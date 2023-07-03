<?php

namespace App\Controller;

use App\Entity\Recipe;
use App\Form\RecipeType;
use App\Repository\RecipeRepository;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class RecipeController extends AbstractController
{
    /**
     * afficher tout les recette
     *
     * @param PaginatorInterface $paginator
     * @param Request $request
     * @param RecipeRepository $Repository
     * @return Response
     */
    #[Route('/recette', name: 'recette.index' , methods:['GET'])]
    public function index(
        PaginatorInterface $paginator ,
        Request $request ,
        RecipeRepository $Repository ,
    ): Response
    {
        $recipes = $paginator->paginate(
            $Repository->findAll(),
            $request->query->getInt('page', 1), /*page number*/
            10 /*limit per page*/
        );
        return $this->render('pages/recipe/index.html.twig',[
            'recipe' => $recipes,
            'recipes' => $recipes
        ]);
    }
    /**
     * create a reccete
     *
     * @param Request $request
     * @param EntityManagerInterface $manager
     * @return Response
     */
    #[Route('/recette/new', name: 'new.index' , methods:['GET' , 'POST'])]
    public function new(
        Request $request ,
        EntityManagerInterface $manager
    ): Response
    {
        $recipe = new Recipe();
        $form = $this->createForm(RecipeType::class, $recipe);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $recipe = $form->getData();
            $manager->persist($recipe);
            $manager->flush();
            $this->addFlash(
                'success',
                'Votre recette a été ajoutée'
            );
            return $this->redirectToRoute('recette.index');
        }

        // dd($recipe);
        return $this->render('pages/recipe/new.html.twig' ,[
            'form' => $form->createView()
            
        ]);
    }


    #[Route('/recette/edition/{id}', name: 'edit_recette', methods: ['GET', 'POST'])]
    /**
     * Summary of editt
     * @param \App\Entity\Recipe $recipe
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @param \Doctrine\ORM\EntityManagerInterface $manager
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function edit(Recipe $recipe, Request $request, EntityManagerInterface $manager): Response
    {
        $form = $this->createForm(RecipeType::class , $recipe);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $recipe = $form->getData();
            $manager->persist($recipe);
            $manager->flush();
            $this->addFlash(
                'success',
                'Votre recette a été modifie'
            );
            return $this->redirectToRoute('recette.index');
        }
        return $this->render('pages/recipe/edit.html.twig', [
            'form' => $form->createView()
        ]);
    }

    #[Route('/recette/suppression/{id}', name: 'delete_recette', methods: ['GET'])]
    /**
     * delete a recette
     * @param \App\Entity\Recipe $recipe
     * @param \Doctrine\ORM\EntityManagerInterface $manager
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function deletet(Recipe $recipe, EntityManagerInterface $manager): Response
    {
        if (!$recipe) {
            $this->addFlash('success', 'Votre recette n`est pas trouve');
            return $this->redirectToRoute('recette.index');
        }
        $manager->remove($recipe);
        $manager->flush();
        $this->addFlash('success', 'Votre recette a été supprimée');
        return $this->redirectToRoute('recette.index');
    }
    
}
