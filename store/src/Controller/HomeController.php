<?php

namespace App\Controller;

use App\Entity\Category;
use App\Repository\CategoryRepository;
use App\Repository\ProductRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class HomeController extends AbstractController
{
    #[Route('/', name: 'app_home' ,  methods:['GET'])]
    public function index(ProductRepository $Repository , CategoryRepository $categoryRepository): Response
    {
        $products = $Repository->findAll();
        $categry = $categoryRepository->findAll();
        return $this->render('home/index.html.twig', [
            'products' => $products ,
            'categories'=> $categry , 
        ]);
    }

    #[Route('/product/{category}', name: 'product_category' , methods:['GET' , 'POST'])]
    public function categoryproduct(Category $category , CategoryRepository $categoryRepository): Response
    {
        $categry = $categoryRepository->findAll();
        return $this->render('home/index.html.twig', [
            'products' => $category->getProducts(),
            'categories'=> $categry , 
        ]);
    }
}
