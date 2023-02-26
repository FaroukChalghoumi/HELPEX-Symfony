<?php

namespace App\Controller;

use App\Entity\Filiere;
use App\Form\FiliereType;
use App\Repository\FiliereRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;


#[Route('admin/filiere')]
class FiliereController extends AbstractController
{
    #[Route('/', name: 'app_filiere_index', methods: ['GET']), IsGranted('ROLE_ADMIN')]
    public function index(FiliereRepository $filiereRepository): Response
    {
        $user = $this->getUser();

        if (!$user) {
            return $this->redirectToRoute('app_login');
        }

        return $this->render('filiere/index.html.twig', [
            'filieres' => $filiereRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_filiere_new', methods: ['GET', 'POST']), IsGranted('ROLE_ADMIN')]
    public function new(Request $request, FiliereRepository $filiereRepository): Response
    {
        $user = $this->getUser();

        if (!$user) {
            return $this->redirectToRoute('app_login');
        }
        $filiere = new Filiere();
        $form = $this->createForm(FiliereType::class, $filiere);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $filiereRepository->save($filiere, true);

            return $this->redirectToRoute('app_filiere_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('filiere/new.html.twig', [
            'filiere' => $filiere,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_filiere_show', methods: ['GET']), IsGranted('ROLE_ADMIN')]
    public function show(Filiere $filiere): Response
    {
        $user = $this->getUser();

        if (!$user) {
            return $this->redirectToRoute('app_login');
        }
        $entityManager = $this->getDoctrine()->getManager();
        $filiere = $entityManager->getRepository(Filiere::class)->find($filiere);
        $users = $filiere->getUsers();

        return $this->render('filiere/show.html.twig', [
            'filiere' => $filiere,
            'users' => $users,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_filiere_edit', methods: ['GET', 'POST']), IsGranted('ROLE_ADMIN')]
    public function edit(Request $request, Filiere $filiere, FiliereRepository $filiereRepository): Response
    {
        $user = $this->getUser();

        if (!$user) {
            return $this->redirectToRoute('app_login');
        }
        $form = $this->createForm(FiliereType::class, $filiere);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $filiereRepository->save($filiere, true);

            return $this->redirectToRoute('app_filiere_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('filiere/edit.html.twig', [
            'filiere' => $filiere,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_filiere_delete', methods: ['POST']), IsGranted('ROLE_ADMIN')]
    public function delete(Request $request, Filiere $filiere, FiliereRepository $filiereRepository): Response
    {
        $user = $this->getUser();

        if (!$user) {
            return $this->redirectToRoute('app_login');
        }
        if ($this->isCsrfTokenValid('delete'.$filiere->getId(), $request->request->get('_token'))) {
            $filiereRepository->remove($filiere, true);
        }

        return $this->redirectToRoute('app_filiere_index', [], Response::HTTP_SEE_OTHER);
    }



    public function showUsersInFiliere($filiereId)
    {
       
    
        if (!$filiere) {
            throw $this->createNotFoundException('No filiere found for id ' . $filiereId);
        }
    
        
    
        return $this->render('filiere/show.html.twig', [
            'filiere' => $filiere,
           
        ]);
    }


}
