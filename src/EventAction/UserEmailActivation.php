<?php


namespace App\EventAction;


use ApiPlatform\Core\EventListener\EventPriorities;
use App\Entity\User;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Event\ViewEvent;
use Symfony\Component\HttpKernel\KernelEvents;

class UserEmailActivation implements EventSubscriberInterface
{
    private $mailer;

    public function __construct(\Swift_Mailer $mailer)
    {
        $this->mailer = $mailer;
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
        $message = (new \Swift_Message('Register'))
            ->setFrom('noreply@app.com')
            ->setTo($user->getEmail())
            ->setBody(sprintf('User add %s',$user->getId()));
        error_log('message ok');
        $this->mailer->send($message);
    }


}