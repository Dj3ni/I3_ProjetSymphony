<?php

namespace App\Controller;

use App\Form\RegistrationFormType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

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
    public function personalCalendar(): Response
    {
        return $this->render("user/user_calendar.html.twig", [
            // "events"=> $events,

        ]);
    }
}
