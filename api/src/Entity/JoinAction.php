<?php

declare(strict_types=1);

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiProperty;
use ApiPlatform\Core\Annotation\ApiResource;
use App\Enum\ActionStatusType;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * An agent joins an event/group with participants/friends at a location.\\n\\nRelated actions:\\n\\n\* \[\[RegisterAction\]\]: Unlike RegisterAction, JoinAction refers to joining a group/team of people.\\n\* \[\[SubscribeAction\]\]: Unlike SubscribeAction, JoinAction does not imply that you'll be receiving updates.\\n\* \[\[FollowAction\]\]: Unlike FollowAction, JoinAction does not imply that you'll be polling for updates.
 *
 * @see http://schema.org/JoinAction Documentation on Schema.org
 *
 * @ORM\Entity
 * @ApiResource(iri="http://schema.org/JoinAction",
 *     collectionOperations={
 *          "GET"={"security"="is_granted('ROLE_ADMIN') or is_granted('ROLE_USER')"},
 *          "POST"={"security"="is_granted('ROLE_ADMIN') or is_granted('ROLE_USER')"},
 *     },
 *     itemOperations={
 *          "GET"={"security"="is_granted('ROLE_ADMIN') or (is_granted('ROLE_USER') and object.course.funder == user) or (is_granted('ROLE_USER') and object.funder == user)"},
 *          "PUT"={"security"="is_granted('ROLE_ADMIN') or (is_granted('ROLE_USER') and object.course.funder == user)"},
 *          "DELETE"={"security"="is_granted('ROLE_ADMIN') or (is_granted('ROLE_USER') and object.course.funder == user) or (is_granted('ROLE_USER') and object.funder == user)"},
 *     }
 * )
 */
class JoinAction
{
    /**
     * @var int|null
     *
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @var string|null indicates the current disposition of the Action
     *
     * @ORM\Column(nullable=true)
     * @ApiProperty(iri="http://schema.org/actionStatus")
     * @Assert\Choice(callback={"App\Enum\ActionStatusType", "toArray"})
     * @Groups({"anonymous:input","admin:output","admin:input","user:output","user:input"})
     */
    private $actionStatus;

    /**
     * @var Course|null
     *
     * @ORM\ManyToOne(targetEntity="App\Entity\Course")
     * @ORM\JoinColumn(nullable=false)
     * @Groups({"anonymous:input","admin:output","admin:input","user:output","user:input"})
     * @ApiProperty(attributes={"fetchEager": false})
     */
    private $course;
    /**
     * @var Person
     *
     * @ORM\ManyToOne(targetEntity="App\Entity\Person")
     * @ORM\JoinColumn(nullable=false)
     * @Assert\NotNull
     * @Groups({"admin:output","admin:input","user:output"})
     * @ApiProperty(attributes={"fetchEager": false})
     */
    private $funder;


    public function getId(): ?int
    {
        return $this->id;
    }

    public function setActionStatus(?string $actionStatus): void
    {
        $this->actionStatus = $actionStatus;
    }

    public function getActionStatus(): ?string
    {
        return $this->actionStatus;
    }

    public function setCourse(?Course $course): void
    {
        $this->course = $course;
    }

    public function getCourse(): ?Course
    {
        return $this->course;
    }

    /**
     * @return Person
     */
    public function getFunder(): Person
    {
        return $this->funder;
    }
    /**
     * @param Person $funder
     */
    public function setFunder(Person $funder): void
    {
        $this->funder = $funder;
    }


}
