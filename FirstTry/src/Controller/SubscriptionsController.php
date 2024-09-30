<?php

namespace App\Controller;

use App\Entity\Event;
use App\Entity\EventSubscription;
use App\EventOccurrenceGenerator;
use App\Repository\EventRepository;
use App\Form\EventSubscriptionFormType;
use App\Repository\EventOccurrenceRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use App\Repository\EventSubscriptionRepository;
use DateTime;
use DateTimeImmutable;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;

class SubscriptionsController extends AbstractController
{
    public ManagerRegistry $doctrine;
    private EventOccurrenceGenerator $occurrenceGenerator;

    public function __construct(ManagerRegistry $doctrine, EventOccurrenceGenerator $occurrenceGenerator){
        $this->doctrine = $doctrine;
        $this->occurrenceGenerator = $occurrenceGenerator;
    }

    #[Route('/event_subscription/{id}', name: 'event_subscription')]
    public function eventSubscription(
        Event $event, 
        Request $request,
        EntityManagerInterface $em,
        EventOccurrenceRepository $eoRep): Response
    {   
        // 1. Get user and event occurrencies
        $user = $this->getUser();
        $occurrences = $event->getOccurrences();
        // dd($occurrences);

        // 2. Create new subscription form
        
        $form = $this->createForm(EventSubscriptionFormType::class, null, [
            "occurrences" => $occurrences, // Give occurrences to Form
        ]);
        
        $form->handleRequest($request);
                
        // 3. Send to DB
        if ($form->isSubmitted() && $form->isValid()){
            // dd($es);
            $data = $form->getData();
            // dd($data);
            $participants = $form->get("numberParticipants")->getData();
            $selectedDates = $form->get("occurrence")->getData();
            dd($selectedDates);
            
            foreach ($occurrences as $occurrence){
                $checkboxName = "occurrence_".$occurrence->getId();
                if ($form->get($checkboxName)->getData()){
                    $es = new EventSubscription();
                    $es->setEventSubscripted($event)
                        ->setUserSubscriptor($user)
                        ->setOccurrenceDates($occurrences)
                        ->setSubscriptionDate(new DateTimeImmutable())
                        ->setNumberParticipants($participants);

                    // 4.2.2. Save in DB
                    $em = $this->doctrine->getManager();
                    $em->persist($es);
                }
            }
            
            
            // 3. Link the event and the user and set de Date of Subscription

                // 4.1 Get selected dates
            // dd($selectedDates);
            // 4.2 Subscribe for each selected date
            // foreach($selectedDates as $date){
            //     // 4.2.1 Create new Event child
            //     $newEndDate = clone $event->getDateEnd(); // Ajustez selon la logique de durée
            //     $eventOccurrence = $this->occurrenceGenerator->createEventOccurrence($event, $date, $newEndDate);
                

                // 4.2.3. Create Subscription for this event
                // $subscription = clone $es;
                // $subscription ->setEventSubscripted($eventOccurrence);
                // $em->persist($subscription);
            // }
            $em->flush();
            
            // message to inform the user
            $this->addFlash("subscription_success", "Subscription sent!");
            return $this->redirectToRoute("event_search");
        }

        return $this->render('event/event_subscription.html.twig',[
            "form"=>$form,
            "event"=>$event,
        ]);
    }

}
