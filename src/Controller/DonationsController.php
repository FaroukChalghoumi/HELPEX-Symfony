<?php

namespace App\Controller;

use App\Repository\CaisseOrganisationRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class DonationsController extends AbstractController
{
    #[Route('/donations', name: 'app_donations')]
    public function index(CaisseOrganisationRepository $caisseOrganisationRepository): Response
    {
        return $this->render('donations/index.html.twig', [
            'Caisses' => $caisseOrganisationRepository->findAll(),
        ]);
    }
}
