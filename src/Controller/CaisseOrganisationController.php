<?php

namespace App\Controller;

use App\Entity\CaisseOrganisation;
use App\Entity\Organisation;
use App\Form\CaisseOrganisation1Type;
use App\Repository\CaisseOrganisationRepository;
use App\Repository\OrganisationRepository;
use Monolog\Registry;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/caisse/organisation')]
class CaisseOrganisationController extends AbstractController
{
    #[Route('/', name: 'app_caisse_organisation_index', methods: ['GET'])]
    public function index(CaisseOrganisationRepository $caisseOrganisationRepository): Response
    {
        return $this->render('caisse_organisation/index.html.twig', [
            'caisse_organisations' => $caisseOrganisationRepository->findAll(),
        ]);
    }

    #[Route('/new/{id}', name: 'app_caisse_organisation_new', methods: ['GET', 'POST'])]
    public function new(Organisation $organisation,Request $request, CaisseOrganisationRepository $caisseOrganisationRepository): Response
    {
        $caisseOrganisation = new CaisseOrganisation();
        $form = $this->createForm(CaisseOrganisation1Type::class, $caisseOrganisation);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $caisseOrganisation->setOrganisation($organisation);

            $caisseOrganisationRepository->save($caisseOrganisation, true);

            return $this->redirectToRoute('app_organisation_show',[
                'id'=> $organisation->getId(),
            ]);
        }

        return $this->renderForm('caisse_organisation/new.html.twig', [
            'caisse_organisation' => $caisseOrganisation,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_caisse_organisation_show', methods: ['GET'])]
    public function show(CaisseOrganisation $caisseOrganisation): Response
    {
        return $this->render('caisse_organisation/show.html.twig', [
            'caisse_organisation' => $caisseOrganisation,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_caisse_organisation_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, CaisseOrganisation $caisseOrganisation, CaisseOrganisationRepository $caisseOrganisationRepository): Response
    {
        $form = $this->createForm(CaisseOrganisation1Type::class, $caisseOrganisation);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $caisseOrganisationRepository->save($caisseOrganisation, true);

            return $this->redirectToRoute('app_caisse_organisation_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('caisse_organisation/edit.html.twig', [
            'caisse_organisation' => $caisseOrganisation,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_caisse_organisation_delete', methods: ['POST'])]
    public function delete(Request $request, CaisseOrganisation $caisseOrganisation, CaisseOrganisationRepository $caisseOrganisationRepository): Response
    {
        $session = $request->getSession();
        $session->set('previous_url', $request->headers->get('referer'));
        if ($this->isCsrfTokenValid('delete'.$caisseOrganisation->getId(), $request->request->get('_token'))) {
            $caisseOrganisationRepository->remove($caisseOrganisation, true);
        }
        $previousUrl = $session->get('previous_url');

        return $this->redirect($previousUrl);
    }


}
