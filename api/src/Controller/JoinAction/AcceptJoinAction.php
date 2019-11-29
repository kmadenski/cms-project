<?php


namespace App\Controller\JoinAction;


use App\Entity\JoinAction;
use App\Entity\Membership;
use App\Enum\ActionStatusType;
use Doctrine\ORM\EntityManagerInterface;

class AcceptJoinAction
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

    public function __invoke(JoinAction $data): Membership
    {
        $funder = $data->getFunder();
        $course = $data->getCourse();

        $data->setActionStatus(ActionStatusType::COMPLETED_ACTION_STATUS());
        $membership = new Membership($course, $funder);
        $this->em->persist($membership);
        $this->em->flush();

        return $membership;
    }
}
