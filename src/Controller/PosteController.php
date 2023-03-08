<?php

namespace App\Controller;

use App\Entity\Poste;
use App\Entity\Commentaire;
use App\Form\PosteType;
use App\Form\CommentaireType;
use App\Repository\PosteRepository;
use App\Repository\CategorieposteRepository;
use App\Repository\CommentaireRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\String\Slugger\SluggerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;

#[Route('/poste')]
class PosteController extends AbstractController
{
    #[Route('/front', name: 'app_poste_front_index', methods: ['GET'])]
    public function index(PosteRepository $posteRepository,CategorieposteRepository $categorieposteRepository): Response
    {
        
        return $this->render('poste/index.html.twig', [
            'postes' => $posteRepository->findAll(),
            'categoriepostes' => $categorieposteRepository->findAll(),
        ]);
    }
    #[Route('/backend', name: 'app_poste_index', methods: ['GET']), IsGranted('ROLE_ADMIN')]
    public function indexback(PosteRepository $posteRepository,CategorieposteRepository $categorieposteRepository,CommentaireRepository $commentaireRepository): Response
    {
        $user = $this->getUser();

        if (!$user) {
            return $this->redirectToRoute('app_login');
        }
        return $this->render('poste/backend.html.twig', [
            'postes' => $posteRepository->findAll(),
            'categoriepostes' => $categorieposteRepository->findAll(),
            'commentaires' => $commentaireRepository->findAll(),
        ]);
    }

    #[Route('/new/back', name: 'app_postebackend_new', methods: ['GET', 'POST']),IsGranted('ROLE_ADMIN')]
    public function newback(Request $request, PosteRepository $posteRepository, SluggerInterface $slugger): Response
    {
        $user = $this->getUser();
        if (!$user) {
            return $this->redirectToRoute('app_login');
        }
        $poste = new Poste();
        $form = $this->createForm(PosteType::class, $poste);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $pictureFile = $form->get('multimedia')->getData();
            if ($pictureFile) {
                $originalFilename = pathinfo($pictureFile->getClientOriginalName(), PATHINFO_FILENAME);
                $safeFilename = $slugger->slug($originalFilename);
                $newFilename = $safeFilename.'-'.uniqid().'.'.$pictureFile->guessExtension();

                try {
                    $pictureFile->move(
                        $this->getParameter('users_directory'),
                        $newFilename
                    );
                } catch (FileException $e) {
                    // handle exception
                }

                $poste->setMultimedia($newFilename);
            }
            $posteRepository->save($poste, true);

            return $this->redirectToRoute('app_poste_index', [], Response::HTTP_SEE_OTHER);
        }
        

