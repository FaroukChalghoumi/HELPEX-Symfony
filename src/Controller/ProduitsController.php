<?php

namespace App\Controller;

use App\Entity\Produits;
use App\Form\ProduitAuthType;
use App\Form\ProduitsType;
use App\Repository\CategorieProduitRepository;
use App\Repository\ProduitsRepository;
use App\Service\PdfService;
use Dompdf\Dompdf;
use Dompdf\Options;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;
use Symfony\Component\String\Slugger\SluggerInterface;


class ProduitsController extends AbstractController
{
    #[Route('/pdf/front/{id}', name: 'pdf_inscription_produit', methods: ['GET', 'POST'])]
    public function genererpdfinscription(Produits $produits, PdfService $pdf){
        //$html=$this->render('inscription_formation/show_pdf.html.twig',['inscription_formation'=>$inscriptionFormation]);
        // $pdf->showPdfFile($html);
        $options = new Options();
        $options->set('defaultFont', 'Arial');

        $dompdf = new Dompdf($options);

        $html = $this->renderView('produits/show_pdf.html.twig', [
            'name' => 'John Doe'
        ]);

        $dompdf->loadHtml($html);

        $dompdf->setPaper('A4', 'portrait');

        $dompdf->render();

        $filename = 'custom_pdf.pdf';
        return new Response(
            $dompdf->output(),
            200,
            array(
                'Content-Type' => 'application/pdf',
                'Content-Disposition' => 'attachment; filename="' . $filename . '"'
            )
        );
    }
    //****mobile****

    #[Route('/produits/allproduits', name: 'mobileafficherproduit', methods: ['GET'])]
    public function indexjson(NormalizerInterface $Normalizer): Response
    {
        //http://127.0.0.1:8000/produits/allproduits
        $respository=$this->getDoctrine()->getRepository(Produits::class);
        $produits=$respository->findAll();
        $jsonContent=$Normalizer->normalize($produits,'json',['groups'=>'post:read']);
        return  new Response(json_encode($jsonContent));


    }

    #[Route('/produits/ajouterjson',name:'mobileajouterproduit',methods: ['GET','POST'])]
    public function addcentrejson(Request $request,NormalizerInterface $Normalizer)
    {
        //http://127.0.0.1:8000/produits/ajouterjson?NomProduit=qsd&EtatProduit=hgv&DescriptionProduit=aaaaaaaa&localisationProduit=xxx&Brand=sss&PrixProduit=47
        $em=$this->getDoctrine()->getManager();
        $produit=new Produits();
        $produit->setNomProduit($request->get('NomProduit'));
        $produit->setEtatProduit($request->get('EtatProduit'));
        $produit->setPrixProduit($request->get('PrixProduit'));
        $produit->setDescriptionProduit($request->get('DescriptionProduit'));
        $produit->setLocalisationProduit($request->get('localisationProduit'));
        $produit->setBrand($request->get('Brand'));
        $produit->setAuthorisation(true);


        $em->persist($produit);
        $em->flush();
        $jsonContent=$Normalizer->normalize($produit,'json',['groups'=>'post:read']);
        return new Response(json_encode($jsonContent));


    }
    #[Route("/produits/updateprduitjson/{id}", name: "updateproduitjson")]
    public function updateCentreJSON(Request $request, $id, NormalizerInterface $Normalizer)
    {
       // http://127.0.0.1:8000/produits/updateprduitjson/houniID?NomProduit=xxx&EtatProduit=hgv&DescriptionProduit=aaaaaaaa&localisationProduit=xxx&Brand=sss&PrixProduit=47
        $em = $this->getDoctrine()->getManager();
        $produit = $em->getRepository(Produits::class)->find($id);
        $produit->setNomProduit($request->get('NomProduit'));
        $produit->setEtatProduit($request->get('EtatProduit'));
        $produit->setPrixProduit($request->get('PrixProduit'));
        $produit->setDescriptionProduit($request->get('DescriptionProduit'));
        $produit->setLocalisationProduit($request->get('localisationProduit'));
        $produit->setBrand($request->get('Brand'));

        $em->flush();

        $jsonContent = $Normalizer->normalize($produit, 'json', ['groups' => "post:read"]);
        return new Response("produit updated successfully " . json_encode($jsonContent));
    }

    #[Route("/produits/deleteproduitjson/{id}", name: "deleteproduitjson")]
    public function deletecentrejson(Request $req, $id, NormalizerInterface $Normalizer)
    {
        //http://127.0.0.1:8000/produits/deleteproduitjson/5
        $em = $this->getDoctrine()->getManager();
        $produit = $em->getRepository(Produits::class)->find($id);
        $em->remove($produit);
        $em->flush();
        $jsonContent = $Normalizer->normalize($produit, 'json', ['groups' => "post:read"]);
        return new Response("produit deleted successfully " . json_encode($jsonContent));
    }
    //***********




    #[Route('/produits/', name: 'app_produits_index', methods: ['GET'])]
    public function index(ProduitsRepository $produitsRepository,CategorieProduitRepository $categorieProduitRepository): Response
    {
        return $this->render('produits/index.html.twig', [
            'produits' => $produitsRepository->findAll(),
            'categorie_produits' => $categorieProduitRepository->findAll(),
        ]);
    }


