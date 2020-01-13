<?php


namespace App\Controller\Person;


use ApiPlatform\Core\Validator\ValidatorInterface;
use App\Entity\JoinAction;
use App\Entity\Person;
use App\Entity\Role;
use App\Enum\ActionStatusType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Security\Core\Encoder\PasswordEncoderInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class ChangePasswordAction
{
    /** @var EntityManagerInterface */
    private $em;
    /** @var UserPasswordEncoderInterface */
    private $passwordEncoder;

    /**
     * ChangePasswordAction constructor.
     * @param EntityManagerInterface $em
     * @param UserPasswordEncoderInterface $passwordEncoder
     */
    public function __construct(EntityManagerInterface $em, UserPasswordEncoderInterface $passwordEncoder)
    {
        $this->em = $em;
        $this->passwordEncoder = $passwordEncoder;
    }

    public function __invoke(Person $data): Person
    {
        $person = $data;
        $encoded = $this->passwordEncoder->encodePassword($person, $person->getPlainPassword());
        $person->setPassword($encoded);

        return $data;
    }
}