        return $this->renderForm('poste/newback.html.twig', [
            'poste' => $poste,
            'form' => $form,
        ]);
    }
    #[Route('/new/fornt', name: 'app_postefront_new', methods: ['GET', 'POST'])]
    public function newfront(Request $request, PosteRepository $posteRepository, SluggerInterface $slugger): Response
    {
        $poste = new Poste();
        $form = $this->createForm(PosteType::class, $poste);
        $form->handleRequest($request);
        $user = $this->getUser();
        if (!$user) {
            return $this->redirectToRoute('app_login');
        }
        if ($form->isSubmitted() && $form->isValid()) {
            $pictureFile = $form->get('multimedia')->getData();
            if ($pictureFile) {
                $originalFilename = pathinfo($pictureFile->getClientOriginalName(), PATHINFO_FILENAME);
                $safeFilename = $slugger->slug($originalFilename);
                $newFilename = $safeFilename.'-'.uniqid().'.'.$pictureFile->guessExtension();

                try {
                    $pictureFile->move(
                        $this->getParameter('users_directory'),
                        $newFilename
                    );
                } catch (FileException $e) {
                    // handle exception
                }

                $poste->setMultimedia($newFilename);
            }
            $posteRepository->save($poste, true);

            return $this->redirectToRoute('app_poste_front_index', [], Response::HTTP_SEE_OTHER);
        }
        

        return $this->renderForm('poste/new.html.twig', [
            'poste' => $poste,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_poste_show', methods: ['GET']),IsGranted('ROLE_ADMIN')]
    public function show(Poste $poste): Response
    {
        $user = $this->getUser();
        if (!$user) {
            return $this->redirectToRoute('app_login');
        }
        $commentaires = $poste->getCommentaire();
        return $this->render('poste/show.html.twig', [
            'poste' => $poste,
            'commentaires' => $commentaires,
        ]);
    }

    #[Route('/postecommentaire/{id}', name: 'app_postecommentaire_show', methods: ['POST','GET'])]
    public function show1(Poste $poste,Request $request,CategorieposteRepository $categorieposteRepository,CommentaireRepository $commentaireRepository): Response
    {

        $commentaires = $poste->getCommentaire();
        $commentaire = new Commentaire();
        $form = $this->createForm(CommentaireType::class, $commentaire, [
            'data' => $commentaire
        ]);
                $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $commentaire->setPoste($poste);
            $commentaireRepository->save($commentaire, true);
            

            return $this->redirectToRoute('app_poste_front_index', [], Response::HTTP_SEE_OTHER);
        }

    return $this->renderform('commentaire/show.html.twig', [
        'form' => $form,
        'poste' => $poste,
        'commentaires' => $commentaires,
        'categoriepostes' => $categorieposteRepository->findAll(),


    ]);
    }
    #[Route('/{id}/frontedit', name: 'app_frontposte_edit', methods: ['GET', 'POST'])]
    public function editfront(Request $request, Poste $poste, PosteRepository $posteRepository,SluggerInterface $slugger): Response
    {
        $form = $this->createForm(PosteType::class, $poste);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $pictureFile = $form->get('multimedia')->getData();
            if ($pictureFile) {
                $originalFilename = pathinfo($pictureFile->getClientOriginalName(), PATHINFO_FILENAME);
                $safeFilename = $slugger->slug($originalFilename);
                $newFilename = $safeFilename.'-'.uniqid().'.'.$pictureFile->guessExtension();

                try {
                    $pictureFile->move(
                        $this->getParameter('users_directory'),
                        $newFilename
                    );
                } catch (FileException $e) {
                    // handle exception
                }

                $poste->setMultimedia($newFilename);
            }
            $posteRepository->save($poste, true);
        

            return $this->redirectToRoute('app_poste_front_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('poste/edit.html.twig', [
            'poste' => $poste,
            'form' => $form,
        ]);
    }
    #[Route('/{id}/backendedit', name: 'app_poste_edit', methods: ['GET', 'POST']),IsGranted('ROLE_ADMIN')]
    public function edit(Request $request, Poste $poste, PosteRepository $posteRepository,SluggerInterface $slugger): Response
    {
        $form = $this->createForm(PosteType::class, $poste);
        $form->handleRequest($request);
        $user = $this->getUser();
        if (!$user) {
            return $this->redirectToRoute('app_login');
        }
        if ($form->isSubmitted() && $form->isValid()) {
            $pictureFile = $form->get('multimedia')->getData();
            if ($pictureFile) {
                $originalFilename = pathinfo($pictureFile->getClientOriginalName(), PATHINFO_FILENAME);
                $safeFilename = $slugger->slug($originalFilename);
                $newFilename = $safeFilename.'-'.uniqid().'.'.$pictureFile->guessExtension();

                try {
                    $pictureFile->move(
                        $this->getParameter('users_directory'),
                        $newFilename
                    );
                } catch (FileException $e) {
                    // handle exception
                }

                $poste->setMultimedia($newFilename);
            }
            $posteRepository->save($poste, true);
        

            return $this->redirectToRoute('app_poste_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('poste/editback.html.twig', [
            'poste' => $poste,
            'form' => $form,
        ]);
    }
    #[Route('front/{id}', name: 'app_postefront_delete', methods: ['POST'])]
    public function deletefront(Request $request, Poste $poste, PosteRepository $posteRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$poste->getId(), $request->request->get('_token'))) {
            $posteRepository->remove($poste, true);
        }

        return $this->redirectToRoute('app_poste_front_index', [], Response::HTTP_SEE_OTHER);
    }

    #[Route('back/{id}', name: 'app_poste_delete', methods: ['POST']),IsGranted('ROLE_ADMIN')]
    public function deleteback(Request $request, Poste $poste, PosteRepository $posteRepository): Response
    {
        $user = $this->getUser();
        if (!$user) {
            return $this->redirectToRoute('app_login');
        }
        if ($this->isCsrfTokenValid('delete'.$poste->getId(), $request->request->get('_token'))) {
            $posteRepository->remove($poste, true);
        }

        return $this->redirectToRoute('app_poste_index', [], Response::HTTP_SEE_OTHER);
    }
}
