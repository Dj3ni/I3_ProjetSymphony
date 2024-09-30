<?php

namespace App\Form;

use App\Entity\User;
use App\Entity\Event;
use App\Entity\EventSubscription;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class EventSubscriptionFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        
        foreach ($options["occurrences"] as $occurrence) {
                $builder 
                ->add("occurrence_". $occurrence->getId(), CheckboxType::class,[
                    "required" => false,
                    "label" => $occurrence->getDateStart()->format("d/m/Y H:i")
                ]);
        }
        $builder
        ->add("numberParticipants", IntegerType::class )
        ->add('save', SubmitType::class, ['label' => 'Subscribe']);
        
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => EventSubscription::class,
            "occurrences" => [], // default value for occurrences
        ]);
    }
}
