<?php

namespace App\Controller;

use App\Entity\Tasks;
use App\Form\TasksType;
use App\Repository\AccompagnementRepository;
use App\Repository\TasksRepository;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/tasks')]
class TasksController extends AbstractController
{

    #[Route('/', name: 'app_item_index', methods: ['GET']), IsGranted('ROLE_ADMIN')]
    public function index(TasksRepository $tasksRepository): Response
    {$user = $this->getUser();

        if (!$user) {
            return $this->redirectToRoute('app_login');
        }
        return $this->render('tasks/index_admin.html.twig', [
            'tasks' => $tasksRepository->findAll(),
        ]);
    }




    #[Route('/user_admin', name: 'app_tasks_index_ad', methods: ['GET','POST'])]
    public function index_admin(TasksRepository $tasksRepository,Request $request): Response
    {
        $user = $this->getUser();

        if (!$user) {
            return $this->redirectToRoute('app_login');
        }

        $color= sprintf('#%06X', mt_rand(0, 0xFFFFFF));
        $task = new Tasks();
        $form = $this->createForm(TasksType::class, $task);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $tasksRepository->save($task, true);

            return $this->redirectToRoute('app_tasks_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('tasks/index.html.twig', [
            'tasks' => $tasksRepository->findAll(),'randomcolor'=>$color, 'task' => $task,
            'form' => $form->createView(),
        ]);
    }
    #[Route('/user_norm/{id}/{id_userP}', name: 'app_tasks_index_nor', methods: ['GET','POST'])]
    public function indexnor(TasksRepository $tasksRepository,Request $request,AccompagnementRepository $accompagnementRepository,$id,$id_userP): Response
    {
        $user = $this->getUser();

        if (!$user) {
            return $this->redirectToRoute('app_login');
        }
        //retrieve from the session the user id
      /*  $session=$request->getSession();
        if(!$session->has("id_user"))
        {   $session->set("id_user")}*/
        $tasks=[];
        $accompagnement_list = $accompagnementRepository->findBy(["user"=> ["user_id" => $id],"user_pro"=> ["user_pro_id" =>$id_userP]]);
        foreach ($accompagnement_list as $accompagnement) {
            array_push($tasks, $accompagnement->getTask());

        }
        $color= sprintf('#%06X', mt_rand(0, 0xFFFFFF));
        $task = new Tasks();
        $form = $this->createForm(TasksType::class, $task);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $tasksRepository->save($task, true);

            return $this->redirectToRoute('app_tasks_index_nor', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('tasks/index.html.twig', [
            'tasks' => $tasks,'randomcolor'=>$color, 'task' => $task,
            'form' => $form->createView(),
        ]);
    }



    #[Route('/user_normal/{id}', name: 'app_tasks_my_pro_user', methods: ['GET'])]
    public function Mypro_users(TasksRepository $tasksRepository,Request $request,AccompagnementRepository $accompagnementRepository,$id): Response
    {$user = $this->getUser();

        if (!$user) {
            return $this->redirectToRoute('app_login');
        }
        $accompagnement_list = $accompagnementRepository->findBy(["user"=> ["user_id" => $id]]);
        $users=[];
        foreach ($accompagnement_list as $user) {

            if(!in_array($user->getUserPro(),$users)){
                array_push($users, $user->getUserPro());
            }


        }

        //dd($users);
        return $this->render('profil_user/index.html.twig', [
            'users' =>$users ]);


    }







    #[Route('/user_pro', name: 'app_tasks_index_pro', methods: ['GET','POST'])]
    public function indexPro(TasksRepository $tasksRepository,Request $request): Response
    {

        $user = $this->getUser();

        if (!$user) {
            return $this->redirectToRoute('app_login');
        }
        $color= sprintf('#%06X', mt_rand(0, 0xFFFFFF));
        $task = new Tasks();
        $form = $this->createForm(TasksType::class, $task);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $tasksRepository->save($task, true);

            return $this->redirectToRoute('app_tasks_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('tasks/index.html.twig', [
            'tasks' => $tasksRepository->findAll(),'randomcolor'=>$color, 'task' => $task,
            'form' => $form->createView(),
        ]);
    }






    #[Route('/new', name: 'app_tasks_new', methods: ['GET', 'POST'])]
    public function new(Request $request, TasksRepository $tasksRepository): Response
    {$user = $this->getUser();

        if (!$user) {
            return $this->redirectToRoute('app_login');
        }
        $task = new Tasks();
        $form = $this->createForm(TasksType::class, $task);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $tasksRepository->save($task, true);
            ///linna yilzim té5ith mi session il id béch t7oto fil tasks

            return $this->redirectToRoute('app_accompagnement_new', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('tasks/new.html.twig', [
            'task' => $task,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_tasks_show', methods: ['GET'])]
    public function show(Tasks $task): Response
    {$user = $this->getUser();

        if (!$user) {
            return $this->redirectToRoute('app_login');
        }
        return $this->render('tasks/show.html.twig', [
            'task' => $task,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_tasks_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Tasks $task, TasksRepository $tasksRepository): Response
    {$user = $this->getUser();

        if (!$user) {
            return $this->redirectToRoute('app_login');
        }
        $form = $this->createForm(TasksType::class, $task);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $tasksRepository->save($task, true);
            //raja3ha kima kénit next time
            /*return $this->redirectToRoute('app_tasks_index', [], Response::HTTP_SEE_OTHER);*/
            return $this->redirect('http://127.0.0.1:8000/tasks/user_norm/5/5');

        }





        return $this->render('tasks/edit.html.twig', [
            'task' => $task,
            'form' => $form->createView(),
        ]);
    }

    #[Route('/{id}', name: 'app_tasks_delete', methods: ['POST'])]
    public function delete(Request $request, Tasks $task, TasksRepository $tasksRepository): Response
    {$user = $this->getUser();

        if (!$user) {
            return $this->redirectToRoute('app_login');
        }
        if ($this->isCsrfTokenValid('delete'.$task->getId(), $request->request->get('_token'))) {
            $tasksRepository->remove($task, true);
        }

        return $this->redirectToRoute('app_tasks_index', [], Response::HTTP_SEE_OTHER);
    }


//admin

    #[Route('admin/{id}/edit', name: 'app_tasks_edit_admin', methods: ['GET', 'POST']), IsGranted('ROLE_ADMIN')]
    public function editAdmin(Request $request, Tasks $task, TasksRepository $tasksRepository): Response
    {$user = $this->getUser();

        if (!$user) {
            return $this->redirectToRoute('app_login');
        }
        $form = $this->createForm(TasksType::class, $task);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $tasksRepository->save($task, true);
            //raja3ha kima kénit next time
            /*return $this->redirectToRoute('app_tasks_index', [], Response::HTTP_SEE_OTHER);*/
            return $this->redirect('http://127.0.0.1:8000/tasks/user_norm/5/5');

        }
        return $this->render('tasks/admin/edit_admin.html.twig', [
            'task' => $task,
            'form' => $form->createView(),
        ]);
    }
}
