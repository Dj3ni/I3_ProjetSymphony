<?php

namespace App\Controller;

use App\Repository\EventRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class EventController extends AbstractController
{
    public ManagerRegistry $doctrine;
    
    // Show all the events 

    #[Route('/events', name: 'events_show')]
    public function showAllEvents(EventRepository $rep): Response
    {
        $events = $rep ->findAll();
        // dd($events);
        
        return $this->render('event/events_show.html.twig', [
            'events' => $events,
        ]);
    }

    // Show the Event Informations

    #[Route('/event{id}', name: 'event')]
    public function showEvent(int $id, EventRepository $rep): Response
    {
        $event = $rep ->find($id);
        // dd($event);
        
        return $this->render('event/event_info.html.twig', [
            'event' => $event,
        ]);
    }

}
