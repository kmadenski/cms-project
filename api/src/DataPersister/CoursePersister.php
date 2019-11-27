<?php


namespace App\DataPersister;


use ApiPlatform\Core\DataPersister\ContextAwareDataPersisterInterface;
use App\Entity\Course;
use App\Entity\Person;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

class CoursePersister implements ContextAwareDataPersisterInterface
{
    /** @var TokenStorageInterface */
    private $tokenStorageInterface;

    /**
     * CoursePersister constructor.
     * @param TokenStorageInterface $tokenStorageInterface
     */
    public function __construct(TokenStorageInterface $tokenStorageInterface)
    {
        $this->tokenStorageInterface = $tokenStorageInterface;
    }


    public function supports($data, array $context = []): bool
    {
        return $data instanceof Course;
    }

    public function persist($data, array $context = [])
    {
        /** @var Person $loggedUser */
        $loggedUser = $this->tokenStorageInterface->getToken()->getUser();
        /** @var Course $course */
        $course = $data;
        if($course->hasEditor()){
            throw new \Exception("cos nie tak");
        }
        $course->setEditor($loggedUser);

        return $course;
    }

    public function remove($data, array $context = [])
    {
        return $data;
    }

}
