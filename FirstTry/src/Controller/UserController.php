<?php

namespace App\Controller;

use App\Form\RegistrationFormType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class UserController extends AbstractController
{
    #[Route('/profile', name: 'user_profile')]
    public function userProfile(Request $req, EntityManagerInterface $em): Response
    {
        $user = $this->getUser();

        $form = $this->createForm(RegistrationFormType::class,$user);
        $form->handleRequest($req);
        if($form->isSubmitted() && $form->isValid()){
            $em->flush();
            return $this->redirectToRoute("user_profile");
        }

        return $this->render('user/profile.html.twig', [
            'user' => $user,
            "form"=>$form,
        ]);
    }

    #[Route('/my_calendar', name:"user_calendar")]
    public function personalCalendar(SerializerInterface $serializerInterface): Response
    {
        $user = $this->getUser();
        $occurrences= $user->getOccurrencesSubscriptions();
        $allOccurrences = [];
        foreach ($occurrences as $occurrence){
            $occurrence = $occurrence->getEventOccurrence();
            $event = $occurrence->getEvent();
            $allOccurrences[] = [
                'id' => $event->getId(),
                'title' => $event->getTitle(),
                'start' => $occurrence->getDateStart()->format('Y-m-d H:i'),
                'end' => $occurrence->getDateEnd()->format('Y-m-d H:i'),
                'eventType' => $event->getEventType()
            ];
        }
        $occurrencesJson = json_encode($allOccurrences);

        // $eventsJson = $serializerInterface->serialize ($events,"json", [
        //     AbstractNormalizer::IGNORED_ATTRIBUTES => ["eventOccurrence","user"]
        // ]);
        // dd($eventsJson);

        return $this->render("user/user_calendar.html.twig", [
            // "events"=> $events,
            // "eventsJson"=>$eventsJson,
            "occurrencesJson" => $occurrencesJson,
        ]);
    }

    #[Route('/my_subscriptions', name:"user_subscriptions")]
    public function personalSubscriptions(): Response
    {
        $user = $this->getUser();
        // dd($user);
        $subscriptions = $user->getOccurrencesSubscriptions();
        // dd($subscriptions);
        
        return $this->render("user/user_subscriptions.html.twig", [
            // "events"=> $events,
            "subscriptions"=>$subscriptions,

        ]);
    }
}
