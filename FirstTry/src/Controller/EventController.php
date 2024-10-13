<?php

namespace App\Controller;

use App\Demo;
use App\Entity\Event;
use App\EventOccurrenceGenerator;
use App\Form\CreateEventFormType;
use App\Repository\EventRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class EventController extends AbstractController
{
    private ManagerRegistry $doctrine;
    private EventOccurrenceGenerator $occurrenceGenerator;

    public function __construct(ManagerRegistry $doctrine, EventOccurrenceGenerator $occurrenceGenerator ){
        $this->doctrine = $doctrine;
        $this->occurrenceGenerator = $occurrenceGenerator;
    }


    
############  Show all the events in DB now managed by events/search
    
    #[Route('/events', name: 'events_show')]
    public function showAllEvents(EventRepository $rep): Response
    {
        $events = $rep ->findAll();
        // dd($events);
        
        return $this->render('event/events_show.html.twig', [
            'events' => $events,
        ]);
    }
    
############# Show the Event Informations
    
    #[Route('/event/{id}', name: 'event')]
    public function showEvent(Event $event): Response
    {
        // dd($event);

        // Init occurrences
        $occurrences = $this->occurrenceGenerator->generateOccurrences($event);
        $places = $event->getEventPlaces();
        // dd($places);

        return $this->render('event/event_info.html.twig', [
            'event' => $event,
            "occurrences"=> $occurrences,
            "places"=>$places,
        ]);
    }
    
############# Creation Event Form

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
            $this->addFlash("event_create_success", "Event successfully created!");
            return $this->redirectToRoute("event_search");
        }
        return $this->render('event/event_create_form.html.twig', [
            'form' => $form,
        ]);
    }
    
############### Update Event Form

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
            $this->addFlash("event_update_success", "Your event is now up-to-date");
            return $this->redirectToRoute("event_search");
        }
        
        return $this->render('event/event_update_form.html.twig', [
            'form' => $form,
            "event"=> $event,
        ]);
    }
    
################## Delete Event Form

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
        
        // 5. Redirect with message
        $this->addFlash("event_delete_success", "Your event was successfully removed!");
        return $this->redirectToRoute("event_search");
    }
    
    
    // For debug event occurences service

    // #[Route("/event/{id}/occurrences")]
    // public function showEventOccurrences( Event $event):Response
    // {

        // Init Event Occurrences
    
    //     if (!$event) {
        //         throw $this->createNotFoundException('Event not found');
        //     }
        //     $occurrences = $this->occurrenceGenerator->generateOccurrences($event);
        
        //     return $this->render("event/occurrences.html.twig", [
            //         "event"=>$event,
            //         "occurrences"=>$occurrences,
            //     ]);
            // }
            
        }