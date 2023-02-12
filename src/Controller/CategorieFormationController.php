<?php

namespace App\Controller;

use App\Entity\CategorieFormation;
use App\Form\CategorieFormationType;
use App\Repository\CategorieFormationRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/categorie/formation')]
class CategorieFormationController extends AbstractController
{
    #[Route('/', name: 'app_categorie_formation_index', methods: ['GET'])]
    public function index(CategorieFormationRepository $categorieFormationRepository): Response
    {
        return $this->render('categorie_formation/index.html.twig', [
            'categorie_formations' => $categorieFormationRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_categorie_formation_new', methods: ['GET', 'POST'])]
    public function new(Request $request, CategorieFormationRepository $categorieFormationRepository): Response
    {
        $categorieFormation = new CategorieFormation();
        $form = $this->createForm(CategorieFormationType::class, $categorieFormation);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $categorieFormationRepository->save($categorieFormation, true);

            return $this->redirectToRoute('app_categorie_formation_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('categorie_formation/new.html.twig', [
            'categorie_formation' => $categorieFormation,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_categorie_formation_show', methods: ['GET'])]
    public function show(CategorieFormation $categorieFormation): Response
    {
        return $this->render('categorie_formation/show.html.twig', [
            'categorie_formation' => $categorieFormation,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_categorie_formation_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, CategorieFormation $categorieFormation, CategorieFormationRepository $categorieFormationRepository): Response
    {
        $form = $this->createForm(CategorieFormationType::class, $categorieFormation);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $categorieFormationRepository->save($categorieFormation, true);

            return $this->redirectToRoute('app_categorie_formation_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('categorie_formation/edit.html.twig', [
            'categorie_formation' => $categorieFormation,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_categorie_formation_delete', methods: ['POST'])]
    public function delete(Request $request, CategorieFormation $categorieFormation, CategorieFormationRepository $categorieFormationRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$categorieFormation->getId(), $request->request->get('_token'))) {
            $categorieFormationRepository->remove($categorieFormation, true);
        }

        return $this->redirectToRoute('app_categorie_formation_index', [], Response::HTTP_SEE_OTHER);
    }
}
