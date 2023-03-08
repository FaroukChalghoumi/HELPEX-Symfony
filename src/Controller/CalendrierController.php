<?php

namespace App\Controller;

use App\Repository\AccompagnementRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CalendrierController extends AbstractController
{


    ///////////////////////////////////////calendrier for pros
    #[Route('/calendrier', name: 'app_calendrier')]
    public function showCalendrier( AccompagnementRepository $accompagnementRepository){
        $user = $this->getUser();

        if (!$user) {
            return $this->redirectToRoute('app_login');
        }

        $roles=array_map('trim',$user->getRoles()); //trimmed array values
        if($roles[0]=="ROLE_PRO") {

            $accompagnements = $accompagnementRepository->findByAccompagnementEmailUserProandStatus($user->getUserIdentifier());
        }
        if($roles[0]=="ROLE_USER"){
            $accompagnements = $accompagnementRepository->findByAccompagnementEmailUserandStatus($user->getUserIdentifier());


        }


        $tasks=[];
        foreach ($accompagnements as $accompagnement){
            array_push($tasks, $accompagnement->getTask());
        }
        $events=[];

        foreach($tasks as $task){
            if ($task->isIsValid()==0){
                $valid="task validé";

            }else{
                $valid="task non validé";
            }

            $events[] = [
                'id' => $task->getId(),
                'start' => $task->getStartDate()->format('Y-m-d H:i:s'),
                'end' => $task->getEndDate()->format('Y-m-d H:i:s'),
                'title' => $task->getTitre(),
                'backgroundColor' =>"#36d9c8",
                'description' => $valid,

            ];
        }

        $data = json_encode($events);
        if($roles[0]=="ROLE_USER"){
            return $this->render('calendar/calendrier.html.twig', compact('data'));
        }
        else{
            return $this->render('calendar/calendrier_pro.html.twig', compact('data'));
        }


    }
}
