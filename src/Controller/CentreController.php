<?php

namespace App\Controller;

use App\Entity\Centre;
use App\Form\CentreType;
use App\Repository\CentreRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/centre')]
class CentreController extends AbstractController
{
    #[Route('/', name: 'app_centre_index', methods: ['GET'])]
    public function index(CentreRepository $centreRepository): Response
    {
        return $this->render('centre/index.html.twig', [
            'centres' => $centreRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_centre_new', methods: ['GET', 'POST'])]
    public function new(Request $request, CentreRepository $centreRepository): Response
    {
        $centre = new Centre();
        $form = $this->createForm(CentreType::class, $centre);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $centreRepository->save($centre, true);

            return $this->redirectToRoute('app_centre_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('centre/new.html.twig', [
            'centre' => $centre,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_centre_show', methods: ['GET'])]
    public function show(Centre $centre): Response
    {
        return $this->render('centre/show.html.twig', [
            'centre' => $centre,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_centre_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Centre $centre, CentreRepository $centreRepository): Response
    {
        $form = $this->createForm(CentreType::class, $centre);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $centreRepository->save($centre, true);

            return $this->redirectToRoute('app_centre_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('centre/edit.html.twig', [
            'centre' => $centre,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_centre_delete', methods: ['POST'])]
    public function delete(Request $request, Centre $centre, CentreRepository $centreRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$centre->getId(), $request->request->get('_token'))) {
            $centreRepository->remove($centre, true);
        }

        return $this->redirectToRoute('app_centre_index', [], Response::HTTP_SEE_OTHER);
    }
}
