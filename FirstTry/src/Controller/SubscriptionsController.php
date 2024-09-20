<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class SubscriptionsController extends AbstractController
{
    #[Route('/event_subscription/{id}', name: 'event_subscription')]
    public function eventSubscription(int $id): Response
    {
        return $this->render('subscriptions/event_subscription.html.twig', [
            'controller_name' => 'SubscriptionsController',
        ]);
    }
}
