<?php


namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiProperty;
use ApiPlatform\Core\Annotation\ApiResource;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 *
 * @ORM\Entity()
 * @ApiResource(
 *     collectionOperations={"GET","POST"},
 *     itemOperations={"GET","DELETE"}
 * )
 */
class Attendee
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
     * @var Course|null
     *
     * @ORM\ManyToOne(targetEntity="App\Entity\EducationEvent")
     * @ORM\JoinColumn(nullable=false)
     */
    private $event;

    /**
     * @var Person
     *
     * @ORM\OneToOne(targetEntity="App\Entity\Person")
     * @ORM\JoinColumn(nullable=false)
     * @ApiProperty(iri="http://schema.org/member")
     * @Assert\NotNull()
     */
    private $person;
}
