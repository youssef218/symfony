<?php

namespace App\Controller;

use App\Entity\Category;
use App\Form\CategoryType;
use App\Repository\CategoryRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class CategoryController extends AbstractController
{
    #[Route('/category', name: 'app_category')]
    #[IsGranted("ROLE_ADMIN", statusCode:404, message:"Page not found.")]
    public function index(CategoryRepository $Repository): Response
    {
        $category = $Repository->findAll();
        return $this->render('category/index.html.twig', [
            'categories' => $category,
        ]);
    }
    
    #[Route('/category/add', name: 'app_category_add' , methods:['GET' , 'POST'])]
    #[IsGranted("ROLE_ADMIN", statusCode:404, message:"Page not found.")]
    public function new(
        Request $request ,
        EntityManagerInterface $manager
    ): Response
    {
        $category = new Category();
        $form = $this->createForm(CategoryType::class, $category);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $manager->persist($category);
            $manager->flush();
            $this->addFlash('success', 'Category added successfully.');
            return $this->redirectToRoute('app_category');
        }
        return $this->render('category/new.html.twig', [
            'form' => $form->createView()
        ]);
        
    }

    #[Route('/category/edit/{id}', name: 'app_category_edit', methods: ['GET', 'POST'])]
    #[IsGranted("ROLE_ADMIN", statusCode:404, message:"Page not found.")]
    public function edit(
        Request $request,
        Category $category,
        EntityManagerInterface $manager
    ): Response
    {
        $form = $this->createForm(CategoryType::class, $category);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $manager->persist($category);
            $manager->flush();
            $this->addFlash('success', 'Category updated successfully.');
            return $this->redirectToRoute('app_category');
        }
        return $this->render('category/new.html.twig', [
            'form' => $form->createView()
        ]);
    }
    #[Route('/category/delete/{id}', name: 'app_category_delete' , methods:['GET' , 'POST'])]
    #[IsGranted("ROLE_ADMIN", statusCode:404, message:"Page not found.")]
    public function delete(
        Category $category,
        EntityManagerInterface $manager
    ): Response
    {
        $manager->remove($category);
        $manager->flush();
        $this->addFlash('success', 'Category deleted successfully.');
        return $this->redirectToRoute('app_category');
    }
    
}
