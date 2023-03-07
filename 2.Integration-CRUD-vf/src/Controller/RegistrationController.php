<?php
namespace App\Controller;

use App\Entity\User;
use App\Form\UserType;
use App\Form\UserProType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use  Symfony\Component\HttpFoundation\Response;

use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\String\Slugger\SluggerInterface;


class RegistrationController extends AbstractController
{
    private $passwordEncoder;

    public function __construct(UserPasswordEncoderInterface $passwordEncoder)
    {
        $this->passwordEncoder = $passwordEncoder;
    }



/**
     * @Route("/pick", name="pick")
     */
    public function pick(): Response
    {
        return $this->render('registration/pick.html.twig');
    }



    /**
     * @Route("/registration/user", name="registration_user")
     */
    public function registerUser(Request $request,  SluggerInterface $slugger)
    {
        $user = new User();

        $form = $this->createForm(UserType::class, $user);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // Encode the new users password
            $user->setPassword($this->passwordEncoder->encodePassword($user, $user->getPassword()));

            // Set their role
            $user->setRoles(['ROLE_USER']);

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


            // Save
            $em = $this->getDoctrine()->getManager();
            $em->persist($user);
            $em->flush();

            return $this->redirectToRoute('app_login');
        }

        return $this->render('registration/user.html.twig', [
            'formR' => $form->createView(),
        ]);
    }

/********************************user_pro registration  ***********/

     /**
     * @Route("/registration/pro", name="registration_pro")
     */
    public function RegisterPro(Request $request,  SluggerInterface $slugger)
    {
        $user = new User();

        $form = $this->createForm(UserProType::class, $user);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // Encode the new users password
            $user->setPassword($this->passwordEncoder->encodePassword($user, $user->getPassword()));

            // Set their role
            $user->setRoles(['ROLE_PRO']);

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


              //pdf
             /** @var UploadedFile $photo */
             $pdf = $form->get('certif')->getData();

             // this condition is needed because the 'brochure' field is not required
             // so the PDF file must be processed only when a file is uploaded
             if ($pdf) {
                 $originalFilename1 = pathinfo($pdf->getClientOriginalName(), PATHINFO_FILENAME);
                 // this is needed to safely include the file name as part of the URL
                 $safeFilename1 = $slugger->slug($originalFilename1);
                 $newFilename1 = $safeFilename1.'-'.uniqid().'.'.$pdf->guessExtension();
 
                 // Move the file to the directory where brochures are stored
                 try {
                     $pdf->move(
                         $this->getParameter('users_directory'),
                         $newFilename1
                     );
                 } catch (FileException $e) {
                     // ... handle exception if something happens during file upload
                 }
 
                 // updates the 'pdfname' property to store the PDF file name
                 // instead of its contents
                 $user->setDiplome($newFilename1);
             }
 

            // Save
            $em = $this->getDoctrine()->getManager();
            $em->persist($user);
            $em->flush();

            return $this->redirectToRoute('app_login');
        }

        return $this->render('registration/userpro.html.twig', [
            'formR' => $form->createView(),
        ]);
    }
}