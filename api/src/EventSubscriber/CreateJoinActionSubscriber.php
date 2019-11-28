<?php


namespace App\EventSubscriber;


use ApiPlatform\Core\EventListener\EventPriorities;
use App\Entity\JoinAction;
use App\Entity\NotifyWish;
use App\Entity\Person;
use App\Enum\ActionStatusType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Event\ViewEvent;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\Security\Core\Security;

class CreateJoinActionSubscriber implements EventSubscriberInterface
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
            KernelEvents::VIEW => ['addFunderInitStatus', EventPriorities::PRE_VALIDATE]
        ];
    }
    public function addFunderInitStatus(ViewEvent $event){
        /** @var JoinAction|null $joinAction */
        $joinAction = $event->getControllerResult();
        $method = $event->getRequest()->getMethod();
        if(!$joinAction instanceof JoinAction || Request::METHOD_POST !== $method || $this->security->isGranted('ROLE_ADMIN')){
            return;
        }

        /** @var Person $loggedUser */
        $loggedUser = $this->security->getUser();

        $joinAction->setActionStatus(ActionStatusType::POTENTIAL_ACTION_STATUS());
        $joinAction->setFunder($loggedUser);
    }
}
