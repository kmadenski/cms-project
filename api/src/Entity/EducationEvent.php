<?php

declare(strict_types=1);

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiProperty;
use ApiPlatform\Core\Annotation\ApiResource;
use ApiPlatform\Core\Annotation\ApiSubresource;
use App\Enum\EventStatusType;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Event type: Education event.
 *
 * @see http://schema.org/EducationEvent Documentation on Schema.org
 *
 * @ORM\Entity
 * @ApiResource(iri="http://schema.org/EducationEvent",
 *     collectionOperations={
 *          "GET"={"security"="is_granted('ROLE_ADMIN') or is_granted('ROLE_USER')"},
 *          "POST"={"security"="is_granted('ROLE_ADMIN') or (is_granted('ROLE_USER'))"},
 *     },
 *     itemOperations={
 *          "GET"={"security"="is_granted('ROLE_ADMIN') or is_granted('ROLE_USER')"},
 *          "PUT"={"security"="is_granted('ROLE_ADMIN') or (is_granted('ROLE_USER') and object.funder == user)"},
 *          "DELETE"={"security"="is_granted('ROLE_ADMIN') or (is_granted('ROLE_USER') and object.funder == user)"},
 *     }
 * )
 */
class EducationEvent
{
    /**
     * @var int|null
     *
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     * @ORM\Column(type="integer")
     * @Groups({"admin:output","user:output"})
     */
    private $id;

    /**
     * @var Collection<Skill> the subject matter of the content
     *
     * @ORM\ManyToMany(targetEntity="App\Entity\Skill")
     * @ORM\JoinTable(inverseJoinColumns={@ORM\JoinColumn(nullable=false, unique=true)})
     * @ApiProperty(iri="http://schema.org/about")
     * @Assert\NotNull
     * @Groups({"admin:output","admin:input","user:output","user:input"})
     */
    private $abouts;
    /**
     * @var Collection<Person>|null a person or organization attending the event
     *
     * @ORM\OneToMany(targetEntity="App\Entity\Attendee", mappedBy="event")
     * @ApiProperty(iri="http://schema.org/attendee")
     * @ApiSubresource()
     */
    private $attendees;

    /**
     * @var string|null an eventStatus of an event represents its status; particularly useful when an event is cancelled or rescheduled
     *
     * @ORM\Column(nullable=true)
     * @ApiProperty(iri="http://schema.org/eventStatus")
     * @Assert\Choice(callback={"EventStatusType", "toArray"})
     * @Groups({"admin:output","admin:input","user:output","user:input"})
     */
    private $eventStatus;

    /**
     * @var \DateTimeInterface|null the time admission will commence
     *
     * @ORM\Column(type="datetime", nullable=true)
     * @ApiProperty(iri="http://schema.org/doorTime")
     * @Assert\DateTime
     * @Groups({"admin:output","admin:input","user:output","user:input"})
     */
    private $doorTime;

    /**
     * @var \DateTimeInterface|null The end date and time of the item (in \[ISO 8601 date format\](http://en.wikipedia.org/wiki/ISO\_8601)).
     *
     * @ORM\Column(type="date", nullable=true)
     * @ApiProperty(iri="http://schema.org/endDate")
     * @Assert\Date
     * @Groups({"admin:output","admin:input","user:output","user:input"})
     */
    private $endDate;

    /**
     * @var Person|null a person or organization that supports (sponsors) something through some kind of financial contribution
     *
     * @ORM\ManyToOne(targetEntity="App\Entity\Person")
     * @ApiProperty(iri="http://schema.org/funder")
     * @Groups({"admin:output","admin:input","user:output"})
     */
    private $funder;

    /**
     * @var int|null the total number of individuals that may attend an event or venue
     *
     * @ORM\Column(type="integer", nullable=true)
     * @ApiProperty(iri="http://schema.org/maximumAttendeeCapacity")
     * @Groups({"admin:output","admin:input","user:output","user:input"})
     */
    private $maximumAttendeeCapacity;
    /**
     * @var Course|null
     *
     * @ORM\ManyToOne(targetEntity="App\Entity\Course")
     * @ORM\JoinColumn(nullable=false)
     * @Assert\NotNull
     * @Groups({"admin:output","admin:input","user:output","user:input"})
     */
    private $course;

    public function __construct()
    {
        $this->abouts = new ArrayCollection();
        $this->attendees = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function addAbout(Skill $about): void
    {
        $this->abouts[] = $about;
    }

    public function removeAbout(Skill $about): void
    {
        $this->abouts->removeElement($about);
    }

    public function getAbouts(): Collection
    {
        return $this->abouts;
    }

    public function addAttendee(Person $attendee): void
    {
        $this->attendees[] = $attendee;
    }

    public function removeAttendee(Person $attendee): void
    {
        $this->attendees->removeElement($attendee);
    }

    public function getAttendees(): Collection
    {
        return $this->attendees;
    }

    public function setEventStatus(?string $eventStatus): void
    {
        $this->eventStatus = $eventStatus;
    }

    public function getEventStatus(): ?string
    {
        return $this->eventStatus;
    }

    public function setDoorTime(?\DateTimeInterface $doorTime): void
    {
        $this->doorTime = $doorTime;
    }

    public function getDoorTime(): ?\DateTimeInterface
    {
        return $this->doorTime;
    }

    public function setEndDate(?\DateTimeInterface $endDate): void
    {
        $this->endDate = $endDate;
    }

    public function getEndDate(): ?\DateTimeInterface
    {
        return $this->endDate;
    }

    public function setFunder(?Person $funder): void
    {
        $this->funder = $funder;
    }

    public function getFunder(): ?Person
    {
        return $this->funder;
    }

    public function setMaximumAttendeeCapacity(?int $maximumAttendeeCapacity): void
    {
        $this->maximumAttendeeCapacity = $maximumAttendeeCapacity;
    }

    public function getMaximumAttendeeCapacity(): ?int
    {
        return $this->maximumAttendeeCapacity;
    }

    public function setCourse(?Course $course): void
    {
        $this->course = $course;
    }

    public function getCourse(): ?Course
    {
        return $this->course;
    }
}
