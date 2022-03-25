<?php

namespace App\Controller;

use App\Entity\Address;
use App\Form\AddressType;
use App\Form\Address3Type;
use App\Repository\AddressRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/address")
 */
class AddressController extends AbstractController
{
    
    /**
     * @Route("/add", name="app_address_add", methods={"GET", "POST"})
     */
    public function add(Request $request, AddressRepository $addressRepository, EntityManagerInterface $entityManager): Response
    {
        $address = new Address();
     
        $form = $this->createForm(Address3Type::class, $address);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $address = $form->getData();
            $address->setUser($this->getUser());

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($address);
            $entityManager->flush();

            return $this->redirectToRoute('app_cart_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('address/add.html.twig', [
            'address' => $address,
            'form' => $form,
        ]);
    }

}
