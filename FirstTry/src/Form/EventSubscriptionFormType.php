<?php

namespace App\Form;

use App\Entity\User;
use App\Entity\Event;
use App\Entity\EventSubscription;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class EventSubscriptionFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            // ->add('subscriptionDate', null, [
            //     'widget' => 'single_text',
            // ])
            // ->add('userSubscriptor', EntityType::class, [
            //     'class' => User::class,
            //     'choice_label' => 'id',
            // ])
            // ->add('eventSubscripted', EntityType::class, [
            //     'class' => Event::class,
            //     'choice_label' => 'id',
            // ])
            ->add("occurrenceDates", ChoiceType::class,[
                // "choices" => array_combine(
                //     array_map(fn($occurrence) => $occurrence->format('Y-m-d H:i:s'), $options["occurrences"]), // Les clés (formatées)
                //     $options["occurrences"] 
                // ),
                "choices" => $options["occurrences"], //give all occurrences
                "multiple" => true,
                "expanded" => true, //checkbox
                "choice_label" => function($choice, $key, $value){
                    return $choice->format("d-m-Y H:i");
                },
                "label" => "Select the date(s) you want to subscribe for"
            ])
            ->add("numberParticipants", NumberType::class )
            ->add('save', SubmitType::class, ['label' => 'Subscribe']);
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => EventSubscription::class,
            "occurrences" => [], // default value for occurrences
        ]);
    }
}
