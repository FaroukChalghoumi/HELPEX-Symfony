<?php

namespace App\Controller;

use App\Entity\Organisation;
use App\Repository\CaisseOrganisationRepository;
use App\Repository\OrganisationRepository;
use Stripe\Checkout\Session;
use Stripe\Stripe;
use Stripe\StripeClient;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mailer\MailerInterface;

use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

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
    public function Payment(Request $request,MailerInterface $mailer): Response
    {
        Stripe::setApiKey('sk_test_51MhCUgHv0arDT0U0P19vmMrNfUVhnrgr7oLZC6LOOXnbTEcciLDUPqrehv7UVbWDnCggUNFZegmbAuyK6wzwtEDI00F2fvASZc');
        $amount = $request->query->getInt('amount');
        $checkout_session = Session::create([
            'line_items' => [[
                'price_data' => [
                    'currency' => 'eur',
                    'product_data' => [
                        'name' => 'Donation',
                    ],
                    'unit_amount' => $amount,
                ],
                'quantity' => 1,
            ]],
            'mode' => 'payment',
            'success_url' => $this->generateUrl('success_url', [], UrlGeneratorInterface::ABSOLUTE_URL),
            'cancel_url' => $this->generateUrl('success_url', [], UrlGeneratorInterface::ABSOLUTE_URL),
        ]);


        $email = (new TemplatedEmail());

        $email->subject('Demo message using the Symfony Mailer library.');
        $email->from('oussema.ayari.2001@gmail.com');
        $email->to('oussema.ayari2020@gmail.com');
        $email->htmlTemplate('emails/template.html.twig');
        $email->context([
        'name' => 'oussema',
    ]);
        $mailer->send($email);



        return $this->redirect($checkout_session->url,303);
    }


    #[Route('/success-url', name: 'success_url')]
    public function successUrl(): Response
    {
        return $this->render('donations/success.html.twig', []);
    }


    #[Route('/cancel-url', name: 'cancel_url')]
    public function cancelUrl(): Response
    {
        return $this->render('donations/cancel.html.twig', []);
    }

    #[Route('/ListOrgs', name: 'app_donations_organisations')]
    public function ListAllOrgs(OrganisationRepository $organisationRepository): Response
    {
        return $this->render('donations/organisations.html.twig', [
            'organisations' => $organisationRepository->findAll(),
        ]);
    }
    #[Route('/ViewOrg/{id}', name: 'app_donations_ViewOrg')]
    public function ViewOrg(Organisation $organisation, CaisseOrganisationRepository $caisseOrganisationRepository): Response
    {
        return $this->render('donations/showOrganisation.html.twig', [
            'organisation' => $organisation,
            'Caisses' => $caisseOrganisationRepository->findBy([
                'organisation' => $organisation
            ])
        ]);
    }

}
