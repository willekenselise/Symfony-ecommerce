<?php

namespace App\Controller;

use App\Entity\Order;
use App\Entity\Commandline;
use App\Form\OrderType;
use App\Repository\OrderRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use App\Repository\ProductRepository;
use Symfony\Component\Security\Core\Security;

/**
 * @Route("/order")
 */
class OrderController extends AbstractController
{
    
    /**
     * @Route("/add", name="app_order_add", methods={"GET", "POST"})
     */
    public function add(Security $security, Request $request, OrderRepository $orderRepository, SessionInterface $session, ProductRepository $productRepository, EntityManagerInterface $entityManager): Response
    {
        $cart = $session->get('cart', []);
        $user = $security->getUser();

        if ($cart === []) {
            return $this->redirectToRoute('app_cart_index');
        }

        if (!$user) {
            return $this->redirectToRoute('app_login');
        }

        foreach ($cart as $id => $quantity) {
            $cartData[] = [
                'product' => $productRepository->find($id),
                'quantity' => $quantity
            ];
        }

        $order = new Order();
        $order->setUser($user);
        $entityManager->persist($order);
        $entityManager->flush();

        foreach( $cartData as $item ){
            $commandline = new Commandline();
            $commandline->setProduct($item['product']);
            $commandline->setQuantity($item['quantity']);
            $commandline->setOrderid($order);
            $entityManager->persist($commandline);
            $entityManager->flush();
            $order->addCommandline($commandline);
        }        
        $entityManager->persist($order);
        $entityManager->flush();

        $session->set('cart', []);

        return $this->redirectToRoute('app_product_index');
    }

    /**
     * @Route("/{id}", name="app_order_show", methods={"GET"})
     */
    public function show(Order $order): Response
    {
        return $this->render('order/show.html.twig', [
            'order' => $order,
        ]);
    }

   
}