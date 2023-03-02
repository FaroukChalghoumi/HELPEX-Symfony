<?php

namespace App\Controller;

use App\Repository\AccompagnementRepository;
use Symfony\Component\Security\Core\Security;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;


class TestController extends AbstractController
{
    public function index( Request $request,Security $security): Response
    {
        return $this->render('calendar/calendrier.html.twig', [
            'controller_name' => 'TestController',
        ]);
    }
    #[Route('/test', name: 'app_tjkjjjjjjjjasks_calendrier1')]
    public function showCalendrier( AccompagnementRepository $accompagnementRepository){
        $user = $this->getUser();

        if (!$user) {
            return $this->redirectToRoute('app_login');
        }
        $task=$accompagnementRepository->findByAccompagnementEmailUserProandStatus($user->getUserIdentifier());
        dd($task);

    }
}
