<?php

namespace App\Controller;

use App\Entity\CategorieProduit;
use App\Entity\Produits;
use App\Form\CategorieProduitType;
use App\Repository\CategorieProduitRepository;
use App\Repository\ProduitsRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;


#[Route('/categorie/produit')]
class CategorieProduitController extends AbstractController
{
    #[Route('/', name: 'app_categorie_produit_index', methods: ['GET']) , IsGranted('ROLE_ADMIN') ]
    public function index(CategorieProduitRepository $categorieProduitRepository): Response
    {
        $user = $this->getUser();

        if (!$user) {
            return $this->redirectToRoute('app_login');
        }

        return $this->render('categorie_produit/adminCat.html.twig', [
            'categorie_produits' => $categorieProduitRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_categorie_produit_new', methods: ['GET', 'POST']) , IsGranted('ROLE_ADMIN') ]
    public function new(Request $request, CategorieProduitRepository $categorieProduitRepository): Response
    {
        $user = $this->getUser();

        if (!$user) {
            return $this->redirectToRoute('app_login');
        }
        $categorieProduit = new CategorieProduit();
        $form = $this->createForm(CategorieProduitType::class, $categorieProduit);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $categorieProduitRepository->save($categorieProduit, true);

            return $this->redirectToRoute('app_categorie_produit_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('categorie_produit/new.html.twig', [
            'categorie_produit' => $categorieProduit,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_categorie_produit_show', methods: ['GET']) , IsGranted('ROLE_ADMIN') ]
    public function show(CategorieProduit $categorieProduit , ProduitsRepository $produitsRepository): Response
    {
        $user = $this->getUser();

        if (!$user) {
            return $this->redirectToRoute('app_login');
        }
        return $this->render('categorie_produit/show.html.twig', [
            'categorie_produit' => $categorieProduit,
            'produits' => $produitsRepository->findAll(),
        ]);
    }

    #[Route('/{id}', name: 'app_produits_delete_admin', methods: ['POST']) , IsGranted('ROLE_ADMIN') ]
    public function deletePAdmin(Request $request, Produits $produit, ProduitsRepository $produitsRepository): Response
    {
        $user = $this->getUser();

        if (!$user) {
            return $this->redirectToRoute('app_login');
        }
        if ($this->isCsrfTokenValid('delete'.$produit->getId(), $request->request->get('_token'))) {
            $produitsRepository->remove($produit, true);
        }

        return $this->redirectToRoute('app_categorie_produit_index', [], Response::HTTP_SEE_OTHER);
    }

    #[Route('/{id}/edit', name: 'app_categorie_produit_edit', methods: ['GET', 'POST']) , IsGranted('ROLE_ADMIN') ]
    public function edit(Request $request, CategorieProduit $categorieProduit, CategorieProduitRepository $categorieProduitRepository): Response
    {
        $user = $this->getUser();

        if (!$user) {
            return $this->redirectToRoute('app_login');
        }
        $form = $this->createForm(CategorieProduitType::class, $categorieProduit);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $categorieProduitRepository->save($categorieProduit, true);

            return $this->redirectToRoute('app_categorie_produit_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('categorie_produit/edit.html.twig', [
            'categorie_produit' => $categorieProduit,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_categorie_produit_delete', methods: ['POST']) , IsGranted('ROLE_ADMIN') ]
    public function delete(Request $request, CategorieProduit $categorieProduit, CategorieProduitRepository $categorieProduitRepository): Response
    {
        $user = $this->getUser();

        if (!$user) {
            return $this->redirectToRoute('app_login');
        }
        if ($this->isCsrfTokenValid('delete'.$categorieProduit->getId(), $request->request->get('_token'))) {
            $categorieProduitRepository->remove($categorieProduit, true);
        }

        return $this->redirectToRoute('app_categorie_produit_index', [], Response::HTTP_SEE_OTHER);
    }
}
