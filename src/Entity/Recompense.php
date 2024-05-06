<?php

namespace App\Entity;

use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Recompense
 *
 * @ORM\Table(name="recompense")
 * @ORM\Entity(repositoryClass=App\Repository\RecompenseRepository::class)
 */
class Recompense
{
    /**
     * @ORM\Column(name="id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;

    /**
     * @ORM\Column(name="equipeGagnante", type="string", length=150, nullable=false)
     * @Assert\NotBlank(message="The winning team name must not be blank.")
     * @Assert\Length(
     *      max = 150,
     *      maxMessage = "The winning team name cannot be longer than {{ limit }} characters."
     * )
     */
    private $equipegagnante;

    /**
     * @ORM\Column(name="TypeRecompense", type="string", length=50, nullable=false)
     * @Assert\NotBlank(message="The type of reward must not be blank.")
     * @Assert\Length(
     *      max = 50,
     *      maxMessage = "The type of reward cannot be longer than {{ limit }} characters."
     * )
     */
    private $typerecompense;

    /**
     * @ORM\Column(name="dateRecompense", type="date", nullable=false)
     * @Assert\NotNull(message="The date of the reward must not be null.")
     * @Assert\Type(
     *      type = "\DateTime",
     *      message = "The value {{ value }} is not a valid date."
     * )
     */
    private $daterecompense;

    

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getEquipegagnante(): ?string
    {
        return $this->equipegagnante;
    }

    public function setEquipegagnante(string $equipegagnante): static
    {
        $this->equipegagnante = $equipegagnante;

        return $this;
    }

    public function getTyperecompense(): ?string
    {
        return $this->typerecompense;
    }

    public function setTyperecompense(string $typerecompense): static
    {
        $this->typerecompense = $typerecompense;

        return $this;
    }

    public function getDaterecompense(): ?\DateTimeInterface
    {
        return $this->daterecompense;
    }

    public function setDaterecompense(\DateTimeInterface $daterecompense): static
    {
        $this->daterecompense = $daterecompense;

        return $this;
    }

   
    public function __toString(): string
    {
        return $this->typerecompense;
    }

}
