<?php

namespace App;

use App\Entity\Event;
use App\Enum\RecurrenceType;

class EventOccurrenceGenerator
{
    public function __construct($test)
    {
        // dd($test);
        // Pas de dépendances à injecter pour l'instant
    }

############## For each event occurence, I create a "child" event

    public function createEventOccurrence(Event $event, \DateTimeInterface $newStartDate, \DateTimeInterface $newEndDate) : Event{
        
        // 1. Clone original event
        $occurrence = clone $event;

        // 2. Modify Dates
        $occurrence->setDateStart($newStartDate)
                    ->setDateEnd($newEndDate);
        
        return $occurrence;
    }

############# Switch case for the recurrenceType

    public function switchCasesOccurrences($recurrenceType, $currentStartDate, $currentEndDate ){
        switch ($recurrenceType) {
            case RecurrenceType::DAILY:
                $currentStartDate->modify('+1 day');
                $currentEndDate->modify('+1 day');
                break;
            case RecurrenceType::WEEKLY:
                $currentStartDate->modify('+1 week');
                $currentEndDate->modify('+1 week');
                break;
            case RecurrenceType::MONTHLY:
                $currentStartDate->modify('+1 month');
                $currentEndDate->modify('+1 month');
                break;
            case RecurrenceType::YEARLY:
                $currentStartDate->modify('+1 year');
                $currentEndDate->modify('+1 year');
                break;
            default:
                throw new \InvalidArgumentException('Type de récurrence inconnu.');
        }
    }

############## I create as many date occurrences as asked for an event

    public function generateOccurrences(Event $event):array
    {
        //for debug
        // dd($event);
        
        // $occurrences = [
        //     new \DateTime('2024-01-01 10:00'),
        //     new \DateTime('2024-01-02 10:00'),
        //     new \DateTime('2024-01-03 10:00'),
        // ];

        // return $occurrences; 

        $occurrencesWithEnd = [];
        // Get start date and OcurrenceType from Form
        $dateStart = $event->getDateStart();
        $dateEnd = $event->getDateEnd();
        $recurrenceType = $event->getRecurrenceType();
        $recurrenceEnd = $event->getRecurrenceEnd();
        $recurrenceCount = $event->getRecurrenceCount();
        
        // Manage nbr ocurrencies
        $currentStartDate = clone $dateStart;
        $currentEndDate = clone $dateEnd;
        $count = 0;

        // Case 1: If no endDate selected:
        if($recurrenceEnd === null && $recurrenceCount === null){
            $recurrenceCount = 5; // default value I chose
        }

        // Case 2: If endDate but no $recurrenceCount (I want it to auto increment itself)
        if ($recurrenceEnd !== null && $recurrenceCount === null) {
            $recurrenceCount = 0 ;
            $tempStartDate = clone $currentStartDate; // temp to avoid modifing currentDate
            $tempEndDate = clone $currentEndDate;

            while($tempStartDate <= $recurrenceEnd){
                // Increment Date
                $this->switchCasesOccurrences($recurrenceType, $tempStartDate, $tempEndDate);
                
                // Increment $recurrenceCount
                $recurrenceCount++;
            }
        }
        

        // Case 3: Iterate until end occurrenceCount or Date
        if ($recurrenceType === RecurrenceType::NONE){ // what to do if case == none

            $occurrencesWithEnd[] = [
                "startDate" => $currentStartDate,
                "endDate" => $currentEndDate,
            ];
        }
        else{
            while(
                ($recurrenceEnd !== null && $currentStartDate <= $recurrenceEnd) ||
                ($recurrenceCount!== null && $count < $recurrenceCount)
                ) {
                    if ((!$currentStartDate || !$currentEndDate) instanceof \DateTime) {
                        throw new \Exception('currentDate is not an instance of \DateTime inside the loop');
                    }

                // Ajouter l'occurrence actuelle
                $occurrencesWithEnd[] = [
                    "startDate" => clone $currentStartDate,
                    "endDate" => clone $currentEndDate,
                ];

                $this->switchCasesOccurrences($recurrenceType, $currentStartDate, $currentEndDate);

                // Incrémenter le compteur d'occurrences
                $count++;
            }
        }

        return $occurrencesWithEnd;

    }

}