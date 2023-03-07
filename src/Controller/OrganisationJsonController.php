<?php

namespace App\Controller;

use App\Entity\Organisation;
use App\Repository\CaisseOrganisationRepository;
use App\Repository\OrganisationRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;
use Symfony\Component\Serializer\SerializerInterface;
use GuzzleHttp\Client;

class OrganisationJsonController extends AbstractController
{
    #[Route('/organisationjson', name: 'app_organisation_json')]
    public function index(SerializerInterface $serializer): Response
    {
        $repository=$this->getDoctrine()->getRepository(Organisation::class);
        $organisations=$repository->findAll();
        $data=$serializer->normalize($organisations,'json',['groups'=> 'post:read']);

        return new Response(json_encode($data));
    }
    #[Route('/organisationjson/{id}', name: 'app_organisation_json_show', methods: ['GET'])]
    public function show(Organisation $organisation, SerializerInterface $serializer): Response
    {
        $data=$serializer->normalize($organisation,'json',['groups'=> 'post:read']);

        return new Response(json_encode($data));
    }
    #[Route('/organisationaddjson', name: 'app_organisation_json_new', methods: ['GET', 'POST'])]
    public function new( Request $request,OrganisationRepository $organisationRepository,SerializerInterface $serializer): Response
    {
        $organisation = new Organisation();

        $organisation->setEmailOrganisation($request->get('email_organisation'));
        $organisation->setNomOrg($request->get('nom_org'));
        $organisation->setPaymentInfo($request->get('payment_info'));
        $organisation->setNumTelOrganisation($request->get('num_tel_organisation'));
        $organisation->setDescriptionOrganisation($request->get('description_organisation'));
        $organisationRepository->save($organisation,true);
        $data=$serializer->normalize($organisation,'json',['groups'=> 'post:read']);
        return new Response(json_encode($data));
    }
    //***mobilejson**
    #[Route("/deleteorgjson/{id}", name: "deletecentrejson")]
    public function deletecentrejson(Request $req, $id, NormalizerInterface $Normalizer)
    {

        $em = $this->getDoctrine()->getManager();
        $centre = $em->getRepository(Organisation::class)->find($id);
        $em->remove($centre);
        $em->flush();
        $jsonContent = $Normalizer->normalize($centre, 'json', ['groups' => "post:read"]);
        return new Response("centre deleted successfully " . json_encode($jsonContent));
    }


    #[Route("/updateorgjson/{id}", name: "updatecentrejson")]
    public function updateCentreJSON(Request $request, $id, NormalizerInterface $Normalizer)
    {

        $em = $this->getDoctrine()->getManager();
        $organisation = $em->getRepository(Organisation::class)->find($id);
        if($request->get('email_organisation'))
        $organisation->setEmailOrganisation($request->get('email_organisation'));
        if($request->get('nom_org'))
        $organisation->setNomOrg($request->get('nom_org'));
        if($request->get('payment_info'))
        $organisation->setPaymentInfo($request->get('payment_info'));
        if($request->get('num_tel_organisation'))
        $organisation->setNumTelOrganisation($request->get('num_tel_organisation'));
        if($request->get('description_organisation'))
        $organisation->setDescriptionOrganisation($request->get('description_organisation'));

        $em->flush();

        $jsonContent = $Normalizer->normalize($organisation, 'json', ['groups' => "post:read"]);
        return new Response("centre updated successfully " . json_encode($jsonContent));
    }
}
