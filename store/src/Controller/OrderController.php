<?php

namespace App\Controller;

use App\Entity\Order;
use App\Entity\Product;
use App\Repository\OrderRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class OrderController extends AbstractController
{
        #[Route('/order', name: 'app_order' , methods:['GET'])]
        #[IsGranted("ROLE_ADMIN", statusCode:404, message:"Page not found.")]
        public function index(OrderRepository $orderRepository): Response
        {
            $orders = $orderRepository->findAll();
            return $this->render('order/index.html.twig', [
                'orders' => $orders,
            ]);
        }

        #[Route('/order/user', name: 'app_order_user')]
        public function orderuser(): Response
        {
            if(!$this->getUser()){
                return $this->redirectToRoute('app_login');
            }
            return $this->render('order/user.html.twig', [
                'user' => $this->getUser(),
            ]);
        }

    #[Route('/store/order/{product}', name: 'app_order_create' , methods:['GET' , 'POST'])]
    public function create(Product $product , EntityManagerInterface $manager , OrderRepository $orderRepository): Response
    {
        if(!$this->getUser()){
           
            return $this->redirectToRoute('app_login');
        }

        $orderExists = $orderRepository->findOneBy([
            'user'=> $this->getUser(),
            'pname' => $product->getName()
            ]);
            if($orderExists){
                $this->addFlash(
                    'warning',
                    'vous deja order'
                );
                return $this->redirectToRoute('app_order_user');
            }
        $order = new Order();
        $order->setPname($product->getName())
            ->setPrice($product->getPrice())
            ->setStatus('processing')
            ->setUser($this->getUser());
            $manager->persist($order);
            $manager->flush();
            $this->addFlash(
                'success',
                'Votre order a été bien passer'
            );
            return $this->redirectToRoute('app_order_user');

        
    }

    #[Route('/order/{order}/{status}', name: 'app_order_status' , methods:['GET' , 'POST'])]
    #[IsGranted("ROLE_ADMIN", statusCode:404, message:"Page not found.")]
    public function status(Order $order , EntityManagerInterface $manager , $status): Response
    {

        $order->setStatus($status);
            $manager->persist($order);
            $manager->flush();
            $this->addFlash(
                'success',
                'Votre order a été bien envoyer'
            );
            return $this->redirectToRoute('app_order');

        
    }
    #[Route('/order/{order}', name: 'app_order_delete', methods:['GET' , 'POST'])]
    #[IsGranted("ROLE_ADMIN", statusCode:404, message:"Page not found.")]
    public function deleteorder(Order $order, EntityManagerInterface $manager): Response
    {
        $manager->remove($order);
        $manager->flush();
        $this->addFlash(
           'success',
            'Votre order a supprimer'
        );
        return $this->redirectToRoute('app_order');
        }
}
