<?php

declare(strict_types=1);

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * The most generic type of item.
 *
 * @see http://schema.org/Thing Documentation on Schema.org
 *
 * @ORM\Entity
 * @ApiResource(iri="http://schema.org/Thing",
 *     collectionOperations={
 *          "GET"={"security"="is_granted('ROLE_ADMIN') or is_granted('ROLE_USER')"},
 *          "POST"={"security"="is_granted('ROLE_ADMIN') or is_granted('ROLE_USER')"},
 *     },
 *     itemOperations={
 *          "GET"={"security"="is_granted('ROLE_ADMIN') or (is_granted('ROLE_USER') and object.getFunder() == user)"},
 *          "DELETE"={"security"="is_granted('ROLE_ADMIN') or (is_granted('ROLE_USER') and object.getFunder() == user)"},
 *     }
 * )
 */
class NotifyWish
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
     * @var Person
     *
     * @ORM\OneToOne(targetEntity="App\Entity\Person")
     * @ORM\JoinColumn(nullable=false)
     * @Assert\NotNull
     * @Groups({"admin:output","admin:input"})
     */
    private $funder;

    /**
     * @var Skill
     *
     * @ORM\OneToOne(targetEntity="App\Entity\Skill")
     * @ORM\JoinColumn(nullable=false)
     * @Assert\NotNull
     * @Groups({"admin:output","admin:input","user:output","user:input"})
     */
    private $skill;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function setFunder(Person $funder): void
    {
        $this->funder = $funder;
    }

    /**
     * @return Person
     */
    public function getFunder(): Person
    {
        return $this->funder;
    }



    public function setSkill(Skill $skill): void
    {
        $this->skill = $skill;
    }

    public function getSkill(): Skill
    {
        return $this->skill;
    }
}
