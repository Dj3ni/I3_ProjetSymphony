<?php

namespace App;

use App\Entity\Event;

class EventOccurrenceGenerator
{
    public function __construct($test)
    {
        // dd($test);
        // Pas de dépendances à injecter pour l'instant
    }

    public function generateOccurrences(Event $event):array
    {
        dd($event);
        $occurrences = [];

        // Get start date and OcurrenceType
        $dateStart = $event->getDateStart();
        $recurrenceType = $event->getRecurrenceType();
        $recurrenceEnd = $event->getRecurrenceEnd();
        $recurrenceCount = $event->getRecurrenceCount();

        // Manage nbr ocurrencies
        $currentDate = clone $dateStart;
        $count = 0;

        // Iterate until end occurrenceCount or Date

        while(($recurrenceEnd && $currentDate <= $recurrenceEnd) ||($recurrenceCount && $count < $recurrenceCount)) {
            // Ajouter l'occurrence actuelle
            $occurrences[] = clone $currentDate;

            // Incrémenter la date en fonction du type de récurrence
            switch ($recurrenceType) {
                case 'daily':
                    $currentDate->modify('+1 day');
                    break;
                case 'weekly':
                    $currentDate->modify('+1 week');
                    break;
                case 'monthly':
                    $currentDate->modify('+1 month');
                    break;
                case 'yearly':
                    $currentDate->modify('+1 year');
                    break;
                case "none":
                    break;
                default:
                    throw new \InvalidArgumentException('Type de récurrence inconnu.');
            }

            // Incrémenter le compteur d'occurrences
            $count++;
        }

        return $occurrences;

    }



}