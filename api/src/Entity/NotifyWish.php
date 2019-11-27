<?php

declare(strict_types=1);

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * The most generic type of item.
 *
 * @see http://schema.org/Thing Documentation on Schema.org
 *
 * @ORM\Entity
 * @ApiResource(iri="http://schema.org/Thing",
 *     collectionOperations={"GET","POST"},
 *     itemOperations={"GET","PUT","DELETE"}
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
     */
    private $id;

    /**
     * @var Person
     *
     * @ORM\OneToOne(targetEntity="App\Entity\Person")
     * @ORM\JoinColumn(nullable=false)
     * @Assert\NotNull
     */
    private $funder;

    /**
     * @var Skill
     *
     * @ORM\OneToOne(targetEntity="App\Entity\Skill")
     * @ORM\JoinColumn(nullable=false)
     * @Assert\NotNull
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