    #[Route('/marketplace/', name: 'app_produits_marketplace', methods: ['GET'])]
    public function marketplace(ProduitsRepository $produitsRepository,CategorieProduitRepository $categorieProduitRepository): Response
    {

        $user = $this->getUser();
        return $this->render('produits/Profile.html.twig', [
            'user' => $user,
            'produits' => $produitsRepository->findAll(),
            'categorie_produits' => $categorieProduitRepository->findAll(),
        ]);
    }



//    #[Route('/', name: 'app_produits_index', methods: ['GET'])]
//    public function indexFront(CategorieProduitRepository $categorieProduitRepository): Response
//    {
//        return $this->render('produits/index.html.twig', [
//            'categorie_produits' => $categorieProduitRepository->findAll(),
//        ]);
//    }
    #[Route('/produits/new', name: 'app_produits_new', methods: ['GET', 'POST'])]
    public function new(Request $request, ProduitsRepository $produitsRepository ,  SluggerInterface $slugger): Response
    {
        $user = $this->getUser();

        if (!$user) {
            return $this->redirectToRoute('app_login');
        }
        $produit = new Produits();
        $form = $this->createForm(ProduitsType::class, $produit);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {

            $pictureFile = $form->get('ImagePath')->getData();
            if ($pictureFile) {
                $originalFilename = pathinfo($pictureFile->getClientOriginalName(), PATHINFO_FILENAME);
                $safeFilename = $slugger->slug($originalFilename);
                $newFilename = $safeFilename.'-'.uniqid().'.'.$pictureFile->guessExtension();

                try {
                    $pictureFile->move(
                        $this->getParameter('users_directory'),
                        $newFilename
                    );
                } catch (FileException $e) {
                    // handle exception
                }

                $produit->setImagePath($newFilename);
            }
            $produit->setStatusProduit("Selling");
            $produit->setAuthorisation(false);
            $produit->setUser($user);
            $produitsRepository->save($produit, true);
            /*$ImagePath = $form->get('ImagePath')->getData();

            if ($ImagePath) {
                $originalFilename = pathinfo($ImagePath->getClientOriginalName(), PATHINFO_FILENAME);
                // this is needed to safely include the file name as part of the URL
                $safeFilename = $slugger->slug($originalFilename);
                $newFilename = $safeFilename.'-'.uniqid().'.'.$ImagePath->guessExtension();

                // Move the file to the directory where brochures are stored
                try {
                    $ImagePath->move(
                        $this->getParameter('Image_Produits'),
                        $newFilename
                    );
                } catch (FileException $e) {
                    // ... handle exception if something happens during file upload
                }

                // updates the 'brochureFilename' property to store the PDF file name
                // instead of its contents
                $produit->setImagePath($newFilename);*/
                //$produitsRepository->save($produit, true);
       // }



            return $this->redirectToRoute('app_produits_marketplace', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('produits/new.html.twig', [
            'produit' => $produit,
            'form' => $form,
        ]);
    }

    #[Route('/produits/{id}', name: 'app_produits_show', methods: ['GET'])]
    public function show(Produits $produit): Response
    {
        $user = $this->getUser();
        return $this->render('produits/show.html.twig', [
            'produit' => $produit,
            'user' => $user , 
        ]);
    }


    #[Route('/produits/{id}', name: 'app_produits_index_adminath', methods: ['GET']) , IsGranted('ROLE_ADMIN')]
    public function showadm(Produits $produit): Response
    {
        $user = $this->getUser();

        if (!$user) {
            return $this->redirectToRoute('app_login');
        }
        return $this->render('produits/show.html.twig', [
            'produit' => $produit,
        ]);
    }

    #[Route('/produits/{id}/edit', name: 'app_produits_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Produits $produit, ProduitsRepository $produitsRepository): Response
    {
        $user = $this->getUser();

        if (!$user) {
            return $this->redirectToRoute('app_login');
        }
        $form = $this->createForm(ProduitsType::class, $produit);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $produitsRepository->save($produit, true);

            return $this->redirectToRoute('app_produits_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('produits/edit.html.twig', [
            'produit' => $produit,
            'form' => $form,
        ]);
    }


    #[Route('/{id}/edit/back', name: 'app_produits_edit_auth', methods: ['GET', 'POST']) , IsGranted('ROLE_ADMIN') ]
    public function editAdmin(Request $request ,Produits $produit, ProduitsRepository $produitsRepository): Response
    {
        $user = $this->getUser();

        if (!$user) {
            return $this->redirectToRoute('app_login');
        }
        $form = $this->createForm(ProduitAuthType::class, $produit);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $produitsRepository->save($produit, true);

            return $this->redirectToRoute('app_categorie_produit_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('produits/editAdmin.html.twig', [
            'produit' => $produit,
            'form' => $form,
        ]);
    }

    #[Route('/produits/{id}', name: 'app_produits_delete', methods: ['POST'])]
    public function delete(Request $request, Produits $produit, ProduitsRepository $produitsRepository): Response
    {
        $user = $this->getUser();

        if (!$user) {
            return $this->redirectToRoute('app_login');
        }
        if ($this->isCsrfTokenValid('delete'.$produit->getId(), $request->request->get('_token'))) {
            $produitsRepository->remove($produit, true);
        }

        return $this->redirectToRoute('app_produits_marketplace', [], Response::HTTP_SEE_OTHER);
    }




}
