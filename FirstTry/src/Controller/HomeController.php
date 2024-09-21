<?php

namespace App\Controller;

use App\Repository\EventRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
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
}
