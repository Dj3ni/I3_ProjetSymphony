<?php

namespace App\Controller;

use App\Entity\Event;
use App\Form\CreateEventFormType;
use App\Repository\EventRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;

class EventController extends AbstractController
{
    public ManagerRegistry $doctrine;
    public function __construct(ManagerRegistry $doctrine){
        $this->doctrine = $doctrine;
    }
    
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

    #[Route('/event/{id}', name: 'event')]
    public function showEvent(int $id, EventRepository $rep): Response
    {
        $event = $rep ->find($id);
        // dd($event);
        
        return $this->render('event/event_info.html.twig', [
            'event' => $event,
        ]);
    }

    // Creation Event Form
    #[Route('/create_event', name: 'create_event')]
    public function createEvent(Request $request): Response
    {
        // 1. Create new empty object
        $event = new Event();
        // 2. Create new Form
        $form = $this->createForm(CreateEventFormType::class, $event);
        $form->handleRequest($request);
        
        // 3.Send in DB
        if ($form->isSubmitted() && $form->isValid()) {
            // dd($form);
            $em = $this->doctrine->getManager();
            $em->persist($event);
            $em->flush();
            return $this->redirectToRoute("events_show");
        }
        return $this->render('event/event_create_form.html.twig', [
            'form' => $form,
        ]);
    }

    // Update Event Form
    #[Route('/update_event/{id}', name: 'update_event')]
    public function updateEvent(int $id, EventRepository $rep, Request $request): Response
    {
        // 1. Create new empty object
        $event = $rep->find($id);
        // dd($event);
        // 2. Create new Form
        $form = $this->createForm(CreateEventFormType::class, $event);
        $form->handleRequest($request);
        
        if ($form->isSubmitted() && $form->isValid()) {
            // dd($form);
            // 3.Send in DB
            $em = $this->doctrine->getManager();
            
            $em->flush();
            return $this->redirectToRoute("events_show");
        }
        
        return $this->render('event/event_update_form.html.twig', [
            'form' => $form,
        ]);
    }


    // Delete Event Form
    #[Route('/delete_event/{id}', name: 'delete_event')]
    public function deleteEvent(int $id, EventRepository $rep, Request $request): Response
    {
        // 1. Create new empty object
        $event = $rep->find($id);
        // dd($event);

        // 2. Get linked entities and remove them

        $eventPlaces = $event->getEventPlaces();
        // dd($eventPlaces);

        $em = $this->doctrine->getManager();
        
        foreach($eventPlaces as $eventPlace){
            $em->remove($eventPlace);
        }
        
        // 3. Remove Event
        $em->remove($event);
        
        // 4.Sync in DB
        $em->flush();

        // 5. Redirect
        return $this->redirectToRoute("events_show");
    }

}
