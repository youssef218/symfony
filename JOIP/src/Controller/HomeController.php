<?php

namespace App\Controller;

use App\Repository\EventRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
    #[Route('/', name: 'app_home' , methods:['GET' ,'POST'])]
    public function index(EventRepository $Repository): Response
    {
        $event = $Repository->findAll();
        return $this->render('home/index.html.twig', [
            'events' => $event,
        ]);
    }
}
