<?php

namespace App\Controller;

use App\Entity\Item;
use App\Entity\Tasks;
use App\Form\ItemType;
use App\Repository\ItemRepository;
use App\Repository\TasksRepository;
use DateTimeImmutable;
use Dompdf\Dompdf;
use Knp\Component\Pager\PaginatorInterface;
use Lcobucci\JWT\Exception;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;
use Symfony\Component\String\Slugger\SluggerInterface;
use Dompdf\Options;

#[Route('/item')]
class ItemController extends AbstractController
{
    #[Route('/', name: 'app_item_index', methods: ['GET'])]
    public function index(ItemRepository $itemRepository,DomPdf $domPdf): Response
    {
        $domPdf = new DomPdf();

        $pdfOptions = new Options();

        $pdfOptions->set('defaultFont', 'Garamond');

        $domPdf->setOptions($pdfOptions);

        return $this->render('item/index.html.twig', [
            'items' => $itemRepository->findAll(),
        ]);




    }
    #[Route('/json/ajout', name: 'addItemJSON')]
    public function ajouterItem(Request $request,TasksRepository $tasksRepository)

    {
        $item=new Item();
        $titre = $request->query->get("titre");
        $is_complete = $request->query->get("is_complete");
        $time = $request->query->get("time");
        $task_id = $request->query->get("taskid");

        $em = $this->getDoctrine()->getManager();
        $item->setTitre($titre);

        $newformat = DateTimeImmutable::createFromFormat('H:i:s', $time);
        $item->setTime($newformat);
        $item->setIsComplete($is_complete)
        ;
        $item->setTasks($tasksRepository->find($task_id));


        $em->persist($item);
        $em->flush();
        // $serializer = new Serializer([new ObjectNormalizer()]);
        // $formatted = $serializer->normalize($item);
        return new JsonResponse("ok");

    }
    #[Route('/editerJson/{id}', name: 'editerItemJson')]

    public function modifieritemJson(Request $request,ItemRepository $itemRepository) {
        $em = $this->getDoctrine()->getManager();
        $item = $itemRepository
            ->find($request->get("id"));
        $titre = $request->query->get("titre");
        $is_complete = $request->query->get("is_complete");
        $time = $request->query->get("time");

        $item->setTitre($titre);
        $newformat = DateTimeImmutable::createFromFormat('H:i:s', $time);
        $item->setTime($newformat);
        $item->setIsComplete($is_complete);

        $em->persist($item);
        $em->flush();
        $serializer = new Serializer([new ObjectNormalizer()]);
        //$formatted = $serializer->normalize($reclamation);
        return new JsonResponse("ok",Response::HTTP_OK);

    }

    #[Route('/json/deleteitem/{id}', name: 'DeleteitemJSON')]
    public function deleteJsonITEM(Request $request,ItemRepository $itemRepository) {
        $id = $request->get("id");

        $em = $this->getDoctrine()->getManager();
        $item = $itemRepository
            ->find($request->get("id"));

        if($item!=null ) {
            $em->remove($item);
            $em->flush();

            $serialize = new Serializer([new ObjectNormalizer()]);
            // $formatted = $serialize->normalize("Reclamation a ete supprimee avec success.");
            return new JsonResponse("delete ok");

        }
        return new JsonResponse("id item invalide.");


    }


    #[Route('/json/{t}', name: 'jsonAllItem', methods: ['GET'])]
    public function indexj($t,ItemRepository $itemRepository,NormalizerInterface $normalizable): Response
    {
        $items=$itemRepository->findBy(['tasks'=>$t]);

        //$serilizer=new serializer([new ObjectNormalizer()]);
        //$var=$serilizer->normalize($items);
        $jsonContent=$normalizable->normalize($items,'json',['groups'=>'item']);
        return  new Response(json_encode($jsonContent));
    }




