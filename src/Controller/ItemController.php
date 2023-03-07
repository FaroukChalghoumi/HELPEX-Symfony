<?php

namespace App\Controller;

use App\Entity\Item;
use App\Entity\Tasks;
use App\Form\ItemType;
use App\Repository\ItemRepository;
use App\Repository\TasksRepository;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\String\Slugger\SluggerInterface;

#[Route('/item')]
class ItemController extends AbstractController
{
    #[Route('/', name: 'app_item_index', methods: ['GET'])]
    public function index(ItemRepository $itemRepository): Response
    {
        return $this->render('item/index.html.twig', [
            'items' => $itemRepository->findAll(),
        ]);
    }


    #[Route('/task/{id}', name: 'app_task_items', methods: ['GET'])]
    public function find_items(ItemRepository $itemRepository ,TasksRepository $tasksRepository,$id): Response
    {$user = $this->getUser();

        if (!$user) {
            return $this->redirectToRoute('app_login');
        }

        $items=$itemRepository->findBy(['tasks'=>$id]);
        $task =$tasksRepository->findOneBy(['id'=>$id]);
        //dd($items);
        return $this->render('item/index.html.twig', [
            'items' =>$items ,'task'=>$task
        ]);




    }

    #[Route('admin/task/{id}', name: 'app_task_items_admin', methods: ['GET']), IsGranted('ROLE_ADMIN')]
    public function find_items_admin(ItemRepository $itemRepository ,TasksRepository $tasksRepository,$id): Response
    {       $user = $this->getUser();

        if (!$user) {
            return $this->redirectToRoute('app_login');
        }
        $items = $itemRepository->findBy(['tasks' => $id]);
        $task = $tasksRepository->findOneBy(['id' => $id]);
        //dd($items);
        return $this->render('item/index_admin.html.twig', [
            'items' => $items, 'task' => $task
        ]);
    }


    #[Route('/new', name: 'app_item_new', methods: ['GET', 'POST'])]
    public function new(Request $request, ItemRepository $itemRepository,SluggerInterface $slugger,TasksRepository $tasksRepository): Response
    {$user = $this->getUser();

        if (!$user) {
            return $this->redirectToRoute('app_login');
        }
        $item = new Item();
        $form = $this->createForm(ItemType::class, $item);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $task=$form->get('tasks')->getData();
            $id_task=$task->getId();

            $pictureFile = $form->get('photo')->getData();
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
                $item->setPhoto($newFilename);
            }
            $itemRepository->save($item, true);

            return $this->redirect('http://127.0.0.1:8000/item/task/'.$id_task);
        }

        return $this->renderForm('item/new.html.twig', [
            'item' => $item,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_item_show', methods: ['GET'])]
    public function show(Item $item): Response
    {$user = $this->getUser();

        if (!$user) {
            return $this->redirectToRoute('app_login');
        }
        return $this->render('item/show.html.twig', [
            'item' => $item,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_item_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Item $item, ItemRepository $itemRepository,SluggerInterface $slugger,$id): Response
    {$user = $this->getUser();

        if (!$user) {
            return $this->redirectToRoute('app_login');
        }
        $form = $this->createForm(ItemType::class, $item);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $task_id =$form->get('tasks')->getData()->getId();

            $pictureFile = $form->get('photo')->getData();
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
                $item->setPhoto($newFilename);
            }


            $itemRepository->save($item, true);

            return $this->redirect('http://127.0.0.1:8000/item/task/'.$task_id);
        }

        return $this->renderForm('item/edit_admin.html.twig', [
            'item' => $item,
            'form' => $form,

        ]);
    }

    #[Route('/{id}', name: 'app_item_delete', methods: ['POST','GET'])]
    public function delete(Request $request, Item $item, ItemRepository $itemRepository): Response
    {       $user = $this->getUser();

        if (!$user) {
            return $this->redirectToRoute('app_login');
        }
        if ($this->isCsrfTokenValid('delete'.$item->getId(), $request->request->get('_token'))) {
            $itemRepository->remove($item, true);
        }
        $task_id=$item->getTasks()->getId();
        return $this->redirect('http://127.0.0.1:8000/item/task/'.$task_id);
    }
}
