<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Symfony\Component\HttpFoundation\RedirectResponse;
class SecurityController extends AbstractController
{
    

    #[Route(path: '/login', name: 'app_login')]
    public function login(AuthenticationUtils $authenticationUtils): Response
    {

    
        $error = $authenticationUtils->getLastAuthenticationError();
        $lastUsername = $authenticationUtils->getLastUsername();
    
        if ($error) {
            // Log error to console
            $logger = $this->container->get('logger');
            $logger->error('Login failed: ' . $error->getMessage());
    
            // Return HTTP error response
            return new Response('Login failed', Response::HTTP_UNAUTHORIZED);
        }
    
        // Redirect to homepage if login is successful
        return new RedirectResponse($this->generateUrl('app_home'));
    }
    #[Route(path: '/logout', name: 'app_logout')]
    public function logout(): void
    {
        throw new \LogicException('This method can be blank - it will be intercepted by the logout key on your firewall.');
    }
}
