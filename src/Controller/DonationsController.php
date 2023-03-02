<?php

namespace App\Controller;

use App\Repository\CaisseOrganisationRepository;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
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

    #[Route('/payment', name: 'app_donations_payment')]
    public function Payment(CaisseOrganisationRepository $caisseOrganisationRepository,MailerInterface $mailer): Response
    {
        $email = (new TemplatedEmail());

        $email->subject('Demo message using the Symfony Mailer library.');
        $email->from('oussema.ayari.2001@gmail.com');
        $email->to('oussama.ayari1@esprit.tn');
        $email->text('This is an important message!');
        $mailer->send($email);

        return $this->render('donations/index.html.twig', [
            'Caisses' => $caisseOrganisationRepository->findAll(),
        ]);
    }
}