    #[Route('/task/{id}', name: 'app_task_items', methods: ['GET'])]
    public function find_items(Request $request,ItemRepository $itemRepository ,TasksRepository $tasksRepository,$id,PaginatorInterface $paginator): Response
    {$user = $this->getUser();

        if (!$user) {
            return $this->redirectToRoute('app_login');
        }

        $items=$itemRepository->findBy(['tasks'=>$id]);
        $task =$tasksRepository->findOneBy(['id'=>$id]);

        $items = $paginator->paginate(
            $items, /* query NOT result */
            $request->query->getInt('page', 1),
            2
        );


        //////////////////stat

        $complete=[] ;
        $non_complet=[];

        $categNom = [];
        $categColor = [];
        $categCount = [];
        foreach ($items as $item){
            array_push( $categColor, '#' . str_pad(dechex(mt_rand(0, 0xFFFFFF)), 6, '0', STR_PAD_LEFT));
            if($item->isIsComplete()==1){
                array_push($non_complet,$item) ;
            }
            else{ array_push($complete,$item) ;}
        }

        $countnon_complet=count($non_complet);
        $countcomplet=count($complete);
        $pourcentage=0;
        if (($countcomplet+$countnon_complet)!=0)
        {            $pourcentage=$countcomplet*100/($countcomplet+$countnon_complet);
         }

        // On "démonte" les données pour les séparer tel qu'attendu par ChartJS
        foreach($items as $categorie){
            array_push( $categNom,$categorie->getTitre());
            array_push(  $categCount,8*rand(10,100));


        }



        return $this->render('item/index.html.twig', [
            'items' =>$items ,'task'=>$task,'categNom' => json_encode($categNom),
            'categColor' => json_encode($categColor),
            'categCount' => json_encode($categCount),
            "complete"=>json_encode($countcomplet),
            "notcomplete"=>json_encode($countnon_complet),"pourcentage"=>$pourcentage
        ]);













    }




    #[Route('/task/download/{id}', name: 'app_item_download', methods: ['GET'])]
    public function down( ItemRepository $itemRepository,$id): Response
    {
        // On définit les options du PDF
        $pdfOptions = new Options();

        // Police par défaut
        $pdfOptions->set('defaultFont', 'Arial');
        $pdfOptions->setIsRemoteEnabled(true);

        // On instancie Dompdf
        $dompdf = new Dompdf($pdfOptions);
        $item=$itemRepository->findAll();




        $context = stream_context_create([
            'ssl' => [
                'verify_peer' => FALSE,
                'verify_peer_name' => FALSE,
                'allow_self_signed' => TRUE
            ]
        ]);
        $dompdf->setHttpContext($context);

        // On génère le html
        $html = $this->renderView('item/download.html.twig', [
            'items' => $item,

        ]);

        $dompdf->loadHtml($html);
        $dompdf->setPaper('A4', 'portrait');
        $dompdf->render();

        // On génère un nom de fichier
        $fichier = 'items'.'.pdf';

        // On envoie le PDF au navigateur
        $dompdf->stream($fichier, [
            'Attachment' => true
        ]);

        return new Response();
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


    #[Route('/new/{idT}', name: 'app_item_new', methods: ['GET', 'POST'])]
    public function new($idT,Request $request, ItemRepository $itemRepository,SluggerInterface $slugger,TasksRepository $tasksRepository): Response
    {$user = $this->getUser();

        if (!$user) {
            return $this->redirectToRoute('app_login');
        }
        $item = new Item();
        $form = $this->createForm(ItemType::class, $item);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $task=$form->get('tasks')->getData();
            //$id_task=$task->getId();

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
            $item->setTasks($tasksRepository->find($idT));
            $itemRepository->save($item, true);

            return $this->redirect('http://127.0.0.1:8000/item/task/'.$idT);
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

            //$item->setTitre("hhhhhhhhhhhhhh9999999");
            $itemRepository->save($item, true);

            return $this->redirect('http://127.0.0.1:8000/item/task/'.$task_id);
        }

        return $this->renderForm('item/edit.html.twig', [
            'item' => $item,
            'form' => $form,

        ]);
    }

    #[Route('/{id}/editadmin', name: 'app_item_editadmin', methods: ['GET', 'POST'])]
    public function editadmin(Request $request, Item $item, ItemRepository $itemRepository,SluggerInterface $slugger,$id): Response
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

            return $this->redirectToRoute('app_item_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('item/admin/edit.html.twig', [
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

    #[Route('admin/{id}', name: 'app_item_delete_admin', methods: ['POST','GET'])]
    public function deleteadmin(Request $request, Item $item, ItemRepository $itemRepository): Response
    {       $user = $this->getUser();

        if (!$user) {
            return $this->redirectToRoute('app_login');
        }
        if ($this->isCsrfTokenValid('delete'.$item->getId(), $request->request->get('_token'))) {
            $itemRepository->remove($item, true);
        }
        $task_id=$item->getTasks()->getId();
        return $this->redirectToRoute('app_item_index', [], Response::HTTP_SEE_OTHER);
    }


}
