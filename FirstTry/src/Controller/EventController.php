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
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class EventController extends AbstractController
{
    private ManagerRegistry $doctrine;

    public function __construct(ManagerRegistry $doctrine, EventOccurrenceGenerator $occurrenceGenerator ){
        $this->doctrine = $doctrine;
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
        $occurrences = $event->getOccurrences();
        // dd($occurrences);
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
    #[IsGranted('ROLE_ADMIN')]
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
    
################## Delete Event

    #[Route('/delete_event/{id}', name: 'delete_event')]
    public function deleteEvent(Event $event, Request $request): Response
    {
        // We want to protect deletion by asking if sure:
            if ($request->isMethod("POST")){
                $action = $request->request->get("action"); //listens to the action type
                $em = $this->doctrine->getManager();

                if ($action === "delete_event"){
                    // Delete event and occurrences
    
                    // 2. Get linked entities and remove them        
                    $eventPlaces = $event->getEventPlaces();
                    // dd($eventPlaces);                
                    foreach($eventPlaces as $eventPlace){
                        $em->remove($eventPlace);
                    }
                    
                    // 3. Remove Event
                    $em->remove($event);
                }   
                else if ($action === "delete_occurrences"){
                    // Delete only occurrences
                    foreach ($event->getOccurrences() as $occurrence){
                        $em->remove($occurrence);
                    }
                    return $this->redirectToRoute("event",[
                        "id"=> $event->getId(),
                    ]);
                }

                // 4.Sync in DB
                $em->flush();
                
                // 5. Redirect with message
                $this->addFlash("event_delete_success", "Your event was successfully removed!");
                return $this->redirectToRoute("event_search");
            }

        return $this->render("event/confirm_delete.html.twig", [
            "event"=> $event,
        ]);
    }
            
}