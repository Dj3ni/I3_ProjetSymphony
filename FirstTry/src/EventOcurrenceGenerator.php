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

    public function generateOccurrences(Event $event):array
    {
        // dd($event);

        // $occurrences = [
        //     new \DateTime('2024-01-01 10:00'),
        //     new \DateTime('2024-01-02 10:00'),
        //     new \DateTime('2024-01-03 10:00'),
        // ];

        // return $occurrences; //for debug

        $occurrences = [];
        $occurrencesWithEnd = [];

        // Get start date and OcurrenceType
        $dateStart = $event->getDateStart();
        $dateEnd = $event->getDateEnd();
        $recurrenceType = $event->getRecurrenceType();
        $recurrenceEnd = $event->getRecurrenceEnd();
        $recurrenceCount = $event->getRecurrenceCount();

        // Manage nbr ocurrencies
        $currentStartDate = clone $dateStart;
        $currentEndDate = clone $dateEnd;

        // if ($currentStartDate instanceof \DateTime) {
        //     $currentStartDate->modify('+1 day'); // Cette ligne ne devrait pas poser problème
        // } else {
        //     throw new \Exception('currentDate is not an instance of \DateTime');
        // }

        // dd($recurrenceEnd);
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
                switch ($recurrenceType) {
                    case RecurrenceType::DAILY:
                        $tempStartDate->modify('+1 day');
                        $tempEndDate->modify('+1 day');
                        break;
                    case RecurrenceType::WEEKLY:
                        $tempStartDate->modify('+1 week');
                        $tempEndDate->modify('+1 week');
                        break;
                    case RecurrenceType::MONTHLY:
                        $tempStartDate->modify('+1 month');
                        $tempEndDate->modify('+1 month');
                        break;
                    case RecurrenceType::YEARLY:
                        $tempStartDate->modify('+1 year');
                        $tempEndDate->modify('+1 year');
                        break;
                    default:
                        throw new \InvalidArgumentException('Type de récurrence inconnu.');
                }
                // Increment $recurrenceCount
                $recurrenceCount++;
            }
        }
        

        // Case 3: Iterate until end occurrenceCount or Date
        if ($recurrenceType === RecurrenceType::NONE){ // what to do if case == none
            // $occurrences[] = clone $currentStartDate;
            // $occurrences[] = clone $currentEndDate;
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
                // $occurrences[] = clone $currentStartDate;
                // $occurrences[] = clone $currentEndDate;
                $occurrencesWithEnd[] = [
                    "startDate" => clone $currentStartDate,
                    "endDate" => clone $currentEndDate,
                ];
    
                // Incrémenter la date en fonction du type de récurrence
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
    
                // Incrémenter le compteur d'occurrences
                $count++;
            }
        }

        return $occurrencesWithEnd;

    }



}