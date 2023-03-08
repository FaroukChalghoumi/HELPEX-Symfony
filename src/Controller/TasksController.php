<?php

namespace App\Controller;

use App\Entity\Accompagnement;
use App\Entity\Tasks;
use App\Entity\User;
use App\Form\TasksType;
use App\Repository\AccompagnementRepository;
use App\Repository\TasksRepository;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Exception\ORMException;
use Doctrine\ORM\OptimisticLockException;
use Exception;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;

#[Route('/tasks')]
class TasksController extends AbstractController
{

    #[Route('/', name: 'app_item_index', methods: ['GET'])]
    public function index(TasksRepository $tasksRepository): Response
    {
        $user = $this->getUser();

        if (!$user) {
            return $this->redirectToRoute('app_login');
        }
        return $this->render('tasks/index_admin.html.twig', [
            'tasks' => $tasksRepository->findAll(),
        ]);
    }

    #[Route('/json', name: 'jsonAllTasks', methods: ['GET'])]
    public function indexj(TasksRepository $tasksRepository,NormalizerInterface $normalizable): Response
    {
        $tasksRepository=$tasksRepository->findAll();


        $jsonContent=$normalizable->normalize($tasksRepository,'json',['groups'=>'tasks']);
        return  new Response(json_encode($jsonContent));
    }
    #[Route('/user_admin', name: 'app_tasks_index_ad', methods: ['GET', 'POST'])]
    public function index_admin(TasksRepository $tasksRepository, Request $request): Response
    {
        $user = $this->getUser();

        if (!$user) {
            return $this->redirectToRoute('app_login');
        }

        $color = sprintf('#%06X', mt_rand(0, 0xFFFFFF));
        $task = new Tasks();
        $form = $this->createForm(TasksType::class, $task);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $tasksRepository->save($task, true);

            return $this->redirectToRoute('app_item_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('tasks/index.html.twig', [
            'tasks' => $tasksRepository->findAll(), 'randomcolor' => $color, 'task' => $task,
            'form' => $form->createView(),
        ]);
    }




    //////////////////Tasks of every user Pro / user normal //////////////////////////////
    #[Route('/user_norm/{id_userP}', name: 'app_tasks_index_nor', methods: ['GET', 'POST'])]
    public function TaskUserPro(UserRepository $userRepository,TasksRepository $tasksRepository, Request $request, AccompagnementRepository $accompagnementRepository,  $id_userP): Response
    {
        $user = $this->getUser();

        if (!$user) {
            return $this->redirectToRoute('app_login');
        }

        $roles=array_map('trim',$user->getRoles()); //trimmed array values
        if($roles[0]=="ROLE_USER") {

            $user_id=[];
            $users= $userRepository->findByEmail($user->getUserIdentifier());
            foreach ($users as $id){
                $user_id=$id->getId();
            }
            $tasks = [];
            $valid=0;
            $non_valid=0;
            $accompagnement_list = $accompagnementRepository->findBy(["user" => ["user_id" => $user_id], "user_pro" => ["user_pro_id" => $id_userP]]);
            foreach ($accompagnement_list as $accompagnement) {
                array_push($tasks, $accompagnement->getTask());

                if($accompagnement->getTask()->isIsValid()){
                    $valid=+1;
                }else{
                    $non_valid=+1;
                }


            }
            $color = sprintf('#%06X', mt_rand(0, 0xFFFFFF));
            $task = new Tasks();
            $form = $this->createForm(TasksType::class, $task);
            $form->handleRequest($request);

            if ($form->isSubmitted() && $form->isValid()) {
                $tasksRepository->save($task, true);

                return $this->redirectToRoute('app_tasks_index_nor', ["valid"=>$valid,"n_valid"=>$non_valid,"user_pro"=>$id_userP], Response::HTTP_SEE_OTHER);
            }

            return $this->render('tasks/index.html.twig', [
                'tasks' => $tasks, 'randomcolor' => $color, 'task' => $task,
                'form' => $form->createView(),"role"=>"user","valid"=>$valid,"n_valid"=>$non_valid,"user_pro"=>$id_userP
            ]);
        }
        if($roles[0]=="ROLE_PRO"){

            $tasks = [];

            $accompagnement_list =$accompagnementRepository->findByAccompagnementEmailUserPro($user->getUserIdentifier());

            foreach ($accompagnement_list as $accompagnement) {
                array_push($tasks, $accompagnement->getTask());

            }
            $color = sprintf('#%06X', mt_rand(0, 0xFFFFFF));
            $task = new Tasks();
            $form = $this->createForm(TasksType::class, $task);
            $form->handleRequest($request);

            if ($form->isSubmitted() && $form->isValid()) {
                $tasksRepository->save($task, true);

                return $this->redirectToRoute('app_tasks_index_nor', [], Response::HTTP_SEE_OTHER);
            }

            return $this->render('tasks/index.html.twig', [
                'tasks' => $tasks, 'randomcolor' => $color, 'task' => $task,
                'form' => $form->createView(),   "role"=>"pro"
            ]);




        }
       return dd("admin");

    }


