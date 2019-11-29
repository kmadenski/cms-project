<?php


namespace App\EventSubscriber;


use ApiPlatform\Core\EventListener\EventPriorities;
use App\Entity\Course;
use App\Entity\EducationEvent;
use App\Entity\Membership;
use App\Entity\Person;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Event\ViewEvent;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Symfony\Component\Security\Core\Security;

class CreateMembershipSubscriber implements EventSubscriberInterface
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
            KernelEvents::VIEW => ['addMember', EventPriorities::PRE_VALIDATE]
        ];
    }

    public function addMember(ViewEvent $event)
    {
        /** @var Membership|null $membership */
        $membership = $event->getControllerResult();
        $method = $event->getRequest()->getMethod();
        if (!$membership instanceof Membership || Request::METHOD_POST !== $method || $this->security->isGranted('ROLE_ADMIN')) {
            return;
        }

        /** @var Course|null $course */
        $course = $membership->getCourse();
        /** @var Person $loggedUser */
        $loggedUser = $this->security->getUser();

        $courseExist = $this->em->getRepository(Membership::class)->findOneBy(['course' => $course, 'member' => $loggedUser]);

        if ($courseExist) {
            throw new AccessDeniedException("You are member of this course.");
        }

        if (!$course) {
            return;
        }

        if (!$course->isPublic()) {
            throw new AccessDeniedException("This course is public. Try join request.");
        }


        if ($membership->getMember() !== $loggedUser) {
            throw new AccessDeniedException("This member is not you");
        }
    }
}
