<?php


namespace App\EventAction;


use ApiPlatform\Core\EventListener\EventPriorities;
use App\Entity\CodeActive;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Event\ViewEvent;
use Symfony\Component\HttpKernel\KernelEvents;

class UserEmailActivation implements EventSubscriberInterface
{
    private $mailer;
    private $em;
    public function __construct(\Swift_Mailer $mailer, EntityManagerInterface $entityManager)
    {
        $this->mailer = $mailer;
        $this->em = $entityManager;
    }

    public static function getSubscribedEvents()
    {
        return[
            KernelEvents::VIEW=>['sendEmailActivation',EventPriorities::POST_WRITE]
        ];
    }

    public function sendEmailActivation(ViewEvent $event){
        $user = $event->getControllerResult();

        $method = $event->getRequest()->getMethod();

        if(!$user instanceof User || Request::METHOD_POST !==$method){
           error_log('not send');
            return;
        }

        $users = $this->em->getRepository(User::class)->find($user->getId());

        $code = $this->generateCodeActive();

        $codeActive = new CodeActive();
        $codeActive->setCode($code);
        $codeActive->setUser($users);
        $this->em->persist($codeActive);
        $this->em->flush();

        $message = (new \Swift_Message('Register'))
            ->setFrom('noreply@app.com')
            ->setTo($user->getEmail())
            ->setBody(sprintf('User add %s,Code:%s',$user->getId(),$code));
        error_log('message ok');
        $this->mailer->send($message);
    }

    public function generateCodeActive(){
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $randomString = '';
        $rand = rand(0,244);
        for ($i = 0; $i < $rand; $i++) {
            $index = rand(0, strlen($characters) - 1);
            $randomString .= $characters[$index];
        }
        $salt = rand(0,1000);
        $code=hash_pbkdf2("sha256", '$password',$salt, $rand);
        return $code;
    }


}