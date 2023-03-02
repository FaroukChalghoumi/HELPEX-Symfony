<?php

namespace App\Controller;

use App\Entity\Centre;
use App\Form\CentreType;
use App\Repository\CentreRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;

#[Route('/centre')]
class CentreController extends AbstractController
{
    //***mobilejson**
    #[Route('/allcentres', name: 'mobileaffichercentre', methods: ['GET'])]
    public function indexjson(NormalizerInterface $Normalizer): Response
    {
        $respository=$this->getDoctrine()->getRepository(Centre::class);
        $centres=$respository->findAll();
        $jsonContent=$Normalizer->normalize($centres,'json',['groups'=>'post:read']);
        return  new Response(json_encode($jsonContent));


    }
    #[Route('/ajouterjson',name:'mobileajoutercentre',methods: ['GET','POST'])]
    public function addcentrejson(Request $request,NormalizerInterface $Normalizer)
    {
        $em=$this->getDoctrine()->getManager();
        $centre=new Centre();
        $centre->setNomCentre($request->get('nomCentre'));
        $centre->setAdresseCentre($request->get('adresseCentre'));
        $centre->setEmailCentre($request->get('emailCentre'));
        $centre->setTelephoneCentre($request->get('telephoneCentre'));
        $centre->setSiteWebCentre($request->get('siteWebCentre'));
        $em->persist($centre);
        $em->flush();
        $jsonContent=$Normalizer->normalize($centre,'json',['groups'=>'post:read']);
        return new Response(json_encode($jsonContent));


    }

    //*************************************************

    #[Route('/', name: 'app_centre_index', methods: ['GET'] ), IsGranted('ROLE_ADMIN')]
    public function index(CentreRepository $centreRepository): Response
    {
        $user = $this->getUser();

        if (!$user) {
            return $this->redirectToRoute('app_login');
        }
        return $this->render('centre/index.html.twig', [
            'centres' => $centreRepository->findAll(),
        ]);
    }


    #[Route('/front', name: 'app_centre_index_front', methods: ['GET'])]
    public function indexfront(CentreRepository $centreRepository): Response
    {

        return $this->render('centre/index_front.html.twig', [
            'centres' => $centreRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_centre_new', methods: ['GET', 'POST']), IsGranted('ROLE_ADMIN')]
    public function new(Request $request, CentreRepository $centreRepository): Response
    {
        $user = $this->getUser();

        if (!$user) {
            return $this->redirectToRoute('app_login');
        }
        $centre = new Centre();
        $form = $this->createForm(CentreType::class, $centre);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $centreRepository->save($centre, true);

            return $this->redirectToRoute('app_centre_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('centre/new.html.twig', [
            'centre' => $centre,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_centre_show', methods: ['GET']), IsGranted('ROLE_ADMIN')]
    public function show(Centre $centre): Response
    {
        $user = $this->getUser();

        if (!$user) {
            return $this->redirectToRoute('app_login');
        }
        return $this->render('centre/show.html.twig', [
            'centre' => $centre,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_centre_edit', methods: ['GET', 'POST']), IsGranted('ROLE_ADMIN')]
    public function edit(Request $request, Centre $centre, CentreRepository $centreRepository): Response
    {
        $user = $this->getUser();

        if (!$user) {
            return $this->redirectToRoute('app_login');
        }
        $form = $this->createForm(CentreType::class, $centre);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $centreRepository->save($centre, true);

            return $this->redirectToRoute('app_centre_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('centre/edit.html.twig', [
            'centre' => $centre,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_centre_delete', methods: ['POST']), IsGranted('ROLE_ADMIN')]
    public function delete(Request $request, Centre $centre, CentreRepository $centreRepository): Response
    {
        $user = $this->getUser();

        if (!$user) {
            return $this->redirectToRoute('app_login');
        }
        if ($this->isCsrfTokenValid('delete'.$centre->getId(), $request->request->get('_token'))) {
            $centreRepository->remove($centre, true);
        }

        return $this->redirectToRoute('app_centre_index', [], Response::HTTP_SEE_OTHER);
    }
}
