<?php

 
namespace App\Controller;
 
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
 use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
 use Symfony\Component\HttpFoundation\Request;
class HomeController extends AbstractController
{

 private $Passwordhasher;
    public function __construct(UserPasswordHasherInterface $Passwordhasher)
    {
        $this->Passwordhasher = $Passwordhasher;
    }
     #[Route('/{reactRouting}', name: 'app_home', requirements: ['reactRouting' => '^(?!api).+'], defaults: ['reactRouting' => null])]
    // ", name="app_home"
    public function index()
    {
        return $this->render('home/index.html.twig');
    }


 #[Route('/api/user', name: 'api_user_create', methods: ['POST'])]
    public function handleFormData(Request $request, EntityManagerInterface $entityManager): Response
    {
        // Get the form data from the request body
        $formData = json_decode($request->getContent(), true);

        // Handle the form data and perform any necessary actions
        // For example, you can persist the data to the database using Doctrine
        
        // Assuming you have an Entity class named `User` to represent the data
        $plainPassword = $formData['password'];
        $userRegester = new User();
        $hashedPassword = $this->Passwordhasher->hashPassword($userRegester, $plainPassword);
        $userRegester->setEmail($formData['email']);
        $userRegester->setPassword($hashedPassword);
        $userRegester->setTele($formData['tele']);
        $userRegester->setVille($formData['ville']);
        $userRegester->setCin($formData['cin']);

        // Persist the entity
        // dd($userRegester);
        $entityManager->persist($userRegester);
        $entityManager->flush();

        // Return a response if needed
        return $this->json(['message' => 'Form data successfully added to the database.']);

    }
}
