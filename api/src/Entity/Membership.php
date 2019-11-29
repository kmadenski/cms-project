<?php


namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiProperty;
use ApiPlatform\Core\Annotation\ApiResource;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * An agent joins an event/group with participants/friends at a location.\\n\\nRelated actions:\\n\\n\* \[\[RegisterAction\]\]: Unlike RegisterAction, JoinAction refers to joining a group/team of people.\\n\* \[\[SubscribeAction\]\]: Unlike SubscribeAction, JoinAction does not imply that you'll be receiving updates.\\n\* \[\[FollowAction\]\]: Unlike FollowAction, JoinAction does not imply that you'll be polling for updates.
 *
 * @see http://schema.org/JoinAction Documentation on Schema.org
 *
 * @ORM\Entity()
 * @ApiResource(iri="http://schema.org/ProgramMembership",
 *     collectionOperations={"GET","POST"},
 *     itemOperations={"GET","DELETE"}
 * )
 */
class Membership
{
    /**
     * @var int|null
     *
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     * @ORM\Column(type="integer")
     * @Groups({"user:action.accept"})
     */
    private $id;

    /**
     * @var Course|null
     *
     * @ORM\ManyToOne(targetEntity="App\Entity\Course")
     * @ORM\JoinColumn(nullable=false)
     * @Assert\NotNull()
     * @Groups({"user:action.accept"})
     */
    private $course;

    /**
     * @var Person
     *
     * @ORM\ManyToOne(targetEntity="App\Entity\Person")
     * @ApiProperty(iri="http://schema.org/member")
     * @Assert\NotNull()
     */
    private $member;

    /**
     * Membership constructor.
     * @param Course|null $course
     * @param Person $member
     */
    public function __construct(?Course $course, Person $member)
    {
        $this->course = $course;
        $this->member = $member;
    }

    /**
     * @return int|null
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @return Course|null
     */
    public function getCourse(): ?Course
    {
        return $this->course;
    }

    /**
     * @return Person
     */
    public function getMember(): Person
    {
        return $this->member;
    }
}
