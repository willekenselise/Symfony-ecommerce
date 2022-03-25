<?php

namespace App\Controller;

use App\Entity\Product;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use App\Repository\ProductRepository;
use Symfony\Component\Security\Core\Security;
use App\Repository\AddressRepository;

class CartController extends AbstractController
{
    /**
     * @Route("/cart", name="app_cart_index")
     */
    public function index(Security $security, SessionInterface $session, ProductRepository $productRepository, AddressRepository $addressRepository)
    {

        $user = $security->getUser();
        $addresses = $addressRepository->findBy(array('user' => $user));
        $cart = $session->get("cart");

        $dataCart = [];
        $total = 0;

        if(!empty($cart)){
        foreach ( $cart as $id => $quantity){
            $product = $productRepository->find($id);
            $dataCart[] = [
                "product" => $product,
                "quantity" => $quantity,
            ];

            $total += $product->getPrice() * $quantity;
        }}

        return $this->render('cart/index.html.twig', compact("dataCart", "total", "addresses"));
    }

     /**
     * @Route("/cart/add/{id}", name="cart_add")
     */
    public function add(Product $product, SessionInterface $session)
    {
        $cart = $session->get( "cart", []);
        $id = $product->getId();   
        
        if( !empty($cart[$id]) ){
            $cart[$id]++;
        }else{
            $cart[$id]=1;
        }

        $session->set("cart", $cart);

        return $this->redirectToRoute('home');
    }


    /**
     * @Route("cart/remove/{id}", name="cart_remove")
     */
    public function remove(Product $product, SessionInterface $session){

        $cart = $session->get( "cart", []);
        $id = $product->getId();   
        
        if(!empty($cart[$id])){
            if($cart[$id] > 1 ){
                $cart[$id]--;
            }else{
                unset($cart[$id]);
            }
        }

        $session->set("cart", $cart);

        return $this->redirectToRoute("home");
       
    }

    /**
     * @Route("cart/delete/{id}", name="cart_delete")
     */
    public function delete(Product $product, SessionInterface $session){

        $cart = $session->get( "cart", []);
        $id = $product->getId();   
        
        if(!empty($cart[$id])){
            unset($cart[$id]); 
        }

        $session->set("cart", $cart);

        return $this->redirectToRoute("home");
       
    }
}
