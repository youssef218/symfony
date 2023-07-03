<?php

namespace App\Controller\api;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class RegesterController extends AbstractController
{
    #[Route('/api/register', name: 'api_register', methods: ['POST'])]
    public function apiRegister(Request $request, UserPasswordHasherInterface $passwordHasher , EntityManagerInterface $Manager): Response
    {
        if($this->getUser()){
            return $this->json([
                'status' => 'success',
                'message' => 'You are logged in',
                'data' => $this->getUser()
                ], 200);
        }


        $user = new User();
        $data = json_decode($request->getContent(), true);

        // Set the user data
        $user->setEmail($data['email']);
        $user->setFirstName($data['first_name']);
        $user->setLastName($data['last_name']);

        // Encode the password
        $hashedPassword = $passwordHasher->hashPassword($user, $data['password']);
        $user->setPassword($hashedPassword);

        // Save the user to the database
        $Manager->persist($user);
        $Manager->flush();

        // Return a success response
        return $this->json([
            'message' => 'User registered successfully.',
            'user_id' => $user->getId(),
        ]);
    }
}
