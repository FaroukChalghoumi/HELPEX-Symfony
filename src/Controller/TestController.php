<?php

namespace App\Controller;

use App\Entity\Item;
use App\Repository\AccompagnementRepository;
use App\Repository\ItemRepository;
use App\Repository\TasksRepository;
use DateTimeImmutable;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mercure\HubInterface;
use Symfony\Component\Mercure\Update;
use Symfony\Component\Mime\Email;
use Symfony\Component\Security\Core\Security;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;


class TestController extends AbstractController
{
    public function index( Request $request,Security $security): Response
    {
        return $this->render('calendar/calendrier.html.twig', [
            'controller_name' => 'TestController',
        ]);
    }





    #[Route('/stat', name: 's')]
    public function statistiques(ItemRepository $itemRepository){
        // On va chercher toutes les catégories
        $items = $itemRepository->findAll();

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

        // On "démonte" les données pour les séparer tel qu'attendu par ChartJS
        foreach($items as $categorie){
            array_push( $categNom,$categorie->getTitre());
            array_push(  $categCount,8*rand(10,100));


        }



        /* // On va chercher le nombre d'annonces publiées par date
         $annonces = $annRepo->countByDate();

         $dates = [];
         $annoncesCount = [];

         // On "démonte" les données pour les séparer tel qu'attendu par ChartJS
         foreach($annonces as $annonce){
             $dates[] = $annonce['dateAnnonces'];
             $annoncesCount[] = $annonce['count'];
         }*/

        return $this->render('stat_task/statistics.html.twig', [
            'categNom' => json_encode($categNom),
            'categColor' => json_encode($categColor),
            'categCount' => json_encode($categCount),
            "complete"=>json_encode($countcomplet),
            "notcomplete"=>json_encode($countnon_complet)

        ]);
    }


    #[Route('/email8')]
    public function sendEmail(MailerInterface $mailer): Response
    {
        $email = (new TemplatedEmail());

        $email->subject('Demo message using the Symfony Mailer library.');
        $email->from('oussema.ayari.2001@gmail.com');
        $email->to('filalieya@gmail.com');
        //$email->htmlTemplate('emails/template.html.twig');
        $email->context([
            'name' => 'oussema',
        ]);
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
