<?php

namespace App\Controller;

use App\Form\RegistrationFormType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class UserController extends AbstractController
{
    #[Route('/profile', name: 'user_profile')]
    public function userProfile(): Response
    {
        $user = $this->getUser();

        $form = $this->createForm(RegistrationFormType::class,$user);
        if($form->isSubmitted() && $form->isValid()){
            
        }

        return $this->render('user/profile.html.twig', [
            'user' => $user,
            "form"=>$form,
        ]);
    }
}
