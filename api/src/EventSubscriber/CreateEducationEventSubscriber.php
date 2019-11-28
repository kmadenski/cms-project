<?php


namespace App\EventSubscriber;


use ApiPlatform\Core\EventListener\EventPriorities;
use App\Entity\Course;
use App\Entity\EducationEvent;
use App\Entity\Person;
use Doctrine\DBAL\Schema\View;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Event\ViewEvent;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Symfony\Component\Security\Core\Security;

class CreateEducationEventSubscriber implements EventSubscriberInterface
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
            KernelEvents::VIEW => ['addCourse', EventPriorities::PRE_VALIDATE]
        ];
    }
    public function addCourse(ViewEvent $event){
        /** @var EducationEvent|null $educationEvent */
        $educationEvent = $event->getControllerResult();
        $method = $event->getRequest()->getMethod();
        if(!$educationEvent instanceof EducationEvent || Request::METHOD_POST !== $method || $this->security->isGranted('ROLE_ADMIN')){
            return;
        }

        /** @var Course|null $course */
        $course = $educationEvent->getCourse();

        if(!$course){
            return;
        }

        /** @var Person $loggedUser */
        $loggedUser = $this->security->getUser();
        if($course->getEditor() !== $loggedUser){
            throw new AccessDeniedException("Cant edit not owned");
        }

        $educationEvent->setFunder($loggedUser);
        $educationEvent->setCourse($course);
    }

}
