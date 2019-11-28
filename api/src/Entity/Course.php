<?php

declare(strict_types=1);

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiFilter;
use ApiPlatform\Core\Annotation\ApiProperty;
use ApiPlatform\Core\Annotation\ApiResource;
use ApiPlatform\Core\Annotation\ApiSubresource;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\SearchFilter;

/**
 * A description of an educational course which may be offered as distinct instances at which take place at different times or take place at different locations, or be offered through different media or modes of study. An educational course is a sequence of one or more educational events and/or creative works which aims to build knowledge, competence or ability of learners.
 *
 * @see http://schema.org/Course Documentation on Schema.org
 *
 * @ORM\Entity
 * @ApiResource(iri="http://schema.org/Course",
 *     collectionOperations={
 *          "GET"={"security"="is_granted('ROLE_ADMIN') or is_granted('ROLE_USER')"},
 *          "POST"={"security"="is_granted('ROLE_ADMIN') or is_granted('ROLE_USER')"},
 *     },
 *     itemOperations={
 *          "GET"={"security"="is_granted('ROLE_ADMIN') or is_granted('ROLE_USER')"},
 *          "PUT"={"security"="is_granted('ROLE_ADMIN') or (is_granted('ROLE_USER') and and object.editor == user)"},
 *          "DELETE"={"security"="is_granted('ROLE_ADMIN') or (is_granted('ROLE_USER') and and object.editor == user)"},
 *     }
 * )
 * @ApiFilter(SearchFilter::class, properties={
 *     "abouts.name": "partial",
 *     "abstract": "partial",
 *     "editor.id": "exact",
 *     "isPrivate": "exact"
 * })
 */
class Course
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
     * @Groups({"admin:output","admin:input","user:output","user:input"})
     */
    private $abouts;

    /**
     * @var string
     *
     * @ORM\Column(type="text")
     * @Assert\NotNull
     * @Groups({"admin:output","admin:input","user:output","user:input"})
     */
    private $abstract;

    /**
     * @var Person specifies the Person who edited the CreativeWork
     *
     * @ORM\OneToOne(targetEntity="App\Entity\Person")
     * @ORM\JoinColumn(nullable=false)
     * @ApiProperty(iri="http://schema.org/editor")
     * @Groups({"admin:output","admin:input","user:output"})
     * @Assert\NotNull
     */
    private $editor;

    /**
     * @var Collection<Person>|null a secondary contributor to the CreativeWork or Event
     *
     * @ORM\ManyToMany(targetEntity="App\Entity\Person")
     * @ORM\JoinTable(inverseJoinColumns={@ORM\JoinColumn(unique=true)})
     * @ApiProperty(iri="http://schema.org/contributor")
     * @ApiSubresource()
     * @Groups({"admin:output","admin:input","user:output"})
     */
    private $contributors;
    /**
     * @var Collection<Membership>|null
     * @ORM\OneToMany(targetEntity="App\Entity\Membership", mappedBy="course")
     * @ApiSubresource()
     * @Groups({"admin:output","admin:input","user:output"})
     */
    private $members;
    /**
     * @var int
     *
     * @ORM\Column(type="integer")
     * @Groups({"admin:output","admin:input","user:output","user:input"})
     * @Assert\NotNull
     */
    private $minimumAttendeeCapacity;

    /**
     * @var int
     *
     * @ORM\Column(type="integer")
     * @Groups({"admin:output","admin:input","user:output","user:input"})
     * @Assert\NotNull
     */
    private $maximumAttendeeCapacity;

    /**
     * @var bool
     *
     * @ORM\Column(type="boolean")
     * @Groups({"admin:output","admin:input","user:output","user:input"})
     * @Assert\NotNull
     */
    private $isPrivate;

    /**
     * @var \DateTimeInterface|null the date on which the CreativeWork was created or the item was added to a DataFeed
     *
     * @ORM\Column(type="date", nullable=true)
     * @ApiProperty(iri="http://schema.org/dateCreated")
     * @Groups({"admin:output","admin:input","user:output"})
     */
    private $dateCreated;

    /**
     * @var Collection<JoinAction>|null
     * @ORM\OneToMany(targetEntity="App\Entity\JoinAction", mappedBy="course")
     * @ApiSubresource()
     * @Groups({"admin:output","admin:input","user:output"})
     */
    private $joins;
    /**
     * @var Collection<EducationEvent>|null
     * @ORM\OneToMany(targetEntity="App\Entity\EducationEvent", mappedBy="course")
     * @ApiSubresource(maxDepth=1)
     * @ApiProperty(attributes={"fetchEager": false})
     * @Groups({"admin:output","admin:input","user:output"})
     */
    private $educationEvents;

    public function __construct()
    {
        $this->dateCreated = new \DateTime();
        $this->abouts = new ArrayCollection();
        $this->contributors = new ArrayCollection();
        $this->educationEvents = new ArrayCollection();
        $this->members = new ArrayCollection();
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

    public function setAbstract(string $abstract): void
    {
        $this->abstract = $abstract;
    }

    public function getAbstract(): string
    {
        return $this->abstract;
    }

    public function setEditor(Person $editor): void
    {
        $this->editor = $editor;
    }

    public function getEditor(): Person
    {
        return $this->editor;
    }

    public function addContributor(Person $contributor): void
    {
        $this->contributors[] = $contributor;
    }

    public function removeContributor(Person $contributor): void
    {
        $this->contributors->removeElement($contributor);
    }

    public function getContributors(): Collection
    {
        return $this->contributors;
    }
    public function getMembers(): Collection
    {
        return $this->members;
    }
    public function setMinimumAttendeeCapacity(int $minimumAttendeeCapacity): void
    {
        $this->minimumAttendeeCapacity = $minimumAttendeeCapacity;
    }

    public function getMinimumAttendeeCapacity(): int
    {
        return $this->minimumAttendeeCapacity;
    }

    public function setMaximumAttendeeCapacity(int $maximumAttendeeCapacity): void
    {
        $this->maximumAttendeeCapacity = $maximumAttendeeCapacity;
    }

    public function getMaximumAttendeeCapacity(): int
    {
        return $this->maximumAttendeeCapacity;
    }

    public function setIsPrivate(bool $isPrivate): void
    {
        $this->isPrivate = $isPrivate;
    }

    public function getIsPrivate(): bool
    {
        return $this->isPrivate;
    }

    public function setDateCreated(?\DateTimeInterface $dateCreated): void
    {
        $this->dateCreated = $dateCreated;
    }

    public function addJoin(JoinAction $contributor): void
    {
        $this->joins[] = $contributor;
    }

    public function removeJoin(JoinAction $contributor): void
    {
        $this->joins->removeElement($contributor);
    }

    public function getJoins(): Collection
    {
        return $this->joins;
    }

    public function getEducationEvents(): Collection
    {
        return $this->educationEvents;
    }

}
