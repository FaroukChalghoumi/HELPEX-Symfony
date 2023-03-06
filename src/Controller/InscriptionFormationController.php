<?php

namespace App\Controller;

use App\Entity\Formation;
use App\Entity\InscriptionFormation;
use App\Form\InscriptionFormationType;
use App\Repository\InscriptionFormationRepository;
use App\Service\PdfService;
use Endroid\QrCode\Builder\BuilderInterface;
use Endroid\QrCode\Color\Color;
use Endroid\QrCode\Encoding\Encoding;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Notifier\Message\SmsMessage;
use Symfony\Component\Notifier\TexterInterface;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/inscription/formation')]
class InscriptionFormationController extends AbstractController
{
    #[Route('/front', name: 'app_inscription_formation_index', methods: ['GET'])]
    public function indexfront(InscriptionFormationRepository $inscriptionFormationRepository): Response
    {
        $user = $this->getUser();

        return $this->render('inscription_formation/index_front.html.twig', [
            'inscription_formations' => $inscriptionFormationRepository->findAll(),
            'user'=>$user,
        ]);
    }
    #[Route('/', name: 'app_inscription_formation_index_front', methods: ['GET'])]
    public function index(InscriptionFormationRepository $inscriptionFormationRepository): Response
    {

        return $this->render('inscription_formation/index.html.twig', [
            'inscription_formations' => $inscriptionFormationRepository->findAll(),
        ]);
    }
    #[Route('/pdf/{id}', name: 'pdf_inscription', methods: ['GET', 'POST'])]
public function genererpdfinscription(InscriptionFormation $inscriptionFormation, PdfService $pdf){
        $html=$this->render('inscription_formation/show.html.twig',['inscription_formation'=>$inscriptionFormation]);
        $pdf->showPdfFile($html);
    }
    #[Route('/new/{id}', name: 'app_inscription_formation_new', methods: ['GET', 'POST'])]
    public function new(/*TexterInterface $texter*/ BuilderInterface $qrBuilder ,MailerInterface $mailer,Formation $id,Request $request, InscriptionFormationRepository $inscriptionFormationRepository): Response
    {
    $user = $this->getUser();


//        $sms = new SmsMessage(
//        // the phone number to send the SMS message to
//            '+15674092939',
//            // the message
//            'A new login was detected!',
//            // optionally, you can override default "from" defined in transports
//            '+1422222222',
//        );
        //$sentMessage = $texter->send($sms);
        $inscriptionFormation = new InscriptionFormation();
        $form = $this->createForm(InscriptionFormationType::class, $inscriptionFormation);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            //$id->getIdCentre()->getEmailCentre();
            $email = (new TemplatedEmail());

            $email->subject('nouvelle inscription a votre formation');
            $email->from('oussema.ayari.2001@gmail.com');
            $email->to('ahmedbelhajhassen22@gmail.com');
            $email->text($user->getUsername(),$user->getUserIdentifier());
            $mailer->send($email);
            $inscriptionFormation->setFormations($id);
            $inscriptionFormation->setUser($user);
            $inscriptionFormation->setStatusFormation("a faire");
            $inscriptionFormation->setNote(0);



            $inscriptionFormationRepository->save($inscriptionFormation, true);

            return $this->redirectToRoute('app_inscription_formation_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('inscription_formation/new.html.twig', [
            'inscription_formation' => $inscriptionFormation,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_inscription_formation_show', methods: ['GET'])]
    public function show(InscriptionFormation $inscriptionFormation): Response
    {

        return $this->render('inscription_formation/show.html.twig', [
            'inscription_formation' => $inscriptionFormation,

        ]);
    }
    #[Route('/{id}/front', name: 'app_inscription_formation_show_front', methods: ['GET'])]
    public function showfront(BuilderInterface $qrBuilder ,InscriptionFormation $inscriptionFormation): Response
    {
        $path = dirname(__DIR__, 2).'/public/uploads/';

        $data ='nom de la formation: '.$inscriptionFormation->getFormations()->getNomFormation()."\n\n".'description:'.$inscriptionFormation->getFormations()->getDescriptionFormation()."\n"."\n". 'duree: '.$inscriptionFormation->getFormations()->getDuree();
        $qrResult = $qrBuilder
            ->size(300)
            ->margin(10)
            ->encoding(new Encoding('UTF-8'))
            ->data($data)
            ->logoPath($path.'logo.png')
            ->logoResizeToWidth('100')
            ->logoResizeToHeight('100')
            ->backgroundColor(new Color(223, 242, 255))
            ->build();

        $base64Data = $qrResult->getDataUri();
        return $this->render('inscription_formation/show_front.html.twig', [
            'inscription_formation' => $inscriptionFormation,
            'qrCode'=>$base64Data,

        ]);
    }

    #[Route('/{id}/edit', name: 'app_inscription_formation_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, InscriptionFormation $inscriptionFormation, InscriptionFormationRepository $inscriptionFormationRepository): Response
    {
        $form = $this->createForm(InscriptionFormationType::class, $inscriptionFormation);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $inscriptionFormationRepository->save($inscriptionFormation, true);

            return $this->redirectToRoute('app_inscription_formation_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('inscription_formation/edit.html.twig', [
            'inscription_formation' => $inscriptionFormation,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_inscription_formation_delete', methods: ['POST'])]
    public function delete(Request $request, InscriptionFormation $inscriptionFormation, InscriptionFormationRepository $inscriptionFormationRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$inscriptionFormation->getId(), $request->request->get('_token'))) {
            $inscriptionFormationRepository->remove($inscriptionFormation, true);
        }

        return $this->redirectToRoute('app_inscription_formation_index', [], Response::HTTP_SEE_OTHER);
    }

}
