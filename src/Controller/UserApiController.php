<?php

namespace App\Controller;

use App\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;
use Symfony\Component\Validator\Constraints\DateTimeInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;
use App\Repository\UserRepository;





class UserApiController extends AbstractController
{

    #[Route('/usersall', name: 'usersall', methods: ['GET'])]
    public function indexjson(UserRepository $Urep, NormalizerInterface $Normalizer): Response
    {
       $users=$Urep->findAll();
        // $users = $this->getDoctrine()->getRepository(User::class)->findAll();
        $jsonContent=$Normalizer->normalize($users,'json',['groups'=>'post:read']);
        return  new Response(json_encode($jsonContent));

    }


    #[Route('/user/signupClient', name: 'signup_client')]
    public function signupClient(Request $request, UserPasswordEncoderInterface $passwordEncoder)
    {

        $email=$request->query->get("email");
        //$roles=$request->query->get("roles");
        $password=$request->query->get("password");
        $Nom=$request->query->get("Nom");
        $Prenom=$request->query->get("Prenom");
        $sexe=$request->query->get("sexe");
        $adresse=$request->query->get("adresse");
        $num_tel=$request->query->get("num_tel");
        $pdp=$request->query->get("pdp");
        $bio=$request->query->get("bio");
        //$date_naissance=$request->query->get("date_naissance");

if(!filter_var($email, FILTER_VALIDATE_EMAIL)){
    return new Response("email invalid");
}
$user= new User();
$user->setEmail($email);
$user->setRoles(['ROLE_USER']);
$user->setPassword(
   $passwordEncoder->encodePassword($user,$password) 
);
$user->setNom($Nom);
$user->setPrenom($Prenom);
$user->setSexe($sexe);
$user->setAdresse($adresse);
$user->setNumTel($num_tel);
$user->setpdp($pdp);
$user->setBio($bio);
//$user->setDateNaissance(new DateTimeInterface($date_naissance));
$user->setIsEnabled(true);

try{
    $em=$this->getDoctrine()->getManager();
    $em->persist($user);
    $em->flush();

    return new JsonResponse("account created", 200);
} catch (\Exception $ex){
    return new Response("exception".$ex->getMessage());
}


} 


#[Route('/user/signupPRO', name: 'signup_PRO')]
public function signupPRO(Request $request, UserPasswordEncoderInterface $passwordEncoder)
{

    $email=$request->query->get("email");
    //$roles=$request->query->get("roles");
    $password=$request->query->get("password");
    /*$Nom=$request->query->get("Nom");
    $Prenom=$request->query->get("Prenom");
    $sexe=$request->query->get("sexe");
    $adresse=$request->query->get("adresse");
    $num_tel=$request->query->get("num_tel");
    $pdp=$request->query->get("pdp");
    $bio=$request->query->get("bio");
    //$date_naissance=$request->query->get("date_naissance");
    $diplome=$request->query->get("diplome");
    $tarif=$request->query->get("tarif");*/




if(!filter_var($email, FILTER_VALIDATE_EMAIL)){
return new Response("email invalid");
}
$user= new User();
$user->setEmail($email);
$user->setRoles(['ROLE_PRO']);
$user->setPassword(
$passwordEncoder->encodePassword($user,$password) 
);
/*$user->setNom($Nom);
$user->setPrenom($Prenom);
$user->setSexe($sexe);
$user->setAdresse($adresse);
$user->setNumTel($num_tel);
$user->setpdp($pdp);
$user->setBio($bio);
//$user->setDateNaissance(new DateTimeInterface($date_naissance));
$user->setIsEnabled(true);
$user->setDiplome($diplome);
$user->setTarif(floatval($tarif));*/


try{
$em=$this->getDoctrine()->getManager();
$em->persist($user);
$em->flush();

return new JsonResponse("account created", 200);
} catch (\Exception $ex){
return new Response("exception".$ex->getMessage());
}


} 


#[Route('/user/EditProfile', name: 'edit_profile')]
public function EditProfile(Request $request, UserPasswordEncoderInterface $passwordEncoder)
{

    $id=$request->get("id");
    $email=$request->query->get("email");
    $password=$request->query->get("password");
    $Nom=$request->query->get("Nom");
    $Prenom=$request->query->get("Prenom");
    $adresse=$request->query->get("adresse");
    $num_tel=$request->query->get("num_tel");
    //$pdp=$request->query->get("pdp");
    $bio=$request->query->get("bio");
    //$date_naissance=$request->query->get("date_naissance");
    //$diplome=$request->query->get("diplome");
    $tarif=$request->query->get("tarif");

    $em=$this->getDoctrine()->getManager();
    $user=$em->getRepository(User::class)->find($id);
    //img
    if($request->files->get("pdp")!=null){
        $file=$request->files->get("pdp");
        $fileName=$file->getClientOriginalName();
        $file->move(
            $fileName
        );
        $user->setpdp($fileName);
    }
    

    $user->setEmail($email);
    $user->setPassword(
    $passwordEncoder->encodePassword($user,$password) 
    );
    $user->setNom($Nom);
    $user->setPrenom($Prenom);
    $user->setAdresse($adresse);
    $user->setNumTel($num_tel);
    $user->setBio(strval($bio));
    //$user->setDateNaissance(new DateTimeInterface($date_naissance));
    //$user->setDiplome($diplome);
    $user->setTarif(floatval($tarif));


    try{
        $em=$this->getDoctrine()->getManager();
        $em->persist($user);
        $em->flush();
         
        return new JsonResponse("profile updated!", 200);
        } catch (\Exception $ex){
        return new Response("exception".$ex->getMessage());
        }
}





#[Route('/user/signin', name: 'signin')]
public function signin(Request $request)
{

    $email=$request->query->get("email");
    $password=$request->query->get("password");

    $em=$this->getDoctrine()->getManager();
$user=$em->getRepository(User::class)->findOneBy(['email'=>$email]);
if($user){
    if(password_verify($password,$user->getPassword())){
        $serializer = new Serializer([new ObjectNormalizer()]);
        $formatted=$serializer->Normalize($user);
        return new JsonResponse($formatted);
    } else{
        return new Response("password not found");
    }
}
else {
return new Response("user not found");
}
}






}
