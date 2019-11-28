<?php


namespace App\EventSubscriber;


use ApiPlatform\Core\EventListener\EventPriorities;
use App\Entity\Course;
use App\Entity\Person;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Event\ViewEvent;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorage;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Security\Core\Security;

final class CreateCourseSubscriber implements EventSubscriberInterface
{
    /** @var Security */
    private $security;

    /**
     * CreateCourseSubscriber constructor.
     * @param Security $security
     */
    public function __construct(Security $security)
    {
        $this->security = $security;
    }

    public static function getSubscribedEvents()
    {
        return [
            KernelEvents::VIEW => ['addLoggedUserAsEditor', EventPriorities::PRE_VALIDATE]
        ];
    }

    public function addLoggedUserAsEditor(ViewEvent $event){
        /** @var Course|null $course */
        $course = $event->getControllerResult();
        $method = $event->getRequest()->getMethod();

        if(!$course instanceof Course || Request::METHOD_POST !== $method || $this->security->isGranted('ROLE_ADMIN')){
            return;
        }

        /** @var Person $loggedUser */
        $loggedUser = $this->security->getUser();
        $course->setEditor($loggedUser);
    }

}
