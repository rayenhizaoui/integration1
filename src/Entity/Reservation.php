<?php

namespace App\Entity;

use App\Repository\ReservationRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * @ORM\Entity(repositoryClass=ReservationRepository::class)
 * @UniqueEntity(fields={"nom"}, message="This reservation name is already in use.")
 */
class Reservation
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private ?int $id = null;

    /**
     * @ORM\Column(length=255)
     * @Assert\NotBlank(message="The name cannot be empty.")
     * @Assert\Length(
     *      max=255,
     *      maxMessage="The name must not exceed {{ limit }} characters."
     * )
     */
    private ?string $nom = null;

    /**
     * @ORM\Column(type=Types::DATE_MUTABLE)
     * @Assert\NotNull(message="The start date cannot be null.")
     * @Assert\Type(\DateTimeInterface::class)
     */
    private ?\DateTimeInterface $datedebutres = null;

    /**
     * @ORM\Column(type=Types::DATE_MUTABLE)
     * @Assert\NotNull(message="The end date cannot be null.")
     * @Assert\Type(\DateTimeInterface::class)
     * @Assert\GreaterThan(propertyPath="datedebutres", message="The end date must be after the start date.")
     */
    private ?\DateTimeInterface $datefinres = null;

    /**
     * @ORM\Column(length=255)
     * @Assert\NotBlank(message="The type cannot be empty.")
     * @Assert\Length(
     *      max=255,
     *      maxMessage="The type must not exceed {{ limit }} characters."
     * )
     */
    private ?string $type = null;

    /**
     * @ORM\Column(type="float")
     * @Assert\NotNull(message="The deposit cannot be null.")
     * @Assert\PositiveOrZero(message="The deposit must be zero or a positive number.")
     */
    private ?float $deposit = null;

    /**
     * @ORM\ManyToOne(inversedBy="reservations")
     * @ORM\JoinColumn(name="id_equipement", referencedColumnName="id")
     * @Assert\NotNull(message="The equipment cannot be null.")
     */
    private ?Equipement $idEquipement = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNom(): ?string
    {
        return $this->nom;
    }

    public function setNom(?string $nom): self
    {
        $this->nom = $nom;

        return $this;
    }

    public function getDatedebutres(): ?\DateTimeInterface
    {
        return $this->datedebutres;
    }

    public function setDatedebutres(?\DateTimeInterface $datedebutres): self
    {
        $this->datedebutres = $datedebutres;

        return $this;
    }

    public function getDatefinres(): ?\DateTimeInterface
    {
        return $this->datefinres;
    }

    public function setDatefinres(?\DateTimeInterface $datefinres): self
    {
        $this->datefinres = $datefinres;

        return $this;
    }

    public function getType(): ?string
    {
        return $this->type;
    }

    public function setType(?string $type): self
    {
        $this->type = $type;

        return $this;
    }

    public function getDeposit(): ?float
    {
        return $this->deposit;
    }

    public function setDeposit(?float $deposit): self
    {
        $this->deposit = $deposit;

        return $this;
    }

    public function getIdEquipement(): ?Equipement
    {
        return $this->idEquipement;
    }

    public function setIdEquipement(?Equipement $idEquipement): self
    {
        $this->idEquipement = $idEquipement;

        return $this;
    }
}
