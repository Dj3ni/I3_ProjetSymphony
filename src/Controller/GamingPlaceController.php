<?php

namespace App\Controller;

use App\Entity\Event;
use App\Entity\Address;
use App\Entity\EventPlace;
use App\Entity\GamingPlace;
use App\Form\EventPlaceFormType;
use App\GeocodingService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class GamingPlaceController extends AbstractController
{
    private EntityManagerInterface $em;
    private GeocodingService $geocodingService;

    public function __construct(EntityManagerInterface $em, GeocodingService $geocodingService){
        $this->em = $em;
        $this->geocodingService = $geocodingService;
    }

    #[Route('/gaming/place', name: 'app_gaming_place')]
    public function index(): Response
    {
        return $this->render('gaming_place/index.html.twig', [
            'controller_name' => 'GamingPlaceController',
        ]);
    }

    #[Route('/remove/{eventPlace}/{event}', name:"remove_gaming_place_event")]
    public function removeGamingPlaceEvent(EventPlace $eventPlace, Event $event){
        
        $event->removeEventPlace($eventPlace);
        $this->em->flush();

        return $this->redirectToRoute("event", ["id"=>$event->getId()]);
    }

    #[Route('add/gamingPlace/{event}', name:"add_gamingPlace_event")]
    public function addGaminPlaceEvent(Event $event, Request $req)
    {
        $eventPlace = new EventPlace();
        $eventPlace->setEvent($event);

        $form = $this->createForm(EventPlaceFormType ::class, $eventPlace);
        $form->handleRequest($req);

        if($form->isSubmitted() && $form->isValid()){
            // dd($form);
            // $chosenGamingPlace = $eventPlace->getGamingPlace();
            
            // $chosenGamingPlace = $form->get("gamingPlace")->getData();
            // dd($chosenGamingPlace);
            // $newGamingPlace = $form->get("newGamingPlace")->getData();
            // $newAddress = $newGamingPlace->getAddress(); 
            $newGamingPlace = $form->get("gamingPlace")->getData();


            // dd($newAddress);

            // if(!$chosenGamingPlace && $newGamingPlace){
                $gamingPlace = new GamingPlace([
                    "name"=> $newGamingPlace->getName(),
                    "type"=>$newGamingPlace->getType(),
                    "description"=>$newGamingPlace->getDescription(),
                    "placeMax" => $newGamingPlace->getPlaceMax()
                ]);

                $addressForm = $newGamingPlace->getAddress();

                $address = new Address([
                    "locality"=> $addressForm->getLocality(),
                    "street"=>$addressForm->getStreet(),
                    "number"=>$addressForm->getNumber(),
                    "city"=>$addressForm->getCity(),
                    "postCode"=>$addressForm->getPostCode(),
                    "country"=>$addressForm->getCountry(),
                ]);

                $coords = $this->geocodingService->getCoordinatesFromAddress($address);

                if($coords){
                    $address->setLat($coords['latitude']);
                    $address->setLon($coords['longitude']);
                }
                else{
                    $address->setLat(null);
                    $address->setLon(null);
                }
                $gamingPlace->setAddress($address);
                $eventPlace->setGamingPlace($gamingPlace);
            // }
            $this->em->persist($eventPlace);
            $this->em->flush();
            $this->addFlash("success", "Your event has a new gaming Place!");            
            return $this->redirectToRoute("event",["id"=>$event->getId()]);
        }

        return $this->render("gaming_place/add_place_event.html.twig", [
            "eventPlace"=>$eventPlace,
            "form"=>$form,
        ]);

    }

}
