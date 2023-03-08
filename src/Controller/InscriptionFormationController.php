<?php

namespace App\Controller;

use App\Entity\InscriptionFormation;
use App\Form\InscriptionFormationType;
use App\Repository\InscriptionFormationRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/inscription/formation')]
class InscriptionFormationController extends AbstractController
{
    #[Route('/', name: 'app_inscription_formation_index', methods: ['GET'])]
    public function index(InscriptionFormationRepository $inscriptionFormationRepository): Response
    {
        return $this->render('inscription_formation/index.html.twig', [
            'inscription_formations' => $inscriptionFormationRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_inscription_formation_new', methods: ['GET', 'POST'])]
    public function new(Request $request, InscriptionFormationRepository $inscriptionFormationRepository): Response
    {
        $inscriptionFormation = new InscriptionFormation();
        $form = $this->createForm(InscriptionFormationType::class, $inscriptionFormation);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $inscriptionFormationRepository->save($inscriptionFormation, true);

            return $this->redirectToRoute('app_inscription_formation_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('inscription_formation/new.html.twig', [
            'inscription_formation' => $inscriptionFormation,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_inscription_formation_show', methods: ['GET'])]
    public function show(InscriptionFormation $inscriptionFormation): Response
    {
        return $this->render('inscription_formation/show.html.twig', [
            'inscription_formation' => $inscriptionFormation,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_inscription_formation_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, InscriptionFormation $inscriptionFormation, InscriptionFormationRepository $inscriptionFormationRepository): Response
    {
        $form = $this->createForm(InscriptionFormationType::class, $inscriptionFormation);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $inscriptionFormationRepository->save($inscriptionFormation, true);

            return $this->redirectToRoute('app_inscription_formation_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('inscription_formation/edit.html.twig', [
            'inscription_formation' => $inscriptionFormation,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_inscription_formation_delete', methods: ['POST'])]
    public function delete(Request $request, InscriptionFormation $inscriptionFormation, InscriptionFormationRepository $inscriptionFormationRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$inscriptionFormation->getId(), $request->request->get('_token'))) {
            $inscriptionFormationRepository->remove($inscriptionFormation, true);
        }

        return $this->redirectToRoute('app_inscription_formation_index', [], Response::HTTP_SEE_OTHER);
    }
}
