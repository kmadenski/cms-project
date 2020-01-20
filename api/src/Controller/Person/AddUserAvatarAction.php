<?php


namespace App\Controller\Person;


use App\Entity\Person;
use App\Entity\UserAvatar;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\Security\Core\Security;

class AddUserAvatarAction
{
    /** @var Security */
    private $security;

    /**
     * AddUserAvatarAction constructor.
     * @param Security $security
     */
    public function __construct(Security $security)
    {
        $this->security = $security;
    }


    public function __invoke(Request $request): UserAvatar
    {
        $uploadedFile = $request->files->get('file');
        if (!$uploadedFile) {
            throw new BadRequestHttpException('"file" is required');
        }

        $mediaObject = new UserAvatar();
        $mediaObject->file = $uploadedFile;
        /** @var Person $user */
        $user = $this->security->getUser();
        $user->setImage($mediaObject);

        return $mediaObject;
    }
}
