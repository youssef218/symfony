<?php

namespace App\Controller\api;

use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;

class LoginController extends AbstractController
{
    #[Route('/api/login', name: 'api_login', methods: ['POST'])]
    public function apiLogin(): JsonResponse
    {
        
        $user = $this->getUser();

        $userData = [
            'email' => $user->getEmail(),
            'first_Name' => $user->getFirstName(),
            'last_Name' => $user->getLastName(),
        ];

        return $this->json($userData);
    }

}
