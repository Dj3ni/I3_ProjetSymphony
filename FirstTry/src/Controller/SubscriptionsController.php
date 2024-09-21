<?php

namespace App\Controller;

use App\Repository\EventRepository;
use App\Form\EventSubscriptionFormType;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use App\Repository\EventSubscriptionRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class SubscriptionsController extends AbstractController
{
    public ManagerRegistry $doctrine;

    public function __construct(ManagerRegistry $doctrine){
        $this->doctrine = $doctrine;
        
    }

    #[Route('/event_subscription', name: 'event_subscription')]
    public function eventSubscription(int $id, EventSubscriptionRepository $rep): Response
    {   
        // $event = $rep->find($id);

        // $form=$this->createForm(EventSubscriptionFormType::class,$event);
        return $this->render('event/event_subscription.html.twig');
    }
}
