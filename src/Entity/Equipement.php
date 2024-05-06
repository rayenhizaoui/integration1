<?php

namespace App\Entity;

use App\Repository\EquipementRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass=EquipementRepository::class)
 */
class Equipement
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
     *      maxMessage="The name cannot be longer than {{ limit }} characters."
     * )
     */
    private ?string $nom = null;

    /**
     * @ORM\Column(type="integer")
     * @Assert\NotBlank(message="The number cannot be empty.")
     * @Assert\Positive(message="The number must be positive.")
     */
    private ?int $nombre = null;

    /**
     * @ORM\Column(length=255, nullable=true)
     */
    private ?string $image = null;

    /**
     * @ORM\Column(type="float", nullable=true)
     * @Assert\Range(
     *      min=0,
     *      max=5,
     *      notInRangeMessage="The rating must be between {{ min }} and {{ max }}."
     * )
     */
    private ?float $rating = null;

    /**
     * @ORM\Column(length=255, nullable=true)
     * @Assert\Length(
     *      max=255,
     *      maxMessage="The QR code data cannot be longer than {{ limit }} characters."
     * )
     */
    private ?string $qrcode = null;

    /**
     * @ORM\OneToMany(targetEntity=Reservation::class, mappedBy="idEquipement")
     */
    private Collection $reservations;

    public function __construct()
    {
        $this->reservations = new ArrayCollection();
    }

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

    public function getNombre(): ?int
    {
        return $this->nombre;
    }

    public function setNombre(?int $nombre): self
    {
        $this->nombre = $nombre;

        return $this;
    }

    public function getImage(): ?string
    {
        return $this->image;
    }

    public function setImage(?string $image): self
    {
        $this->image = $image;

        return $this;
    }

    public function getRating(): ?float
    {
        return $this->rating;
    }

    public function setRating(?float $rating): self
    {
        $this->rating = $rating;

        return $this;
    }

    public function getQrcode(): ?string
    {
        return $this->qrcode;
    }

    public function setQrcode(?string $qrcode): self
    {
        $this->qrcode = $qrcode;

        return $this;
    }

    /**
     * @return Collection<int, Reservation>
     */
    public function getReservations(): Collection
    {
        return $this->reservations;
    }

    public function addReservation(Reservation $reservation): self
    {
        if (!$this->reservations->contains($reservation)) {
            $this->reservations[] = $reservation;
            $reservation->setIdEquipement($this);
        }

        return $this;
    }

    public function removeReservation(Reservation $reservation): self
    {
        if ($this->reservations->removeElement($reservation)) {
            // set the owning side to null (unless already changed)
            if ($reservation->getIdEquipement() === $this) {
                $reservation->setIdEquipement(null);
            }
        }

        return $this;
    }

    public function __toString(): string
    {
        return $this->nom;
    }
}
