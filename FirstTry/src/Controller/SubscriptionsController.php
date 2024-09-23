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
        $user = $this->getUser();
        // dd($user);

        if ($user === null){
            // redirect to loginPage, then come back here

            return $this->redirectToRoute("app_login", [
                "redirect"=> $this->generateUrl('event_subscription', ['id' => $event->getId()])
            ]);
        }

        else{
            // Cfréate the form
            $es = new EventSubscription();
            
            $form = $this->createForm(EventSubscriptionFormType::class, $es);
            
            $form->handleRequest($request);
    
            // Link the event and the user and set de Date of Subscription
            $es ->setEventSubscripted($event);
            $es ->setUserSubscriptor($user);
            $es ->setSubscriptionDate(new DateTimeImmutable());
    
            if ($form->isSubmitted() && $form->isValid()){
                // dd($es);
                $em = $this->doctrine->getManager();
                $em->persist($es);
                $em->flush();
                return $this->redirectToRoute("events_show");
            }
        }


        // $form=$this->createForm(EventSubscriptionFormType::class,$event);
        return $this->render('event/event_subscription.html.twig',[
            "form"=>$form,
        ]);
    }




}
