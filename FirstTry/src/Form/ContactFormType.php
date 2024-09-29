<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Required;

class ContactFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add("name", TextType::class,[
                "required" => true,
                "empty_data" => "",
                "attr"=>[
                    "placeholder" => "Your name"
                ],
                // 'constraints' => NotBlank::CLASS_CONSTRAINT,

            ])
            ->add("email", TextType::class,[
                "required" => true,
                "empty_data" => "",
                "attr"=>[
                    "placeholder" => "Your email"
                ],
                // 'constraints' => NotBlank::CLASS_CONSTRAINT,
            ])
            ->add("message", TextareaType::class,[
                "required" => true,
                "empty_data" => "",
                "attr"=>[
                    "placeholder" => "Write your message here"
                ],
                // 'constraints' => NotBlank::CLASS_CONSTRAINT,
            ] )
            ->add("save", SubmitType::class, [
                'label' => 'Contact us'
                ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            // Configure your form options here
        ]);
    }
}