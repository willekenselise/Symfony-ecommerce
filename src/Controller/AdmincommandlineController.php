<?php

namespace App\Controller;

use App\Entity\Commandline;
use App\Form\Commandline1Type;
use App\Repository\CommandlineRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/admin/commandline")
 */
class AdmincommandlineController extends AbstractController
{
    /**
     * @Route("/", name="app_admincommandline_index", methods={"GET"})
     */
    public function index(CommandlineRepository $commandlineRepository): Response
    {
        return $this->render('admin/commandline/index.html.twig', [
            'commandlines' => $commandlineRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="app_admincommandline_new", methods={"GET", "POST"})
     */
    public function new(Request $request, CommandlineRepository $commandlineRepository): Response
    {
        $commandline = new Commandline();
        $form = $this->createForm(Commandline1Type::class, $commandline);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $commandlineRepository->add($commandline);
            return $this->redirectToRoute('app_admincommandline_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('admin/commandline/new.html.twig', [
            'commandline' => $commandline,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/{id}", name="app_admincommandline_show", methods={"GET"})
     */
    public function show(Commandline $commandline): Response
    {
        return $this->render('admin/commandline/show.html.twig', [
            'commandline' => $commandline,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="app_admincommandline_edit", methods={"GET", "POST"})
     */
    public function edit(Request $request, Commandline $commandline, CommandlineRepository $commandlineRepository): Response
    {
        $form = $this->createForm(Commandline1Type::class, $commandline);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $commandlineRepository->add($commandline);
            return $this->redirectToRoute('app_admincommandline_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('admin/commandline/edit.html.twig', [
            'commandline' => $commandline,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/{id}", name="app_admincommandline_delete", methods={"POST"})
     */
    public function delete(Request $request, Commandline $commandline, CommandlineRepository $commandlineRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$commandline->getId(), $request->request->get('_token'))) {
            $commandlineRepository->remove($commandline);
        }

        return $this->redirectToRoute('app_admincommandline_index', [], Response::HTTP_SEE_OTHER);
    }
}
