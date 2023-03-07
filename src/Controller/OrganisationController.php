<?php

namespace App\Controller;

use App\Entity\Organisation;
use App\Form\OrganisationType;
use App\Repository\CaisseOrganisationRepository;
use App\Repository\OrganisationRepository;
use App\Service\FileUploader;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\String\Slugger\SluggerInterface;

#[Route('/organisation')]
class OrganisationController extends AbstractController
{
    #[Route('/', name: 'app_organisation_index', methods: ['GET'])]
    public function index(OrganisationRepository $organisationRepository): Response
    {
        $organisations= $organisationRepository->findAll();
        $data= [];
        $orgsNames=array();
        foreach ($organisations as $organisation){
            $orgsNames[]=$organisation->getNomOrg();
            $caisseData=$organisation->getCaisses();
            $sum=0;
            foreach ($caisseData as $caisse){
                $sum+=$caisse->getMontantCaisseOrg();
            }
            $data[]=$sum;
        }
        $numDataPoints = count($data);
        $colors = array();
        for ($i = 0; $i < $numDataPoints; $i++) {
            $colors[] = "rgba(" . rand(0, 255) . ", " . rand(0, 255) . ", " . rand(0, 255) . ", 0.2)";
        }
        return $this->render('organisation/index.html.twig', [
            'organisations' => $organisations,
            'orgNames' => json_encode($orgsNames),
            'statData' => $data,
            'colors' => json_encode($colors),
        ]);
    }

    #[Route('/new', name: 'app_organisation_new', methods: ['GET', 'POST'])]
    public function new(Request $request, OrganisationRepository $organisationRepository,FileUploader $fileUploader): Response
    {
        $organisation = new Organisation();
        $form = $this->createForm(OrganisationType::class, $organisation);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $brochureFile = $form->get('documentOrganisation')->getData();
            $Logo = $form->get('logoOrg')->getData();
            if ($brochureFile) {
                $brochureFileName = $fileUploader->upload($brochureFile);
                $organisation->setDocumentOrganisation($brochureFileName);
            }
            if ($Logo) {
                $LogoFileName = $fileUploader->upload($Logo);
                $organisation->setLogoOrg($LogoFileName);
            }
            $organisationRepository->save($organisation, true);

            return $this->redirectToRoute('app_organisation_index', [], Response::HTTP_SEE_OTHER);
        }
        $organisations= $organisationRepository->findAll();
        $data= [];
        $orgsNames=array();
        foreach ($organisations as $organisation){
            $orgsNames[]=$organisation->getNomOrg();
            $caisseData=$organisation->getCaisses();
            $sum=0;
            foreach ($caisseData as $caisse){
                $sum+=$caisse->getMontantCaisseOrg();
            }
            $data[]=$sum;
        }
        $numDataPoints = count($data);
        $colors = array();
        for ($i = 0; $i < $numDataPoints; $i++) {
            $colors[] = "rgba(" . rand(0, 255) . ", " . rand(0, 255) . ", " . rand(0, 255) . ", 0.2)";
        }
        return $this->renderForm('organisation/new.html.twig', [
            'organisation' => $organisation,
            'form' => $form,
            'organisations' => $organisations,
            'orgNames' => json_encode($orgsNames),
            'statData' => $data,
            'colors' => json_encode($colors),
        ]);
    }

    #[Route('/{id}', name: 'app_organisation_show', methods: ['GET'])]
    public function show(Organisation $organisation, CaisseOrganisationRepository $caisseOrganisationRepository,OrganisationRepository $organisationRepository): Response
    {

        $organisations= $organisationRepository->findAll();
        $data= [];
        $orgsNames=array();
        foreach ($organisations as $org){
            $orgsNames[]=$org->getNomOrg();
            $caisseData=$org->getCaisses();
            $sum=0;
            foreach ($caisseData as $caisse){
                $sum+=$caisse->getMontantCaisseOrg();
            }
            $data[]=$sum;
        }
        $numDataPoints = count($data);
        $colors = array();
        for ($i = 0; $i < $numDataPoints; $i++) {
            $colors[] = "rgba(" . rand(0, 255) . ", " . rand(0, 255) . ", " . rand(0, 255) . ", 0.2)";
        }
        return $this->render('organisation/show.html.twig', [
            'organisation' => $organisation,
            'Caisses' => $caisseOrganisationRepository->findBy([
                'organisation' => $organisation
            ]),
            'organisations' => $organisations,
            'orgNames' => json_encode($orgsNames),
            'statData' => $data,
            'colors' => json_encode($colors)
        ]);
    }

    #[Route('/{id}/edit', name: 'app_organisation_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Organisation $organisation, OrganisationRepository $organisationRepository,FileUploader $fileUploader): Response
    {
        $form = $this->createForm(OrganisationType::class, $organisation);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $brochureFile = $form->get('documentOrganisation')->getData();
            $Logo = $form->get('logoOrg')->getData();

            if ($brochureFile) {
                $brochureFileName = $fileUploader->upload($brochureFile);
                $organisation->setDocumentOrganisation($brochureFileName);
            }
            if ($Logo) {
                $LogoFileName = $fileUploader->upload($Logo);
                $organisation->setLogoOrg($LogoFileName);
            }
            $organisationRepository->save($organisation,true);
            return $this->redirectToRoute('app_organisation_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('organisation/edit.html.twig', [
            'organisation' => $organisation,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_organisation_delete', methods: ['POST'])]
    public function delete(Request $request, Organisation $organisation, OrganisationRepository $organisationRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$organisation->getId(), $request->request->get('_token'))) {
            $organisationRepository->remove($organisation, true);
        }

        return $this->redirectToRoute('app_organisation_index', [], Response::HTTP_SEE_OTHER);
    }
}
