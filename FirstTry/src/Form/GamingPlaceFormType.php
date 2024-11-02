<?php

namespace App\Form;

use App\Entity\Address;
use App\Entity\GamingPlace;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class GamingPlaceFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', TextType::class,[
                "attr"=>[
                    "placeholder" => "Gaming place awesome name"
                ]
            ])
            ->add('type', TextType::class)
            ->add('description', TextareaType::class)
            ->add('placeMax', IntegerType::class, [
                "attr"=>[
                    "minValue" =>1,
                ],
            ])
            ->add('address', AddressFormType::class, [
                "label"=> "Address"
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => GamingPlace::class,
        ]);
    }
}
