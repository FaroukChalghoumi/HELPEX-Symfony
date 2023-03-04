<?php

namespace App\Controller;
use App\Entity\User;
use App\Form\EditYourProfileType;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\String\Slugger\SluggerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\UX\Chartjs\Builder\ChartBuilderInterface;
use Symfony\UX\Chartjs\Model\Chart;


class UserController extends AbstractController
{


   






    //////////////////////backbackbackBABY////////////

    #[Route('admin/users', name: 'AllUsers'), IsGranted('ROLE_ADMIN')]
    public function AllUsers(UserRepository $userRepo, ChartBuilderInterface $chartBuilder): Response
    {
        $user = $this->getUser();

        if (!$user) {
            return $this->redirectToRoute('app_login');
        }
        
        $chart = $chartBuilder->createChart(Chart::TYPE_LINE);

        $chart->setData([
            'labels' => ['February', 'March', 'April'],
            'datasets' => [
                [
                    'label' => 'My First dataset',
                    'backgroundColor' => 'rgb(255, 99, 132)',
                    'borderColor' => 'rgb(255, 99, 132)',
                    'data' => [ 20, 5, 45],
                ],
            ],
        ]);

        $chart->setOptions([
            'scales' => [
                'y' => [
                    'suggestedMin' => 0,
                    'suggestedMax' => 100,
                ],
            ],
        ]);

//////////////PIECHART
        $allusers = $userRepo->findAll();
        $role1= 'ROLE_PRO' ;
        $role2= 'ROLE_USER' ;
        $prousers = $userRepo->findPros([$role1]);
        $cliusers =$userRepo->findPros([$role2]);

        $allusersCount = count($allusers);
        $prousersCount = count($prousers);
        $cliusersCount = count($cliusers);

        $Piechart = $chartBuilder->createChart(Chart::TYPE_PIE);
        $Piechart->setData([
            'labels' => ['Professional Users', 'Clients'],
            'datasets' => [
                [
                    'label' => 'User Role',
                    'backgroundColor' => ['#36A2EB', '#FF6384'],
                    'data' => [$prousersCount,$cliusersCount]
                ]
            ]
        ]);


        $Piechart->setOptions([
           
            'width' => 500,
            'height' => 500,
        ]);


        /////////////////DOUGHNUTTT

        $Dchart = $chartBuilder->createChart(Chart::TYPE_DOUGHNUT);
        $Dchart->setData([
            'labels' => ['Infermiers', 'Kinés', 'Aide Soignant'],
            'datasets' => [
                [
                    'label' => 'Utilisateurs Pro selon Filieres',
                    'backgroundColor' => ['#36A2EB', '#FF6384','#009900'],
                    'data' => [76,27,49],
                ]
            ]
        ]);


       


        return $this->render('user/back/allusers.html.twig', [
            'user' => $this->getUser(),
            'usersList' => $userRepo->findAll(),
            'chart' => $chart,
            'PieChart' =>  $Piechart,
            'DChart' =>  $Dchart,

        ]);
    }


    #[Route('admin/users/pros', name: 'backProUsers'), IsGranted('ROLE_ADMIN')]
    public function backProUsers(UserRepository $userRepo): Response
    {
        $user = $this->getUser();

        if (!$user) {
            return $this->redirectToRoute('app_login');
        }
        
        $role1= 'ROLE_PRO' ;
       

        return $this->render('user/back/prousers.html.twig', [
            'user' => $this->getUser(),
            //'usersList' => $userRepo->findAll(),
            'ProList' => $userRepo->findPros([$role1]),
           // 'ClientList' => $userRepo->findPros([$role2])

        ]);
    }


    #[Route('admin/users/clients', name: 'CliUsers'), IsGranted('ROLE_ADMIN')]
    public function CliUsers(UserRepository $userRepo): Response
    {
        $user = $this->getUser();

        if (!$user) {
            return $this->redirectToRoute('app_login');
        }
        
        $role2= 'ROLE_USER' ;
       

        return $this->render('user/back/clients.html.twig', [
            'user' => $this->getUser(),
            //'usersList' => $userRepo->findAll(),
            //'ProList' => $userRepo->findPros([$role1]),
            'ClientList' => $userRepo->findPros([$role2])

        ]);
    }


    ///////////////////////FRONTBABY/////////////

    #[Route('/professionals', name: 'ProUsers')]
public function ProUsers(UserRepository $userRepo): Response
    {
       // $user = $this->getUser();

        //if (!$user) {
          //  return $this->redirectToRoute('app_login');
       // }
        $role= 'ROLE_PRO' ;




        return $this->render('user/front/professionals.html.twig', [
            'user' => $this->getUser(),
            'ProList' => $userRepo->findPros([$role])

        ]);
    }



    #[Route('professionals/{id}', name: 'showProUser', methods: ['GET'])]
    public function show(User $User): Response
    {
        $entityManager = $this->getDoctrine()->getManager();
        $User = $entityManager->getRepository(User::class)->find($User);
        

        return $this->render('user/front/ProfileProuser.html.twig', [
            'user' => $User,
            
        ]);
    }

    #[Route('/YourProfile', name: 'YourProfile')]

    public function YourProfile(): Response
    {
        $user = $this->getUser();

        if (!$user) {
            return $this->redirectToRoute('app_login');
        }        return $this->render('user/front/YourProfile.html.twig', [
            'user' => $user,
        ]);
    }

    #[Route('/YourProfile/edit/{id}', name: 'YourProfileEdit', methods : ['GET', 'POST'])]

    public function updateYourProfile(Request $request, User $user, UserRepository $ur,  SluggerInterface $slugger ): Response
    {
        $user = $this->getUser();

        if (!$user) {
            return $this->redirectToRoute('app_login');
        }

        $form = $this->createForm(EditYourProfileType::class, $user);
        $form->handleRequest($request);


        if ($form->isSubmitted() && $form->isValid()) {
            dump($request->request->all());


 //img
             /** @var UploadedFile $photo */
             $photo = $form->get('pic')->getData();

             // this condition is needed because the 'brochure' field is not required
             // so the PDF file must be processed only when a file is uploaded
             if ($photo) {
                 $originalFilename = pathinfo($photo->getClientOriginalName(), PATHINFO_FILENAME);
                 // this is needed to safely include the file name as part of the URL
                 $safeFilename = $slugger->slug($originalFilename);
                 $newFilename = $safeFilename.'-'.uniqid().'.'.$photo->guessExtension();
 
                 // Move the file to the directory where brochures are stored
                 try {
                     $photo->move(
                         $this->getParameter('users_directory'),
                         $newFilename
                     );
                 } catch (FileException $e) {
                     // ... handle exception if something happens during file upload
                 }
 
                 // updates the 'photoname' property to store the PDF file name
                 // instead of its contents
                 $user->setpdp($newFilename);
             }



            $ur->save($user, true);
            return $this->redirectToRoute('YourProfile', [], Response::HTTP_SEE_OTHER);
        }
        dump($form->getErrors(true, false));
        return $this->renderForm("user/front/EditYourProfile.html.twig", [
            "user" => $user,
            "updateForm" => $form,
            
            
        ]);
    }

    
}
