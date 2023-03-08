<?php

namespace App\Controller;
use App\Service\BadWordFilter;
use App\Entity\Poste;
use App\Form\PosteType;
use App\Entity\Commentaire;
use App\Entity\Postelikes;
use App\Form\CommentaireType;
use App\Repository\PosteRepository;
use Doctrine\Persistence\ObjectManager;
use App\Repository\PostelikesRepository;
use App\Repository\CommentaireRepository;
use Knp\Component\Pager\PaginatorInterface;
use App\Repository\CategorieposteRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\String\Slugger\SluggerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use App\Service\Api;

#[Route('/poste')]
class PosteController extends AbstractController
{
    #[Route('/samirytir', name: 'app_api_front')]
    public function index3(Api $callApiService): Response
    {
        dd($callApiService->getdata());


        return $this->render('front.html.twig', [
            'data' => $callApiService->getdata(),
        ]);
    }

    #[Route("/deletepostejson/{id}", name: "deletepostejson")]
    public function deletecentrejson(Request $req, $id, NormalizerInterface $Normalizer)
    {

        $em = $this->getDoctrine()->getManager();
        $poste = $em->getRepository(Poste::class)->find($id);
        $em->remove($poste);
        $em->flush();
        $jsonContent = $Normalizer->normalize($poste, 'json', ['groups' => "post:read"]);
        return new Response("poste deleted successfully " . json_encode($jsonContent));
    }

    #[Route("/postejson/{id}", name: "centreid", methods: ['GET'])]
    public function CentreId($id, NormalizerInterface $normalizer, PosteRepository $repo)
    {
        $poste = $repo->find($id);
        $posteNormalises = $normalizer->normalize($poste, 'json', ['groups' => "post:read"]);
        return new Response(json_encode($posteNormalises));
    }

    #[Route("/updatepostejson/{id}", name: "updatepostejson")]
    public function updateCentreJSON(Request $request, $id, NormalizerInterface $Normalizer)
    {

        $em = $this->getDoctrine()->getManager();
        $poste = $em->getRepository(Poste::class)->find($id);
        $poste->setTitre($request->get('titre'));
        $poste->setDescription($request->get('description'));

        $em->flush();

        $jsonContent = $Normalizer->normalize($poste, 'json', ['groups' => "post:read"]);
        return new Response("centre updated successfully " . json_encode($jsonContent));
    }


    //*************************************************
    /////////////////////////////////////////////////////////////////////////////////////////
    #[Route('/ajouterjson',name:'mobileajouterposte',methods: ['GET','POST'])]
    
    public function addpostejson(Request $request,NormalizerInterface $Normalizer)
    {
        $em=$this->getDoctrine()->getManager();
        $poste=new Poste();
        $poste->setDescription($request->get('description'));
        $poste->setTitre($request->get('titre'));
        $em->persist($poste);
        $em->flush();
        $jsonContent=$Normalizer->normalize($poste,'json',['groups'=>'post:read']);
        return new Response(json_encode($jsonContent));


    }
    #[Route('/afficherjson', name: 'app_mobile_index', methods: ['GET'])]
    public function postemobile(NormalizerInterface $Normalizer,PosteRepository $posteRepository): Response
    {
        $poste = $posteRepository->findAll();
        $jsoncontent = $Normalizer->normalize($poste,'json',['groups'=>'post:read']);
         return new Response(json_encode($jsoncontent));

    }
    #[Route('/front/bycategory/{id}', name: 'app_poste_bycategory')]
    public function getByCategorie($id,Request $request,PosteRepository $posteRepository,PaginatorInterface $paginator,CategorieposteRepository $categorieposteRepository) : Response {

        $postes = $posteRepository->getBycategory($id);
        $postes = $paginator->paginate(
            $postes, /* query NOT result */
            $request->query->getInt('page', 1),
            6
        );
        return $this->renderForm('poste\index.html.twig', [
            'postes' => $postes,
            'categoriepostes' => $categorieposteRepository->findAll(),
        ]);
    }
    #[Route('/front', name: 'app_poste_front_index', methods: ['GET'])]
    public function index(Request $request,PosteRepository $posteRepository,PaginatorInterface $paginator,CategorieposteRepository $categorieposteRepository,Api $samir): Response
    {

        $data = $samir->getdata();

        $postes=$posteRepository->findAll();
        $postes = $paginator->paginate(
            $postes, /* query NOT result */
            $request->query->getInt('page', 1),
            6
        );
        return $this->render('poste/index.html.twig', [
            'dataArray'=> $data,
            'postes' => $postes,
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
            $poste->setUser($user);
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
    public function newfront(Request $request, PosteRepository $posteRepository, SluggerInterface $slugger,BadWordFilter $badWordFilter): Response
    {
        $poste = new Poste();
        $form = $this->createForm(PosteType::class, $poste);
        $form->handleRequest($request);
        $user = $this->getUser();
        if (!$user) {
            return $this->redirectToRoute('app_login');
        }
        if ($form->isSubmitted() && $form->isValid()) {
            $poste->setUser($user);
            $poste->setDescription($badWordFilter->filter($poste->getDescription()));
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
            $this->addFlash('notice', 'Poste added Successufully!!');
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
        $user= $poste->getUser();
        return $this->render('poste/show.html.twig', [
            'poste' => $poste,
            'commentaires' => $commentaires,
        ]);
    }

    #[Route('/postecommentaire/{id}', name: 'app_postecommentaire_show', methods: ['POST','GET'])]
    public function show1(Poste $poste,Request $request,CategorieposteRepository $categorieposteRepository,CommentaireRepository $commentaireRepository, BadWordFilter $badWordFilter): Response
    {
        $user = $this->getUser();
        $commentaires = $poste->getCommentaire();
        $commentaire = new Commentaire();
        $user1= $poste->getUser();
        $form = $this->createForm(CommentaireType::class, $commentaire, [
            'data' => $commentaire
        ]);
                $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $commentaire->setPoste($poste);
            $commentaire->setUser($user);
            $commentaire->setDescription($badWordFilter->filter($commentaire->getDescription()));
            $commentaireRepository->save($commentaire, true);
          


            return $this->renderform('commentaire/show.html.twig', [
                'user'=> $user1,
                'form' => $form,
                'poste' => $poste,
                'commentaires' => $commentaires,
                'categoriepostes' => $categorieposteRepository->findAll(),
        
        
            ]);
        }

    return $this->renderform('commentaire/show.html.twig', [
        'user'=> $user1,
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
    #[Route('/{id}/like', name: 'app_poste_like')]
    public function like(Poste $poste,PostelikesRepository $likerepo): Response
    {
        $user = $this->getUser();
        if($poste->isliked($user))
        {
            $like = $likerepo ->findoneby(['poste'=>$poste,'user'=>$user]);
            $likerepo->remove($like, true);
            return $this->redirectToRoute('app_poste_front_index', [], Response::HTTP_SEE_OTHER);

        }
        $like = new Postelikes();
        $like->setPoste($poste)->setUser($user);
        $likerepo->save($like, true);

        return $this->redirectToRoute('app_poste_front_index', [], Response::HTTP_SEE_OTHER);
    }
    
   
}
