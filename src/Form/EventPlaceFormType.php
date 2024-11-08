<?php

namespace App\Form;

use App\Entity\Event;
use App\Entity\EventPlace;
use App\Entity\GamingPlace;
use App\Form\GamingPlaceFormType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;

class EventPlaceFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            // ->add('event', EntityType::class, [
            //     'class' => Event::class,
            //     'choice_label' => 'id',
            // ])
            ->add("gamingPlace", GamingPlaceFormType::class)
            // ->add('gamingPlace', EntityType::class, [
            //     'class' => GamingPlace::class,
            //     'choice_label' => 'name',
            //     'placeholder'=> "Choose a gaming place",
            // ])
        ;

    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => EventPlace::class,
        ]);
    }
}
