<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\RegistrationType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class SecurityController extends AbstractController
{
    #[Route('/connexion', name: 'security.login' , methods:['GET' , 'POST'])]
    /**
     * login clien and add securiy path 
     * @param \Symfony\Component\Security\Http\Authentication\AuthenticationUtils $authenticationUtils
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function login(AuthenticationUtils $authenticationUtils): Response
    {
        return $this->render('pages/security/login.html.twig' , [
            'last_username' => $authenticationUtils->getLastUsername(),
            'error' => $authenticationUtils->getLastAuthenticationError()
        ]);
    }

    #[Route('/deconnexion', name:'security.logout', methods: ['GET'])]
    public function logout(): Response
    {
        return $this->render('pages/security/logout.html.twig');
    }

    #[Route('/inscreption', name:'securety.restration', methods: ['GET' , 'POST'])]
    /**
     * Summary of restration
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @param \Doctrine\ORM\EntityManagerInterface $manger
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function restration(
        Request $request ,
        EntityManagerInterface $manger
    ): Response
    {
        $user = new User();
        $user->setRoles(['user_role']);
        $form = $this->createForm(RegistrationType::class, $user);
        $form->handleRequest($request);
        
        if ($form->isSubmitted() && $form->isValid()) {
            $user = $form->getData();
            $this->addFlash(
                'success',
                'Votre compt a été bien create'
            );
            $manger->persist($user);
            $manger->flush();
            return $this->redirectToRoute('security.login');     
        }
        return $this->render('pages/security/restration.html.twig' , [
            'form' => $form->createView(),
        ]);
    }


}
