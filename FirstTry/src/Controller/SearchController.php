<?php

namespace App\Controller;

use App\Form\SearchFormType;
use App\Repository\EventRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;
use Symfony\Component\Serializer\SerializerInterface;

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

        // if ($req->isXmlHttpRequest()){ //if ajax search
        //     $data = $form->getData();
        //     // dd($data);
        //     $events = $rep->findEventByTitles($data);
        //     $eventsJson = $serializerInterface->serialize ($events,"json", [
        //         AbstractNormalizer::IGNORED_ATTRIBUTES => ["subscriptions","eventPlaces","userOrganisator", "Occurrences"]
        //     ]);
        //     return new Response($eventsJson);
        // }

        if($form->isSubmitted() && $form->isValid()){
            $data = $form->getData();
            // dd($data);
            $events = $rep->findEventByTitles($data);
            $eventsJson = $serializerInterface->serialize ($events,"json", [
                AbstractNormalizer::IGNORED_ATTRIBUTES => ["subscriptions","eventPlaces","userOrganisator", "Occurrences"]
            ]);
            // return $this->redirectToRoute("events",[
            //     "eventsJson"=>$eventsJson,
            //     "events"=>$events,
            // ]);
            return new Response($eventsJson);

        }else{
            $events = $rep->findAll();
        }

        return $this->render('search/events_search.html.twig', [
            'form' => $form,
            "events" => $events,
        ]);
    }
}
