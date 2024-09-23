<?php

namespace App\Controller;

use App\Entity\Event;
use App\Entity\EventSubscription;
use App\Repository\EventRepository;
use App\Form\EventSubscriptionFormType;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use App\Repository\EventSubscriptionRepository;
use DateTime;
use DateTimeImmutable;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;

class SubscriptionsController extends AbstractController
{
    public ManagerRegistry $doctrine;

    public function __construct(ManagerRegistry $doctrine){
        $this->doctrine = $doctrine;
        
    }

    #[Route('/event_subscription/{id}', name: 'event_subscription')]
    public function eventSubscription(Event $event, Request $request): Response
    {   
        // 1. Get user
        $user = $this->getUser();
        // dd($user);
        
        // 2. Create new subscription form
        $es = new EventSubscription();
        $form = $this->createForm(EventSubscriptionFormType::class, $es);
        $form->handleRequest($request);

        // 3. Link the event and the user and set de Date of Subscription
        $es ->setEventSubscripted($event);
        $es ->setUserSubscriptor($user);
        $es ->setSubscriptionDate(new DateTimeImmutable());

        // 4. Send to DB
        if ($form->isSubmitted() && $form->isValid()){
            // dd($es);
            $em = $this->doctrine->getManager();
            $em->persist($es);
            $em->flush();
            return $this->redirectToRoute("events_show");
        }

        return $this->render('event/event_subscription.html.twig',[
            "form"=>$form,
        ]);
    }




}
