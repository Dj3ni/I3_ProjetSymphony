<?php

namespace App\Controller;

use App\Form\ContactFormType;
use App\Repository\EventRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class HomeController extends AbstractController
{
    #[Route('/', name: 'home')]
    public function index(EventRepository $rep): Response
    {
        // $em = $this->doctrine()->getManager();
        $lastEvents = $rep->findLastEvents(3);
        return $this->render('home/index.html.twig',[
            "lastEvents" => $lastEvents,
        ]);
    }

    #[Route("/contact", name:"contact" )]
    public function contact(Request $req): Response
    {
        $form = $this->createForm(ContactFormType::class);
        $form->handleRequest($req);
        
        if($form->isSubmitted() && $form->isValid()){
            // dd($form);
            $data = $form->getData();
            // dd($data);
            $this->addFlash("contact_success", "Message received 5/5!");
            return $this->redirectToRoute("contact");
        }

        return $this->render("home/contact.html.twig",[
            "form"=>$form,
        ]);
    }
}
