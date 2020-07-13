<?php


namespace App\Controller\ActivateAccount;


use App\Entity\CodeActive;
use App\Entity\User;
use Doctrine\ORM\EntityManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

class ActivateAccountController extends AbstractController
{

    /**
     * @Route("/activateAccount/{code}",methods={"GET"})
     */



    public function activateAccount( string  $code){

        $entityManager = $this->getDoctrine()->getManager();
        $codeActive = $entityManager->getRepository(CodeActive::class)->findOneBy(['code'=>$code]);

        if(!$codeActive){
            return new JsonResponse(['ActiveAccount'=>false, "message"=>"Wrong code"]);
        }

        $id =  $codeActive->getUser();
        $user = $entityManager->getRepository(User::class)->find($id);

        if(!$user){
            return new JsonResponse(['ActiveAccount'=>false, "message"=>"not found user"]);
        }
        $status = $user->getStatusAccaunt(true);

        if($status == true)
        {
           return new JsonResponse(['ActiveAccount'=>false, "message"=>"This Account is active"]);
        }

        $user->setStatusAccaunt(true);
        $entityManager->flush();
        return new JsonResponse(['ActiveAccount'=>true]);
    }



}