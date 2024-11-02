<?php

namespace App\Controller;

use App\Demo;
use App\Entity\Address;
use App\Entity\Event;
use App\Entity\EventOccurrence;
use App\Entity\EventPlace;
use App\Entity\GamingPlace;
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
        // foreach ($places as $place){
        //     $place->getGamingPlace()->getAddress();
        // }
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
    public function createEvent(Request $request, EventOccurrenceGenerator $occurrenceGenerator): Response
    {
        $em = $this->doctrine->getManager();

        // 1. Create new empty object
        $event = new Event();
        $eventPlace = new EventPlace();
        $gamingPlace = new GamingPlace();
        $gamingAddress = new Address();

        $em->persist($gamingAddress);
        $gamingPlace->setAddress($gamingAddress);
        $em->persist($gamingPlace);
        $eventPlace->setGamingPlace($gamingPlace);
        $event->addEventPlace($eventPlace);
        $em->persist($eventPlace);
        $em->persist($event);
        // 2. Create new Form
        $form = $this->createForm(CreateEventFormType::class, $event);
        $form->handleRequest($request);
        
        // 3.Send in DB
        if ($form->isSubmitted() && $form->isValid()) {
            // dd($form->getErrors(true, false));
            // dd($form);
            $event->setUserOrganisator($this->getUser());
            $em->persist($event);
            // Create Occurrences
            $occurrenceGenerator->generateOccurrences($event);
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
    public function updateEvent(Event $event, EventOccurrenceGenerator $occurrenceGenerator, Request $request): Response
    {
        $em = $this->doctrine->getManager();
        
        // 1. Stock old recurrence to check if change
        $oldReccurrence = $event->getRecurrenceType();
        
        // 2. Create new Form
        $form = $this->createForm(CreateEventFormType::class, $event);
        $form->handleRequest($request);
        
        if ($form->isSubmitted() && $form->isValid()) {
            // dd($form);
            //  Check if recurrence change
            if ($event->getRecurrenceType() !== $oldReccurrence){
                // Remove old occurrences
                foreach($event->getOccurrences() as $occurrence){
                    $event->removeOccurrence($occurrence);
                }
                // create new occurences
                $occurrenceGenerator->generateOccurrences($event);
            }
            // 3.Send in DB
            $em->flush();
            
            $this->addFlash("event_update_success", "Your event is now up-to-date");
            return $this->redirectToRoute("event", ["id"=> $event->getId()]);
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