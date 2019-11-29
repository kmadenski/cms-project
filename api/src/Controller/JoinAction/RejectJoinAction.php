<?php


namespace App\Controller\JoinAction;


use App\Entity\JoinAction;
use App\Entity\Membership;
use App\Enum\ActionStatusType;
use Doctrine\ORM\EntityManagerInterface;

class RejectJoinAction
{
    /** @var EntityManagerInterface */
    private $em;

    /**
     * AcceptJoinAction constructor.
     * @param EntityManagerInterface $em
     */
    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    public function __invoke(JoinAction $data): JoinAction
    {
        $data->setActionStatus(ActionStatusType::FAILED_ACTION_STATUS());
        $this->em->flush();

        return $data;
    }
}
