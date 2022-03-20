<?php

namespace App\Controller;

use App\Entity\Commandline;
use App\Form\CommandlineType;
use App\Repository\CommandlineRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/commandline")
 */
class CommandlineController extends AbstractController
{
    /**
     * @Route("/new", name="app_commandline_new", methods={"GET", "POST"})
     */
    public function new(Request $request, CommandlineRepository $commandlineRepository): Response
    {
        $commandline = new Commandline();
        $form = $this->createForm(CommandlineType::class, $commandline);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $commandlineRepository->add($commandline);
            return $this->redirectToRoute('home', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('commandline/new.html.twig', [
            'commandline' => $commandline,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/{id}", name="app_commandline_show", methods={"GET"})
     */
    public function show(Commandline $commandline): Response
    {
        return $this->render('commandline/show.html.twig', [
            'commandline' => $commandline,
        ]);
    }


}
