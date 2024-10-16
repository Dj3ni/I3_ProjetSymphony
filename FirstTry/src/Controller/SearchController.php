<?php

namespace App\Controller;

use App\Enum\EventType;
use App\Form\SearchFormType;
use App\Repository\EventRepository;
use Doctrine\Persistence\ManagerRegistry;
use App\Serializer\EventCustomNameConverter;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;

class SearchController extends AbstractController
{
    public ManagerRegistry $doctrine;

    public function __construct(ManagerRegistry $doctrine)
    {
        $this->doctrine = $doctrine;
    }

    #[Route('/events/search', name: 'event_search')]
    public function eventsSearch(
        Request $req,
        EventRepository $rep,
        SerializerInterface $serializerInterface): Response
    {
        $form = $this->createForm(SearchFormType::class);
        // dd($form);
        $form->handleRequest($req);
        
        if($form->isSubmitted() && $form->isValid()){
            $data = $form->getData();
            // dd($data);
            $events = $rep->findEventByTitles($data);
            $eventsJson = $serializerInterface->serialize ($events,"json", [
                AbstractNormalizer::IGNORED_ATTRIBUTES => ["subscriptions","eventPlaces","userOrganisator", "occurrences"],
            ]);
            return new Response($eventsJson);
            // return $this->render('search/events_show.html.twig', [
            //     "events" => $events,
            //     "eventsJson" => $eventsJson,
            // ]); ;
        }
        else {
            $events = $rep->findAll();
            $eventsJson = $serializerInterface->serialize ($events,"json", [
                AbstractNormalizer::IGNORED_ATTRIBUTES => ["subscriptions","eventPlaces","userOrganisator", "occurrences"]
            ]);
            // dd($eventsJson);
            
            return $this->render('search/events_search.html.twig', [
                'form' => $form,
                "events" => $events,
                "eventsJson" => $eventsJson,
            ]);
        }
    }

    #[Route("events/calendar", name: "calendar")]
    public function Calendar(EventRepository $rep, SerializerInterface $serializerInterface ):Response
    {
        $events = $rep->findAll();
            $eventsJson = $serializerInterface->serialize ($events,"json", [
                AbstractNormalizer::IGNORED_ATTRIBUTES => ["subscriptions","eventPlaces","userOrganisator", "occurrences"]
            ]);
        return $this->render("search/events_calendar.html.twig", [
                "events" => $events,
                "eventsJson" => $eventsJson,
        ]);
    }

    #[Route("events/map", name: "events_map")]
    public function eventsMap(EventRepository $rep, SerializerInterface $serializerInterface ):Response
    {
        $events = $rep->findAll();
            $eventsJson = $serializerInterface->serialize ($events,"json", [
                AbstractNormalizer::IGNORED_ATTRIBUTES => ["subscriptions","eventPlaces","userOrganisator", "occurrences"]
            ]);
        return $this->render("search/events_map.html.twig", [
                "events" => $events,
                "eventsJson" => $eventsJson,
        ]);
    }



    #[Route("/search/{type}", name: "search_type")]
    public function searchByType(EventType $type, EventRepository $rep){
        
        $events = $rep->findByType($type);

        return $this->render("event/events_show.html.twig", [
            "events"=> $events,
        ]);
    }
}
