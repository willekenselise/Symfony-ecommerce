<?php

namespace App\Controller;

use App\Entity\Address;
use App\Form\Address1Type;
use App\Repository\AddressRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/admin/address")
 */
class AdminaddressController extends AbstractController
{
    /**
     * @Route("/", name="app_adminaddress_index", methods={"GET"})
     */
    public function index(AddressRepository $addressRepository): Response
    {
        return $this->render('admin/address/index.html.twig', [
            'addresses' => $addressRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="app_adminaddress_new", methods={"GET", "POST"})
     */
    public function new(Request $request, AddressRepository $addressRepository): Response
    {
        $address = new Address();
        $form = $this->createForm(Address1Type::class, $address);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $addressRepository->add($address);
            return $this->redirectToRoute('app_adminaddress_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('admin/address/new.html.twig', [
            'address' => $address,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/{id}", name="app_adminaddress_show", methods={"GET"})
     */
    public function show(Address $address): Response
    {
        return $this->render('admin/address/show.html.twig', [
            'address' => $address,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="app_adminaddress_edit", methods={"GET", "POST"})
     */
    public function edit(Request $request, Address $address, AddressRepository $addressRepository): Response
    {
        $form = $this->createForm(Address1Type::class, $address);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $addressRepository->add($address);
            return $this->redirectToRoute('app_adminaddress_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('admin/address/edit.html.twig', [
            'address' => $address,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/{id}", name="app_adminaddress_delete", methods={"POST"})
     */
    public function delete(Request $request, Address $address, AddressRepository $addressRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$address->getId(), $request->request->get('_token'))) {
            $addressRepository->remove($address);
        }

        return $this->redirectToRoute('app_adminaddress_index', [], Response::HTTP_SEE_OTHER);
    }
}
