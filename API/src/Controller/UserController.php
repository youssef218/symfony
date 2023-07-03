<?php

namespace App\Controller;

use App\Entity\User;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Lexik\Bundle\JWTAuthenticationBundle\Services\JWTTokenManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class UserController extends AbstractController
{
    private $userRepository;
    private $entityManager;
    private $jwtManager;

    public function __construct(
        UserRepository $userRepository,
        EntityManagerInterface $entityManager,
        JWTTokenManagerInterface $jwtManager
    ) {
        $this->userRepository = $userRepository;
        $this->entityManager = $entityManager;
        $this->jwtManager = $jwtManager;
    }

    #[Route('/register', name: 'user_register', methods: ['POST'])]
    public function register(Request $request): JsonResponse
    {
        $data = json_decode($request->getContent(), true);
        $email = $data['email'];
        $password = $data['password'];
        $user_exist =$this->userRepository->findOneByEmail($email);
        if($user_exist){
            return $this->json([
                'message' => 'User already exist',
                'status' => 400
                ], 400);
        }
        $user = new User();
        $user->setEmail($email);
        $user->setPassword($password);

        $this->entityManager->persist($user);
        $this->entityManager->flush();

        return new JsonResponse(['message' => 'User registered successfully.']);
    }

    #[Route('/login', name: 'user_login', methods: ['POST'])]
    public function login(Request $request): JsonResponse
    {

        $data = json_decode($request->getContent(), true);
        $email = $data['email'];
        $password = $data['password'];
        $user = $this->userRepository->findOneByEmail($email);
        if(!$user){
            return $this->json([
                'status' => 400 ,
                'message' => 'User Not exist'
                ], 400);
        }

        if ($user->getPassword() !== $password) {
            return new JsonResponse(['message' => 'Invalid credentials.'], JsonResponse::HTTP_UNAUTHORIZED);
        }

        $token = $this->jwtManager->create($user);
        return new JsonResponse(['token' => $token]);
    }

    #[Route('/logout', name: 'user_logout', methods: ['POST'])]
    public function logout(): JsonResponse
    {
        // No action needed for now, as JWT tokens are stateless.
        return new JsonResponse(['message' => 'Logged out successfully.']);
    }

    #[Route('/profile', name: 'user_profile', methods: ['GET'])]
public function profile(): JsonResponse
{
    $user = $this->getUser();
        dd($user);
    if (!$user) {
        return $this->json([
            'message' => 'Not connected',
            'status' => 401
        ], 401);
    }
    
    return $this->json($user);
}

    
}
