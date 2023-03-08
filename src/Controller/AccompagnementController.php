<?php

namespace App\Controller;

use App\Entity\Accompagnement;
use App\Form\Accompagnement1Type;
use App\Repository\AccompagnementRepository;
use App\Repository\TasksRepository;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;
use Symfony\Component\Mime\Email;


#[Route('/accompagnement')]
class AccompagnementController extends AbstractController
{
    #[Route('/', name: 'app_accompagnement_index', methods: ['GET'])]
    public function index(AccompagnementRepository $accompagnementRepository): Response
    {$user = $this->getUser();

        if (!$user) {
            return $this->redirectToRoute('app_login');
        }
        return $this->render('accompagnement/admin/index.html.twig', [
            'accompagnements' => $accompagnementRepository->findAll(),
        ]);
    }
    #[Route('/MesAccompagnements', name: 'mesaccompagnemnts', methods: ['GET'])]
    public function mesaccompagnemnts(AccompagnementRepository $accompagnementRepository): Response
    {$user = $this->getUser();
            $role="unKnown";
        if (!$user) {
            return $this->redirectToRoute('app_login');
        }


        $roles=array_map('trim',$user->getRoles()); //trimmed array values
        if($roles[0]=="ROLE_USER"){
            $accompagnementList=$accompagnementRepository->findByAccompagnementEmail($user->getUserIdentifier());
            $role="user";

        }
        if($roles[0]=="ROLE_PRO"){
            $accompagnementList=$accompagnementRepository->findByAccompagnementEmailUserPro($user->getUserIdentifier());
            $role="pro";

        }

        return $this->render('accompagnement/index.html.twig', [
            'accompagnements' => $accompagnementList,'role'=>$role
        ]);
    }









    ///////////////////////////////Json : return all accompagnements //////////////////////////////////
    #[Route('/json', name: 'jsonAll', methods: ['GET'])]
    public function accompagnements(AccompagnementRepository $accompagnementRepository,NormalizerInterface $normalizable): Response
    {


        $accompagnements=$accompagnementRepository->findAll();
        $jsonContent=$normalizable->normalize($accompagnements,'json',['groups'=>'post:read']);
        return  new Response(json_encode($jsonContent));
    }
    #[Route('/mytaskstogive', name: 'app_accompagnement_user1', methods: ['GET'])]
    public function get_tasks(AccompagnementRepository $accompagnementRepository,Security $security): Response
    {
        $user = $this->getUser();
        $user_id = $security->getUser()->getUserIdentifier();


        if (!$user) {
            return $this->redirectToRoute('app_login');
        }
        $tasks = [];

        $accompagnement_list = $accompagnementRepository->findByAccompagnementEmail($user_id);

        foreach ($accompagnement_list as $accompagnement) {
            if ($accompagnement->getUserPro() == null) {
                array_push($tasks, $accompagnement->getTask());
            }
        }
        return $this->render('user/front/ProfileProuser.html.twig', [
            'tasks' => $tasks,
        ]);
    }


    #[Route('/new', name: 'app_accompagnement_new', methods: ['GET', 'POST'])]
    public function new(Request $request, AccompagnementRepository $accompagnementRepository): Response
    {$user = $this->getUser();

        if (!$user) {
            return $this->redirectToRoute('app_login');
        }
        $accompagnement = new Accompagnement();
        $form = $this->createForm(Accompagnement1Type::class, $accompagnement);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $accompagnementRepository->save($accompagnement, true);

            return $this->redirectToRoute('app_accompagnement_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('accompagnement/new.html.twig', [
            'accompagnement' => $accompagnement,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_accompagnement_show', methods: ['GET'])]
    public function show(Accompagnement $accompagnement): Response
    {$user = $this->getUser();

        if (!$user) {
            return $this->redirectToRoute('app_login');
        }
        return $this->render('accompagnement/show.html.twig', [
            'accompagnement' => $accompagnement,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_accompagnement_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Accompagnement $accompagnement, AccompagnementRepository $accompagnementRepository): Response
    {$user = $this->getUser();

        if (!$user) {
            return $this->redirectToRoute('app_login');
        }
        $form = $this->createForm(Accompagnement1Type::class, $accompagnement);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $accompagnementRepository->save($accompagnement, true);

            return $this->redirectToRoute('app_accompagnement_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('accompagnement/edit_admin.html.twig', [
            'accompagnement' => $accompagnement,
            'form' => $form,
        ]);
    }


    #[Route('/accepter/{id}', name: 'app_accompagnement_accepter')]
    public function accepter(Request $request, AccompagnementRepository $accompagnementRepository,$id): Response{
        $user = $this->getUser();
        if (!$user) {
            return $this->redirectToRoute('app_login');
        }
        $accompagnement=$accompagnementRepository->find($id);
        $accompagnement->setIsAccepted(true);
        $accompagnementRepository->save($accompagnement, true);
        return $this->redirectToRoute('mesaccompagnemnts', [], Response::HTTP_SEE_OTHER);

    }
    #[Route('/remove/{id}', name: 'app_accompagnement_rejeterr')]
    public function rejeter(Request $request, Accompagnement $accompagnement, AccompagnementRepository $accompagnementRepository): Response
    {$user = $this->getUser();

        if (!$user) {
            return $this->redirectToRoute('app_login');
        }
            $accompagnementRepository->remove($accompagnement, true);


        return $this->redirectToRoute('mesaccompagnemnts');
    }








    #[Route('/{id}', name: 'app_accompagnement_delete', methods: ['POST'])]
    public function delete(Request $request, Accompagnement $accompagnement, AccompagnementRepository $accompagnementRepository): Response
    {$user = $this->getUser();

        if (!$user) {
            return $this->redirectToRoute('app_login');
        }
        if ($this->isCsrfTokenValid('delete'.$accompagnement->getId(), $request->request->get('_token'))) {
            $accompagnementRepository->remove($accompagnement, true);
        }

        return $this->redirectToRoute('mesaccompagnemnts', [], Response::HTTP_SEE_OTHER);
    }









    #[Route('/send_request/{id}/{t}', name: 'app_accompagnemet_send_request', methods: ['GET','POST'])]
    public function add_acoompagnement( $id,$t, Request $request, UserRepository $userRepository,TasksRepository $tasksRepository, AccompagnementRepository $accompagnementRepository): Response
    {$user = $this->getUser();

        if (!$user) {
            return $this->redirectToRoute('app_login');
        }
        //CREATE AN accompagnement et apres update user and add the id key inside the user
        $accompagnement = new Accompagnement();
        $user_prof=$userRepository->find($id);
        $user=$userRepository->find($id);
        if (!$user_prof) {
            throw $this->createNotFoundException('Unable to find Preisliste entity user profes.');
        }



        $accompagnement->setUser($user);
        $accompagnement->setUserPro($user_prof);
        $accompagnement->setTask($tasksRepository->find($t));
        $accompagnement->setIsAccepted(false);
        $accompagnementRepository->save($accompagnement, true);

        // hétha té5thou min session badlouu
     /*   $user = $userRepository->find(888);
        if (!$user) {
            throw $this->createNotFoundException('Unable to find Preisliste entity user 3adi.');
        }*/

        //$user->setAccompagnement($accompagnement);

        //$userRepository->save($user, true);
        dd($user);
        dd($accompagnement);
        //return new Response("some content");

    }




        // ...
    //linna delete accomp
        // agnement


    //select il accompagnement of user 3addiyin

}

