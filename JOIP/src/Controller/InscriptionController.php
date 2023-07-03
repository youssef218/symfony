<?php

namespace App\Controller;

use App\Entity\Apprenant;
use App\Entity\Inscription;
use App\Form\IncriptionType;
use App\Repository\EventRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;


class InscriptionController extends AbstractController
{
    #[Route('/inscription/{id}', name: 'app_inscription' , methods:['GET','POST'])]
    public function index(Request $request, EntityManagerInterface $manager , $id , EventRepository $eventRepository) : Response
    {
        $event = $eventRepository->find($id);
        $apprenant = new Apprenant();
        $form = $this->createForm(IncriptionType::class, $apprenant);
    
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $apprenant = $form->getData();
            $manager->persist($apprenant);

        $inscription = new Inscription();
        $inscription->setEvent($event); // Replace $eventObject with the actual Event object
        $inscription->setAppenant($apprenant); // Replace $apprenantObject with the actual Apprenant object
        $manager->persist($inscription);
            $manager->flush();
            // $this->redirectToRoute('app_ingredient');
            $this->addFlash(
                'success',
                'Votre inscreption a été bien conferme '
            );
            return $this->redirectToRoute('app_home');
            // Handle form submission
            // ...
        }
    
        return $this->render('home/inscription.html.twig', [
            'form' => $form->createView(),
            'id' => $id
        ]);
    }
}
