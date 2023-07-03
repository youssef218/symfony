<?php

namespace App\Controller;

use App\Entity\Product;
use App\Form\ProductType;
use App\Repository\ProductRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
class ProductController extends AbstractController
{
     
    #[Route('/product', name: 'app_product', methods:['GET'])]
    #[IsGranted("ROLE_ADMIN", statusCode:404, message:"Page not found.")]
    public function index(ProductRepository $Repository): Response
    {
        $products = $Repository->findAll();
        return $this->render('product/index.html.twig', [
            'products' => $products ,
        ]);
    }

    #[Route('/store/product', name: 'app_product_new' , methods:['GET' , 'POST'])]
    #[IsGranted("ROLE_ADMIN", statusCode:404, message:"Page not found.")]
    public function store(
        Request $request ,
        EntityManagerInterface $manager
    ): Response
    {
        $product = new Product();
        $form = $this->createForm(ProductType::class, $product);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $product = $form->getData();
            if ($request->files->get('product')['image']){
                $image = $request->files->get('product')['image'];
                $image_name = time().'_'.$image->getClientOriginalName() ;
                $image->move($this->getParameter('image_directory'),$image_name);
                $product->setImage($image_name);
            }
            $manager->persist($product);
            $manager->flush();
            $this->addFlash(
                'success',
                'Votre product a été bien create'
            );
            return $this->redirectToRoute('app_product');
        }
        
        return $this->render('product/creat.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('/product/details/{id}', name: 'app_product_details' , methods:['GET'])]
    public function show(Product $product ): Response
    {
        return $this->render('product/show.html.twig', [
            'product' => $product ,
        ]);
    }
    #[Route('/product/edit/{id}', name: 'app_product_edit', methods: ['GET', 'POST'])]
    #[IsGranted("ROLE_ADMIN", statusCode:404, message:"Page not found.")]
    public function edit(Product $product, Request $request, EntityManagerInterface $manager): Response
    {
        $form = $this->createForm(ProductType::class, $product);
        $form->handleRequest($request);
        
        if ($form->isSubmitted() && $form->isValid()) {
            if ($request->files->get('product')['image']) {
                $image = $request->files->get('product')['image'];
                $image_name = time() . '_' . $image->getClientOriginalName();
                $image->move($this->getParameter('image_directory'), $image_name);
                $product->setImage($image_name);
            }
            
            $manager->flush();
            $this->addFlash(
                'success',
                'Votre produit a été bien mis à jour.'
            );
            
            return $this->redirectToRoute('app_product');
        }
        
        return $this->render('product/edit.html.twig', [
            'form' => $form->createView(),
        ]);
    }
    

    #[Route('/product/delete/{id}', name: 'app_product_delete', methods:['GET' , 'POST'])]
    #[IsGranted("ROLE_ADMIN", statusCode:404, message:"Page not found.")]
    public function delete(Product $product, EntityManagerInterface $manager): Response
    {
        $filesystem = new Filesystem();
        $imagePath = './uploads/' . $product->getImage();

        if ($filesystem->exists($imagePath)) {
            $filesystem->remove($imagePath);
        }
        
        // Ne supprimez pas tout le répertoire d'images, uniquement l'image du produit
        // $filesystem->remove($this->getParameter('image_directory'));
        
        $manager->remove($product);
        $manager->flush();
        
        $this->addFlash(
            'success',
            'Votre produit a bien été supprimé'
        );
        
        return $this->redirectToRoute('app_product');
    }
    
}
