<?php


namespace App\EventSubscriber;


use ApiPlatform\Core\EventListener\EventPriorities;
use App\Entity\Course;
use App\Entity\Person;
use App\Entity\Role;
use Doctrine\DBAL\Schema\View;
use Doctrine\ORM\EntityManagerInterface;
use Lexik\Bundle\JWTAuthenticationBundle\Security\Http\Authentication\AuthenticationSuccessHandler;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Event\ViewEvent;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Core\Security;

class CreatePersonSubscriber implements EventSubscriberInterface
{
    /** @var Security */
    private $security;
    /** @var UserPasswordEncoderInterface */
    private $passwordEncoder;
    /** @var AuthenticationSuccessHandler */
    private $authenticationSuccessHandler;
    /** @var EntityManagerInterface */
    private $em;

    /**
     * CreatePersonSubscriber constructor.
     * @param Security $security
     * @param UserPasswordEncoderInterface $passwordEncoder
     * @param AuthenticationSuccessHandler $authenticationSuccessHandler
     * @param EntityManagerInterface $em
     */
    public function __construct(Security $security, UserPasswordEncoderInterface $passwordEncoder, AuthenticationSuccessHandler $authenticationSuccessHandler, EntityManagerInterface $em)
    {
        $this->security = $security;
        $this->passwordEncoder = $passwordEncoder;
        $this->authenticationSuccessHandler = $authenticationSuccessHandler;
        $this->em = $em;
    }


    public static function getSubscribedEvents()
    {
        return [
            KernelEvents::VIEW => [
                'registerPerson', EventPriorities::POST_VALIDATE,
                'test', EventPriorities::PRE_WRITE
            ],
        ];
    }
    public function test(ViewEvent $event){
        print_r(":wadawd");exit;
    }
    public function registerPerson(ViewEvent $event)
    {
        /** @var Person|null $person */
        $person = $event->getControllerResult();
        $method = $event->getRequest()->getMethod();

        if (!$person instanceof Person || Request::METHOD_POST !== $method || $this->security->isGranted('ROLE_ADMIN')) {
            return;
        }

        $encoded = $this->passwordEncoder->encodePassword($person, $person->getPlainPassword());
        $person->setPassword($encoded);
        $roleRepository = $this->em->getRepository(Role::class);

        /** @var Role|null $role */
        $role = $roleRepository->findOneBy(['name' => Role::ROLE_USER]);

        if(!$role){
            throw new \Exception("No ROLE_USER in db");
        }

        $person->addUserRole($role);
    }
}
