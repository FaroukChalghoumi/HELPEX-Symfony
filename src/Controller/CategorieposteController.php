<?php

namespace App\Controller;

use App\Entity\Poste;
use App\Entity\Categorieposte;
use App\Form\CategorieposteType;
use App\Repository\PosteRepository;
use App\Repository\CategorieposteRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/categorieposte')]
class CategorieposteController extends AbstractController
{
    #[Route('/', name: 'app_categorieposte_index', methods: ['GET'])]
    public function index(CategorieposteRepository $categorieposteRepository): Response
    {
        return $this->render('categorieposte/index.html.twig', [
            'categoriepostes' => $categorieposteRepository->findAll(),
        ]);
    }
    #[Route('/poste', name: 'app_categorieposte_index1', methods: ['GET'])]
    public function index1(CategorieposteRepository $categorieposteRepository): Response
    {
        return $this->render('poste/index.html.twig', [
            'categoriepostes' => $categorieposteRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_categorieposte_new', methods: ['GET', 'POST'])]
    public function new(Request $request, CategorieposteRepository $categorieposteRepository): Response
    {
        $categorieposte = new Categorieposte();
        $form = $this->createForm(CategorieposteType::class, $categorieposte);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $categorieposteRepository->save($categorieposte, true);

            return $this->redirectToRoute('app_categorieposte_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('categorieposte/new.html.twig', [
            'categorieposte' => $categorieposte,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_categorieposte_show', methods: ['GET'])]
    public function show(Categorieposte $categorieposte): Response
    {
        return $this->render('categorieposte/show.html.twig', [
            'categorieposte' => $categorieposte,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_categorieposte_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Categorieposte $categorieposte, CategorieposteRepository $categorieposteRepository): Response
    {
        $form = $this->createForm(CategorieposteType::class, $categorieposte);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $categorieposteRepository->save($categorieposte, true);

            return $this->redirectToRoute('app_categorieposte_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('categorieposte/edit.html.twig', [
            'categorieposte' => $categorieposte,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_categorieposte_delete', methods: ['POST'])]
    public function delete(Request $request, Categorieposte $categorieposte, CategorieposteRepository $categorieposteRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$categorieposte->getId(), $request->request->get('_token'))) {
            $categorieposteRepository->remove($categorieposte, true);
        }

        return $this->redirectToRoute('app_categorieposte_index', [], Response::HTTP_SEE_OTHER);
    }
}
