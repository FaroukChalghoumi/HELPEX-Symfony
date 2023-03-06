<?php

namespace App\Controller;

use App\Repository\AccompagnementRepository;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mercure\HubInterface;
use Symfony\Component\Mercure\Update;
use Symfony\Component\Mime\Email;
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

    #[Route('/email')]
    public function sendEmail(MailerInterface $mailer): Response
    {
        $email = (new Email())
            ->from('eya.filali@esprit.tn')
            ->to('filalieya@gmail.com')
            //->cc('cc@example.com')
            //->bcc('bcc@example.com')
            //->replyTo('fabien@example.com')
            //->priority(Email::PRIORITY_HIGH)
            ->subject('Time for Symfony Mailer!')
            ->text('Sending emails is fun again!')
            ->html('<p>See Twig integration for better HTML integration!</p>');

       $mailer->send($email);
        return $this->render('test/index.html.twig', [
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
    #[Route('/pub', name: 'pub')]
    public function publish(HubInterface $hub): Response
    {
        $update = new Update(
            'https://example.com/.well-known/mercure',
            json_encode(['status' => 'OutOfStock'])
        );

        $hub->publish($update);
 $response = new Response();
        $response->headers->set('Access-Control-Allow-Origin', '*');
        $response->setContent("published");
        return $response ;
    }

}
