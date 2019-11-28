<?php


namespace App\EventSubscriber;


use ApiPlatform\Core\EventListener\EventPriorities;
use App\Entity\Course;
use App\Entity\EducationEvent;
use App\Entity\NotifyWish;
use App\Entity\Person;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Event\ViewEvent;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Symfony\Component\Security\Core\Security;

class CreateNotifyWishSubscriber implements EventSubscriberInterface
{
    /** @var Security */
    private $security;
    /** @var EntityManagerInterface */
    private $em;

    /**
     * CreateEducationEventSubscriber constructor.
     * @param Security $security
     * @param EntityManagerInterface $em
     */
    public function __construct(Security $security, EntityManagerInterface $em)
    {
        $this->security = $security;
        $this->em = $em;
    }

    public static function getSubscribedEvents()
    {
        return [
            KernelEvents::VIEW => ['addFunder', EventPriorities::PRE_VALIDATE]
        ];
    }
    public function addFunder(ViewEvent $event){
        /** @var NotifyWish|null $notifyWish */
        $notifyWish = $event->getControllerResult();
        $method = $event->getRequest()->getMethod();
        if(!$notifyWish instanceof NotifyWish || Request::METHOD_POST !== $method || $this->security->isGranted('ROLE_ADMIN')){
            return;
        }

        /** @var Person $loggedUser */
        $loggedUser = $this->security->getUser();

        $notifyWish->setFunder($loggedUser);
    }

}
