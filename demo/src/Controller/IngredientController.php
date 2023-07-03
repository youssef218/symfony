<?php

namespace App\Controller;


use App\Entity\Ingredient;
use App\Form\IngredientType;
use App\Repository\IngredientRepository;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class IngredientController extends AbstractController
{

    #[Route('/ingredient', name: 'app_ingredient', methods: ['GET'])]
    public function index(IngredientRepository $Repository, PaginatorInterface $paginator, Request $request): Response
    {

        /** 
         *  function afficher all ingredient 
         * @param IngredientRepository $repository
         *  @param PaginatorInterface $paginator
         *  @param Request $request
         *   @return Response
         */
        $ingredients = $paginator->paginate(
            $Repository->findAll(),
            $request->query->getInt('page', 1), /*page number*/
            10 /*limit per page*/
        );
        // dd($ingredients);
        return $this->render('pages/ingredient/index.html.twig', [
            'ingredients' => $ingredients,
            'pagination' => $ingredients
        ]);
    }
    /**
     * pour ajouter nouveau ingredient
     *
     * @param Request $request
     * @param EntityManagerInterface $manager
     * @return Response
     */
    #[Route('/ingredient/nouveau', name: 'new_ingredient', methods: ['GET', 'POST'])]
    public function new(
        Request $request,
        EntityManagerInterface $manager,
    ): Response {
        $ingredient = new Ingredient();
        $form = $this->createForm(IngredientType::class, $ingredient);

        $form->handleRequest($request);
        // $form->submit($request->request->get($form->getName()));
        if ($form->isSubmitted() && $form->isValid()) {
            $ingredient = $form->getData();
            $manager->persist($ingredient);
            $manager->flush();
            // $this->redirectToRoute('app_ingredient');
            $this->addFlash(
                'success',
                'Votre ingredient a été ajoutée'
            );
            return $this->redirectToRoute('app_ingredient');
        }


        return $this->render('pages/ingredient/new.html.twig', [
            'form' => $form->createView()
        ]);
    }
    /**
     * update object
     *
     * @param Request $request
     * @param EntityManagerInterface $manager
     * 
     */
    #[Route('/ingredient/edition/{id}', name: 'edit_ingredient', methods: ['GET', 'POST'])]
    public function edit(Ingredient $ingredient, Request $request, EntityManagerInterface $manager): Response
    {
        // $ingredient = $Repository->findOneBy(['id' => $id]);
        $form = $this->createForm(IngredientType::class, $ingredient);
        $form->handleRequest($request);
        // $form->submit($request->request->get($form->getName()));
        if ($form->isSubmitted() && $form->isValid()) {
            $ingredient = $form->getData();
            $manager->persist($ingredient);
            $manager->flush();
            // $this->redirectToRoute('app_ingredient');
            $this->addFlash(
                'success',
                'Votre ingredient a été modifie'
            );
            return $this->redirectToRoute('app_ingredient');
        }
        return $this->render('pages/ingredient/edit.html.twig', [
            'form' => $form->createView()
        ]);
    }
    /**
     * suppression object
     *
     * @param Request $request
     * @param EntityManagerInterface $manager
     */
    #[Route('/ingredient/suppression/{id}', name: 'delete_ingredient', methods: ['GET'])]
    public function delete(Ingredient $ingredient, EntityManagerInterface $manager): Response
    {
        if (!$ingredient) {
            $this->addFlash('success', 'Votre ingredient n;est trouve');
            return $this->redirectToRoute('app_ingredient');
        }
        $manager->remove($ingredient);
        $manager->flush();
        $this->addFlash('success', 'Votre ingredient a été supprimée');
        return $this->redirectToRoute('app_ingredient');
    }
}
