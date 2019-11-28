<?php

declare(strict_types=1);

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiProperty;
use ApiPlatform\Core\Annotation\ApiResource;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * A person (alive, dead, undead, or fictional).
 *
 * @see http://schema.org/Person Documentation on Schema.org
 *
 * @ORM\Entity
 * @ApiResource(iri="http://schema.org/Person",
 *     collectionOperations={
 *          "GET"={"security"="is_granted('ROLE_ADMIN')"},
 *          "POST"={}
 *     },
 *     itemOperations={
 *          "GET"={"security"="is_granted('ROLE_ADMIN') or (is_granted('ROLE_USER') and object == user)"},
 *          "PUT"={"security"="is_granted('ROLE_ADMIN') or (is_granted('ROLE_USER') and object == user)"},
 *          "DELETE"={"security"="is_granted('ROLE_ADMIN')"}
 *     }
 * )
 */
class Person implements UserInterface
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
     * @var Collection<EducationalOrganization>|null an organization that the person is an alumni of
     *
     * @ORM\ManyToMany(targetEntity="App\Entity\EducationalOrganization")
     * @ORM\JoinTable(inverseJoinColumns={@ORM\JoinColumn(unique=true)})
     * @ApiProperty(iri="http://schema.org/alumniOf")
     * @Groups({"admin:output","admin:input","user:output","user:input"})
     */
    private $alumniOfs;

    /**
     * @var \DateTimeInterface|null date of birth
     *
     * @ORM\Column(type="date", nullable=true)
     * @ApiProperty(iri="http://schema.org/birthDate")
     * @Assert\Date
     * @Groups({"admin:output","admin:input","anonymous:input","user:output","user:input"})
     */
    private $birthDate;

    /**
     * @var Collection<Person>|null a colleague of the person
     *
     * @ORM\ManyToMany(targetEntity="App\Entity\Person")
     * @ORM\JoinTable(inverseJoinColumns={@ORM\JoinColumn(unique=true)})
     * @ApiProperty(iri="http://schema.org/colleague")
     * @Groups({"admin:output","admin:input","user:output","user:input"})
     */
    private $colleagues;

    /**
     * @var string|null email address
     *
     * @ORM\Column(type="text", nullable=false)
     * @ApiProperty(iri="http://schema.org/email")
     * @Assert\Email
     * @Groups({"anonymous:input","admin:output","admin:input","user:output","user:input"})
     */
    private $email;
    /**
     * @var string|null password
     *
     * @ORM\Column(type="text", nullable=false)
     * @Groups({"anonymous:input","user:input"})
     */
    private $password;
    /**
     * @var Person|null a person or organization that supports (sponsors) something through some kind of financial contribution
     *
     * @ORM\ManyToOne(targetEntity="App\Entity\Person")
     * @ApiProperty(iri="http://schema.org/funder")
     * @Groups({"anonymous:input","admin:output","admin:input"})
     */
    private $funder;

    /**
     * @var string|null Gender of the person. While http://schema.org/Male and http://schema.org/Female may be used, text strings are also acceptable for people who do not identify as a binary gender.
     *
     * @ORM\Column(type="text", nullable=true)
     * @ApiProperty(iri="http://schema.org/gender")
     * @Groups({"anonymous:input","admin:output","admin:input","user:output","user:input"})
     */
    private $gender;

    /**
     * @var string|null the name of the item
     *
     * @ORM\Column(type="text", nullable=true)
     * @ApiProperty(iri="http://schema.org/name")
     * @Groups({"anonymous:input","admin:output","admin:input","user:output","user:input"})
     */
    private $name;

    /**
     * @var Collection<Skill>|null
     *
     * @ORM\ManyToMany(targetEntity="App\Entity\Skill")
     * @ORM\JoinTable(inverseJoinColumns={@ORM\JoinColumn(unique=true)})
     * @Groups({"anonymous:input","admin:output","admin:input","user:output","user:input"})
     */
    private $skills;

    /**
     * @var Collection<Language>|null
     *
     * @ORM\ManyToMany(targetEntity="App\Entity\Language")
     * @ORM\JoinTable(inverseJoinColumns={@ORM\JoinColumn(unique=true)})
     * @Groups({"anonymous:input","admin:output","admin:input","user:output","user:input"})
     */
    private $knowsLanguages;
    /**
     * @var Collection<Role>|null
     *
     * @ORM\ManyToMany(targetEntity="App\Entity\Role")
     * @ORM\JoinTable(inverseJoinColumns={@ORM\JoinColumn(unique=false)})
     * @Groups({"admin:output","admin:input"})
     */
    private $userRoles;
    /**
     * @var string|null the telephone number
     *
     * @ORM\Column(type="text", nullable=true)
     * @ApiProperty(iri="http://schema.org/telephone")
     * @Groups({"anonymous:input","admin:output","admin:input","user:output","user:input"})
     */
    private $telephone;

    /**
     * @var string|null a description of the item
     *
     * @ORM\Column(type="text", nullable=true)
     * @ApiProperty(iri="http://schema.org/description")
     * @Groups({"anonymous:input","admin:output","admin:input","user:output","user:input"})
     */
    private $description;

    /**
     * @var string|null An image of the item. This can be a \[\[URL\]\] or a fully described \[\[ImageObject\]\].
     *
     * @ORM\Column(type="text", nullable=true)
     * @ApiProperty(iri="http://schema.org/image")
     * @Assert\Url
     * @todo uzupełnić
     */
    private $image;

    public function __construct()
    {
        $this->alumniOfs = new ArrayCollection();
        $this->colleagues = new ArrayCollection();
        $this->skills = new ArrayCollection();
        $this->knowsLanguages = new ArrayCollection();
        $this->roles = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function addAlumniOf(EducationalOrganization $alumniOf): void
    {
        $this->alumniOfs[] = $alumniOf;
    }

    public function removeAlumniOf(EducationalOrganization $alumniOf): void
    {
        $this->alumniOfs->removeElement($alumniOf);
    }

    public function getAlumniOfs(): Collection
    {
        return $this->alumniOfs;
    }

    public function setBirthDate(?\DateTimeInterface $birthDate): void
    {
        $this->birthDate = $birthDate;
    }

    public function getBirthDate(): ?\DateTimeInterface
    {
        return $this->birthDate;
    }

    public function addColleague(Person $colleague): void
    {
        $this->colleagues[] = $colleague;
    }

    public function removeColleague(Person $colleague): void
    {
        $this->colleagues->removeElement($colleague);
    }

    public function getColleagues(): Collection
    {
        return $this->colleagues;
    }

    public function setEmail(?string $email): void
    {
        $this->email = $email;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    /**
     * @return string|null
     */
    public function getPassword(): ?string
    {
        return $this->password;
    }

    /**
     * @param string|null $password
     */
    public function setPassword(?string $password): void
    {
        $this->password = $password;
    }

    public function setFunder(?Person $funder): void
    {
        $this->funder = $funder;
    }

    public function getFunder(): ?Person
    {
        return $this->funder;
    }

    public function setGender(?string $gender): void
    {
        $this->gender = $gender;
    }

    public function getGender(): ?string
    {
        return $this->gender;
    }

    public function setName(?string $name): void
    {
        $this->name = $name;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function addSkill(Skill $skill): void
    {
        $this->skills[] = $skill;
    }

    public function removeSkill(Skill $skill): void
    {
        $this->skills->removeElement($skill);
    }

    public function getSkills(): Collection
    {
        return $this->skills;
    }

    public function addKnowsLanguage(Language $knowsLanguage): void
    {
        $this->knowsLanguages[] = $knowsLanguage;
    }

    public function removeKnowsLanguage(Language $knowsLanguage): void
    {
        $this->knowsLanguages->removeElement($knowsLanguage);
    }

    public function getKnowsLanguages(): Collection
    {
        return $this->knowsLanguages;
    }

    public function setTelephone(?string $telephone): void
    {
        $this->telephone = $telephone;
    }

    public function getTelephone(): ?string
    {
        return $this->telephone;
    }

    public function setDescription(?string $description): void
    {
        $this->description = $description;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setImage(?string $image): void
    {
        $this->image = $image;
    }

    public function getImage(): ?string
    {
        return $this->image;
    }
    public function addUserRole(Role $role):  void {
        $this->userRoles[] = $role;
    }
    public function removeUserRole(Role $role): void
    {
        $this->userRoles->removeElement($role);
    }
    public function getUserRoles()
    {
        return $this->userRoles->getValues();
    }
    public function getRoles(){
        return array_map(function (Role $role){
            return $role->getName();
        }, $this->userRoles->toArray());
    }
    public function getSalt()
    {
        return "";
    }

    public function getUsername()
    {
        return $this->email;
    }

    public function eraseCredentials()
    {
        // TODO: Implement eraseCredentials() method.
    }


}
