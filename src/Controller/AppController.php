<?php

namespace App\Controller;

use App\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AppController extends AbstractController
{
   #[Route('/home', name: 'home')]
    public function index(): Response
    {
        $user = $this->getUser();
        if (!$user) {
            return $this->redirectToRoute('app_login');
        }
        return $this->render('app/userhome.html.twig', [
            'user' => $this->getUser(),
        ]);
    } 

    #[ Route('/admin', name: 'admin'),
     IsGranted("ROLE_ADMIN") ]
    public function indexAdmin(): Response
    {
        
        return $this->render('app/admin.html.twig', [
            'user' => $this->getUser(),
        ]);
    } 

}
