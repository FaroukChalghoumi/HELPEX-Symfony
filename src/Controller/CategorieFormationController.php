<?php

namespace App\Controller;

use App\Entity\CategorieFormation;
use App\Form\CategorieFormationType;
use App\Repository\CategorieFormationRepository;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/categorie/formation')]
class CategorieFormationController extends AbstractController
{
    #[Route('/', name: 'app_categorie_formation_index', methods: ['GET']), IsGranted('ROLE_ADMIN')]
    public function index(CategorieFormationRepository $categorieFormationRepository): Response
    {
        $user = $this->getUser();

        if (!$user) {
            return $this->redirectToRoute('app_login');
        }
        return $this->render('categorie_formation/index.html.twig', [
            'categorie_formations' => $categorieFormationRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_categorie_formation_new', methods: ['GET', 'POST']), IsGranted('ROLE_ADMIN')]
    public function new(Request $request, CategorieFormationRepository $categorieFormationRepository): Response
    {
        $user = $this->getUser();

        if (!$user) {
            return $this->redirectToRoute('app_login');
        }
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

    #[Route('/{id}', name: 'app_categorie_formation_show', methods: ['GET']), IsGranted('ROLE_ADMIN')]
    public function show(CategorieFormation $categorieFormation): Response
    {
        $user = $this->getUser();

        if (!$user) {
            return $this->redirectToRoute('app_login');
        }
        return $this->render('categorie_formation/show.html.twig', [
            'categorie_formation' => $categorieFormation,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_categorie_formation_edit', methods: ['GET', 'POST']), IsGranted('ROLE_ADMIN')]
    public function edit(Request $request, CategorieFormation $categorieFormation, CategorieFormationRepository $categorieFormationRepository): Response
    {
        $user = $this->getUser();

        if (!$user) {
            return $this->redirectToRoute('app_login');
        }
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

    #[Route('/{id}', name: 'app_categorie_formation_delete', methods: ['POST']), IsGranted('ROLE_ADMIN')]
    public function delete(Request $request, CategorieFormation $categorieFormation, CategorieFormationRepository $categorieFormationRepository): Response
    {
        $user = $this->getUser();

        if (!$user) {
            return $this->redirectToRoute('app_login');
        }
        if ($this->isCsrfTokenValid('delete'.$categorieFormation->getId(), $request->request->get('_token'))) {
            $categorieFormationRepository->remove($categorieFormation, true);
        }

        return $this->redirectToRoute('app_categorie_formation_index', [], Response::HTTP_SEE_OTHER);
    }
}
