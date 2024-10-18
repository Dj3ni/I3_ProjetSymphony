<?php

namespace App\Form;

use App\Entity\EventSubscription;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;

class EventOccurrenceSubscriptionFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        // dd($options);
        $occurrence = $options["data"]->getOccurrence();
        // dd($occurrence);
        
            $builder
            -> add("eventOccurrence", TextType::class,[
                "mapped" => false,
                "data"=> $occurrence->getDateStart()->format("d-m-Y H:i"),
                "disabled"=> true,
                "expanded"=> true,            
    
            ])
            -> add("numberParticipants", IntegerType::class,[
                "label" => "Number of participants",
                "required" => true,
            ]);
        
                /* First test
                ->add("occurrence", EntityType::class,[
                    // "choices" => array_combine(
                        //     array_map(fn($occurrence) => $occurrence->format('Y-m-d H:i:s'), $options["occurrences"]), // Les clés (formatées)
                        //     $options["occurrences"] 
                        // ),
                        "class"=>"App\Entity\EventOccurrence",
                        "choices" => $options["occurrences"], //give all occurrences
                        "multiple" => true,
                        "expanded" => true, //checkbox
                        "choice_label" => function($occurrence){
                            return $occurrence->getDateStart()->format("d-m-Y H:i");
                        },
                        "label" => "Select the date(s) you want to subscribe for"
                        ])
                        ->add("numberParticipants", IntegerType::class )
                        */ 

                /* Fields created with FormType 
                    ->add('subscriptionDate', null, [
                        'widget' => 'single_text',
                    ])
                    ->add('userSubscriptor', EntityType::class, [
                        'class' => User::class,
                        'choice_label' => 'id',
                    ])
                    ->add('eventSubscripted', EntityType::class, [
                        'class' => Event::class,
                        'choice_label' => 'id',
                    ])*/
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










//         $builder
//             ->add('dateStart', null, [
//                 'widget' => 'single_text',
//             ])
//             ->add('dateEnd', null, [
//                 'widget' => 'single_text',
//             ])
//             ->add('event', EntityType::class, [
//                 'class' => Event::class,
//                 'choice_label' => 'id',
//             ])
//         ;
//     }

//     public function configureOptions(OptionsResolver $resolver): void
//     {
//         $resolver->setDefaults([
//             'data_class' => EventOccurrence::class,
//         ]);
//     }
// }
