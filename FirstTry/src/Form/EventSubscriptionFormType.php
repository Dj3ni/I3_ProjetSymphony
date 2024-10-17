<?php

namespace App\Form;

use App\Entity\User;
use App\Entity\Event;
use App\Entity\EventOccurrence;
use App\Entity\EventSubscription;
use ContainerVK0bxpg\getEventOccurrenceSubscriptionFormTypeService;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\ChoiceList\Factory\Cache\ChoiceLabel;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\FormType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class EventSubscriptionFormType extends AbstractType

{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {


    
        // Case 1 doesn't work: it passes everything correctly to subform but impossible to get the occurrences for this subform 
        // Case 1: dynamic field generation with occurrence and partcicipants, we need a subform to generate those 2 fields
        // $event = $options['event'];
        // dd($event);
        // $occurrences = $options["occurrences"];
        // foreach($occurrences as $occurrence){
        //     $builder
        //     ->add("eventOccurrences",EventOccurrenceSubscriptionFormType::class, [
        //         "data"=>$occurrence,
        //         "label" =>false,
        //         "mapped"=>false,
        //     ]);
        // }

        // $builder
            // ->add ("eventOccurrences", CollectionType::class, [
            //     "label" => "Select your date(s) to subscribe:",
            //     "entry_type" => EventOccurrenceSubscriptionFormType::class, //subform
            //     "entry_options" => [
            //         "label" =>false,
            //         "occurrence " => $occurrences,
            //     ],
            //     "allow_add" => false,
            //     "mapped" => false, //don't map to the property
            //     "data"=> $occurrences,
            // ])
        
        // Case 2: only one numberparticipants field, occurrences for this event only + checkbox 
            $builder
                ->add('eventOccurrences', EntityType::class,[
                    "class"=> EventOccurrence::class,
                    "label" => "Select the date: ",
                    // choice for each occurrence
                    "choice_label" => function (EventOccurrence $eventOccurrence){
                        return $eventOccurrence->getDateStart()->format("d-m-Y H:i"). " to ". $eventOccurrence->getDateEnd()->format("d-m-Y H:i");
                    },
                    "multiple"=> true, //multiple choice
                    "expanded"=>true, //checkbox
                    "required"=>true,
                    'query_builder' => function (\App\Repository\EventOccurrenceRepository $repo) use ($options) {
                        // Utilise un query_builder pour restreindre aux occurrences liées à un événement
                        return $repo->createQueryBuilder('o')
                            ->where('o.event = :event')
                            ->setParameter('event', $options['event']);
                    },
                ])  
                ->add('numberParticipants', IntegerType::class, [
                    "label"=> "Number of participants",
                    "attr"=> [
                        "min" => 1,
                    ],
                ])

            ->add('save', SubmitType::class, ['label' => 'Subscribe']);
        
        
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => EventSubscription::class,
            "event"=> null,
            "occurrences" => [], // default value for occurrences
        ]);
    }
}