//////////////////////////////////////les user pro de user normal /////////////////////////////
    #[Route('/myProUsers/', name: 'app_tasks_my_pro_user', methods: ['GET'])]
    public function My_pro_users(TasksRepository $tasksRepository, Request $request, AccompagnementRepository $accompagnementRepository): Response
    {
        $user = $this->getUser();

        if (!$user) {
            return $this->redirectToRoute('app_login');
        }
        $roles=array_map('trim',$user->getRoles()); //trimmed array values







        if($roles[0]=="ROLE_USER"){
            $accompagnement_list2 = $accompagnementRepository->findByAccompagnementEmail($user->getUserIdentifier());
            $accompagnement_list=[];
            $femmels=0;
            $males=0;
            foreach ($accompagnement_list2 as $accompagnement){
                if($accompagnement->isIsAccepted()!=0){
                    array_push($accompagnement_list, $accompagnement);
                }
            }
            $users = [];
            $categColor=[];
            foreach ($accompagnement_list as $user) {

                if (!in_array($user->getUserPro(), $users)) {
                    array_push($users, $user->getUserPro());
                    array_push( $categColor, '#' . str_pad(dechex(mt_rand(0, 0xFFFFFF)), 6, '0', STR_PAD_LEFT));

                    if($user->getUserPro()->getSexe()=="femme"){
                        $femmels=+1;
                    }
                    else{$males=+1 ;}

                }


            }
            return $this->render('profil_user/index.html.twig', [
                'users' => $users ,"liste"=>"des utilisateurs professionels","femme"=>$femmels,"male"=>$males,"categColor"=>$categColor]);

        }
        //////////////////////////// pour les users//////////////////////Again
        if($roles[0]=="ROLE_PRO"){
            {
                $accompagnement_list2 = $accompagnementRepository->findByAccompagnementEmailUserPro($user->getUserIdentifier());
                $users = [];
                $accompagnement_list=[];
                foreach ($accompagnement_list2 as $accompagnement){
                    if($accompagnement->isIsAccepted()!=0){
                        array_push($accompagnement_list, $accompagnement);
                    }
                }
                foreach ($accompagnement_list as $user) {

                    if (!in_array($user->getUser(), $users)) {
                        array_push($users, $user->getUser());
                    }


                }
                return $this->render('profil_user/index.html.twig', [
                    'users' => $users ,"liste"=>"des utilisateurs dont le quel vous étes associés"]);


            }
        }
dd("admin");
        }


    #[Route('/user_pro', name: 'app_tasks_index_pro', methods: ['GET', 'POST'])]
    public function indexPro(TasksRepository $tasksRepository, Request $request): Response
    {

        $user = $this->getUser();

        if (!$user) {
            return $this->redirectToRoute('app_login');
        }
        $color = sprintf('#%06X', mt_rand(0, 0xFFFFFF));
        $task = new Tasks();
        $form = $this->createForm(TasksType::class, $task);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $tasksRepository->save($task, true);

            return $this->redirectToRoute('app_item_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('tasks/index.html.twig', [
            'tasks' => $tasksRepository->findAll(), 'randomcolor' => $color, 'task' => $task,
            'form' => $form->createView(),
        ]);
    }
    #[Route('/new', name: 'app_tasks_new_vide', methods: ['GET', 'POST'])]
    public function newvide(EntityManagerInterface  $entityManager,Request $request, TasksRepository $tasksRepository,AccompagnementRepository $accompagnementRepository,UserRepository $userRepository): Response
    {       //$this->$entityManager = $entityManager;

        $user = $this->getUser();

        if (!$user) {
            return $this->redirectToRoute('app_login');
        }
        $roles=array_map('trim',$user->getRoles()); //trimmed array values
        if($roles[0]=="ROLE_USER"){
            $task = new Tasks();
            $form = $this->createForm(TasksType::class, $task);
            $form->handleRequest($request);

            if ($form->isSubmitted() && $form->isValid()) {
                $tasksRepository->save($task, true);
                $accompagnement = new Accompagnement();
                $current_user=$userRepository->findByEmail($user->getUserIdentifier());
                $accompagnement->setUser($current_user[0]);
                $accompagnement->setIsAccepted(false);
                //$accompagnementRepository->save($accompagnement,true);
                $accompagnement->setTask($task);


                $entityManager->beginTransaction(); // start a transaction

                $entityManager->persist($task);
                $entityManager->flush();
                $entityManager->persist($accompagnement);
                $entityManager->flush(); // save both entities

                $entityManager->commit();




                return $this->redirect('http://127.0.0.1:8000/tasks/myProUsers/');
            }

            return $this->renderForm('tasks/new.html.twig', [
                'task' => $task,
                'form' => $form,
            ]);}
        else{
            return $this->redirectToRoute('app_login');
        }
    }


    /**
     * @throws OptimisticLockException
     * @throws ORMException
     */
    #[Route('/new/{idP}', name: 'app_tasks_new', methods: ['GET', 'POST'])]
    public function new($idP,EntityManagerInterface  $entityManager,Request $request, TasksRepository $tasksRepository,AccompagnementRepository $accompagnementRepository,UserRepository $userRepository): Response
    {       //$this->$entityManager = $entityManager;

        $user = $this->getUser();

        if (!$user) {
            return $this->redirectToRoute('app_login');
        }
        $roles=array_map('trim',$user->getRoles()); //trimmed array values
        if($roles[0]=="ROLE_USER"){
        $task = new Tasks();
        $form = $this->createForm(TasksType::class, $task);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $tasksRepository->save($task, true);
            $accompagnement = new Accompagnement();
            $current_user=$userRepository->findByEmail($user->getUserIdentifier());
            $accompagnement->setUser($current_user[0]);
            $accompagnement->setIsAccepted(true);
            $accompagnement->setUserPro($userRepository->find($idP));
            //$accompagnementRepository->save($accompagnement,true);
            $accompagnement->setTask($task);


            $entityManager->beginTransaction(); // start a transaction

                $entityManager->persist($task);
            $entityManager->flush();
                $entityManager->persist($accompagnement);
            $entityManager->flush(); // save both entities

                $entityManager->commit();

            $user=$userRepository->findByEmail1($this->getUser()->getUserIdentifier());


            //$user=reset($id_user);
            //dd($user['id']);
            return $this->redirect('http://127.0.0.1:8000/tasks/myProUsers/');


        }

        return $this->renderForm('tasks/new.html.twig', [
            'task' => $task,
            'form' => $form,
        ]);}
        else{
            return $this->redirectToRoute('app_login');
        }
    }


    //new task vide

    #[Route('/{id}', name: 'app_tasks_show', methods: ['GET'])]
    public function show(Tasks $task): Response
    {
        $user = $this->getUser();

        if (!$user) {
            return $this->redirectToRoute('app_login');
        }
        return $this->render('tasks/show.html.twig', [
            'task' => $task,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_tasks_edit', methods: ['GET', 'POST'])]
    public function edit(UserRepository $userRepository ,AccompagnementRepository $accompagnementRepository,Request $request, Tasks $task, TasksRepository $tasksRepository): Response
    {
        $user = $this->getUser();

        if (!$user) {
            return $this->redirectToRoute('app_login');
        }
        $form = $this->createForm(TasksType::class, $task);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $tasksRepository->save($task, true);
            //raja3ha kima kénit next time
            //return $this->redirectToRoute('app_tasks_index', [], Response::HTTP_SEE_OTHER);
            $user=$userRepository->findByEmail1($this->getUser()->getUserIdentifier());


            //$user=reset($id_user);
            //dd($user['id']);
            http://127.0.0.1:8000/tasks/myProUsers/
            //return $this->redirect($request->getRequestUri());

            // return $this->redirect('http://127.0.0.1:8000/tasks/user_norm/'.$user->getId());
            return $this->redirect('http://127.0.0.1:8000/tasks/myProUsers/');
           // return $this->redirect('http://127.0.0.1:8000/tasks/user_norm/5/5');

        }


        return $this->render('tasks/edit.html.twig', [
            'task' => $task,
            'form' => $form->createView(),
        ]);
    }

    #[Route('/{id}', name: 'app_tasks_delete', methods: ['POST'])]
    public function delete(AccompagnementRepository $accompagnementRepository,UserRepository $userRepository,Request $request, Tasks $task, TasksRepository $tasksRepository): Response
    {
        $user = $this->getUser();

        if (!$user) {
            return $this->redirectToRoute('app_login');
        }

        if ($this->isCsrfTokenValid('delete' . $task->getId(), $request->request->get('_token'))) {
            try {
                $tasksRepository->remove($task, true);

            } catch (Exception $exception) {
                $this->addFlash('warning', 'attention! task non vide !');
                //return $this->redirectToRoute('app_tasks_index');
               // return $this->redirect('http://127.0.0.1:8000/tasks/user_norm/5/6');

            }
        }
        $user=$userRepository->findByEmail1($this->getUser()->getUserIdentifier());


        //$user=reset($id_user);
        //dd($user['id']);
        http://127.0.0.1:8000/tasks/myProUsers/
        //return $this->redirect($request->getRequestUri());

       // return $this->redirect('http://127.0.0.1:8000/tasks/user_norm/'.$user->getId());
        return $this->redirect("http://127.0.0.1:8000/tasks/myProUsers/");
        //return $this->redirectToRoute('app_item_index', [], Response::HTTP_SEE_OTHER);
    }






    #[Route('/{id}', name: 'app_tasks_deleteAdmin', methods: ['POST'])]
    public function deleteadmin(Request $request, Tasks $task, TasksRepository $tasksRepository): Response
    {
        $user = $this->getUser();

        if (!$user) {
            return $this->redirectToRoute('app_login');
        }

        if ($this->isCsrfTokenValid('delete' . $task->getId(), $request->request->get('_token'))) {
            try {
                $tasksRepository->remove($task, true);

            } catch (Exception $exception) {
                $this->addFlash('warning', 'attention! task non vide !');
                return $this->redirectToRoute('app_item_index', [], Response::HTTP_SEE_OTHER);
                // return $this->redirect('http://127.0.0.1:8000/tasks/user_norm/5/6');

            }
        }

        return $this->redirectToRoute('app_item_index', [], Response::HTTP_SEE_OTHER);
    }


//admin

    #[Route('admin/{id}/edit', name: 'app_tasks_edit_admin', methods: ['GET', 'POST']), IsGranted('ROLE_ADMIN')]
    public function editAdmin(Request $request, Tasks $task, TasksRepository $tasksRepository): Response
    {
        $user = $this->getUser();

        if (!$user) {
            return $this->redirectToRoute('app_login');
        }
        $form = $this->createForm(TasksType::class, $task);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $tasksRepository->save($task, true);
            //raja3ha kima kénit next time
            return $this->redirectToRoute('app_item_index', [], Response::HTTP_SEE_OTHER);
            //return $this->redirect('http://127.0.0.1:8000/tasks/user_norm/5/5');

        }
        return $this->render('tasks/admin/edit_admin.html.twig', [
            'task' => $task,
            'form' => $form->createView(),
        ]);
    }


}
