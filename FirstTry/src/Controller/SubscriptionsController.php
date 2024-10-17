<?php

namespace App\Controller;

use App\Entity\Event;
use App\Entity\EventSubscription;
use App\EventOccurrenceGenerator;
use App\Repository\EventRepository;
use App\Form\EventSubscriptionFormType;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use App\Repository\EventSubscriptionRepository;
use DateTime;
use DateTimeImmutable;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Serializer\SerializerInterface;

class SubscriptionsController extends AbstractController
{
    public ManagerRegistry $doctrine;
    private EventOccurrenceGenerator $occurrenceGenerator;

    public function __construct(ManagerRegistry $doctrine, EventOccurrenceGenerator $occurrenceGenerator){
        $this->doctrine = $doctrine;
        $this->occurrenceGenerator = $occurrenceGenerator;
    }

    #[Route('/event_subscription/{id}', name: 'event_subscription')]
    public function eventSubscription(Event $event, Request $request): Response
    {   
        // 1. Get user
        $user = $this->getUser();
        // dd($user);

        // 2. Create new subscription form
        $occurrences = $this->occurrenceGenerator->generateOccurrences($event);
        
        $form = $this->createForm(EventSubscriptionFormType::class, null, [
            "occurrences" => $occurrences,
        ]);
        
        $form->handleRequest($request);
        
        
        // 4. Send to DB
        if ($form->isSubmitted() && $form->isValid()){
            // dd($es);
            $data = $form->getData();
            // dd($data);
            $participants = $form->get("numberParticipants")->getData();
            // 3. Link the event and the user and set de Date of Subscription
            $es = new EventSubscription();
            $es->setEventSubscripted($event)
                ->setUserSubscriptor($user)
                ->setSubscriptionDate(new DateTimeImmutable())
                ->setNumberParticipants($participants);
            
                // 4.1 Get selected dates
            $selectedDates = $form->get("occurrenceDates")->getData();
            // dd($selectedDates);
            // 4.2 Subscribe for each selected date
            foreach($selectedDates as $date){
                // 4.2.1 Create new Event child
                $newEndDate = clone $event->getDateEnd(); // Ajustez selon la logique de durée
                $eventOccurrence = $this->occurrenceGenerator->createEventOccurrence($event, $date, $newEndDate);
                
                // 4.2.2. Save in DB
                $em = $this->doctrine->getManager();
                $em->persist($eventOccurrence);

                // 4.2.3. Create Subscription for this event
                $subscription = clone $es;
                $subscription ->setEventSubscripted($eventOccurrence);
                $em->persist($subscription);
            }
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

    #[Route ("/calendar/{id}")]
    public function calendar(Event $event, SerializerInterface $serializerInterface){
        // dd($event);
        
        // l'Utilisateur doit être connecté, on va obtenir tous ses evenements (rajoutés avec de Fixtures)
        $user = $this->getUser(); // ATTENTION: la méthode getUser est du CONTROLLER et portera toujours ce nom, même si notre classe est Utilisateur
        // si pas d'Utilisateur, on va au login
        if (is_null($user)) {
            return $this->redirectToRoute("app_login");
        }

        // sinon, on continue. On obtient tous les Evenement de cet utilisateur
        $events = $user->getEventSubscriptions();
        
        $evenementsJSON = $serializerInterface->serialize($events, 'json', [AbstractNormalizer::IGNORED_ATTRIBUTES => ['user',"subscriptions","eventPlaces","userOrganisator","Occurrences","userSubscriptor"]]);
        return $this->render('subscriptions/calendar.html.twig',[
            
            "event"=>$event,
            "evenementsJSON"=>$evenementsJSON,
        ]);
    }

}
