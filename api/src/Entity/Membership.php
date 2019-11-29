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
 *     collectionOperations={
 *          "GET"={"security"="is_granted('ROLE_ADMIN') or (is_granted('ROLE_USER'))"},
 *          "POST"={"security"="is_granted('ROLE_ADMIN') or (is_granted('ROLE_USER'))"},
 *     },
 *     itemOperations={
 *          "GET"={"security"="is_granted('ROLE_ADMIN') or (is_granted('ROLE_USER') and object.getMember() == user) or (is_granted('ROLE_USER') and object.getCourse().getFunder() == user)"},
 *          "PUT"={"security"="is_granted('ROLE_ADMIN')"},
 *          "DELETE"={"security"="is_granted('ROLE_ADMIN') or (is_granted('ROLE_USER') and object.getMember() == user) or (is_granted('ROLE_USER') and object.getCourse().getFunder() == user)"},
 *     }
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
     * @Groups({"user:action.accept","admin:output"})
     */
    private $id;

    /**
     * @var Course|null
     *
     * @ORM\ManyToOne(targetEntity="App\Entity\Course")
     * @ORM\JoinColumn(nullable=false)
     * @Assert\NotNull()
     * @ApiProperty(attributes={"fetchEager": false})
     * @Groups({"user:action.accept","admin:output","admin:input","user:input","user:output"})
     */
    private $course;

    /**
     * @var Person
     *
     * @ORM\ManyToOne(targetEntity="App\Entity\Person")
     * @ApiProperty(iri="http://schema.org/member",attributes={"fetchEager": false})
     * @Groups({"admin:output","admin:input","user:input","user:output"})
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

    /**
     * @param Course|null $course
     */
    public function setCourse(?Course $course): void
    {
        $this->course = $course;
    }

    /**
     * @param Person $member
     */
    public function setMember(Person $member): void
    {
        $this->member = $member;
    }


}
