<?php

namespace App\Entity;

use App\Repository\TournoiRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass=TournoiRepository::class)
 */
class Tournoi
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private ?int $id = null;




    /**
     * @Assert\Regex(
     *     pattern="/^[a-z]+$/i",
     *     htmlPattern="^[a-zA-Z]+$"
     * )
     * @ORM\Column(length=255)
     */
    private ?string $nomTournoi = null;

    /**
     * @ORM\Column(length=255)
     */
    private ?string $nomEquipe = null;

    /**
     * @Assert\PositiveOrZero
     * @ORM\Column(type="integer")
     */
    private ?int $nombreParticipants = null;

    /**
     * @Assert\PositiveOrZero
     * @ORM\Column(type="integer")
     */
    private ?int $duree = null;

    /**
     * @ORM\Column(length=255)
     */
    private ?string $typeJeu = null;

    /**
     * @Assert\PositiveOrZero
     * @ORM\Column(type="float")
     */
    private ?float $fraisParticipant = null;

    /**
     * @ORM\ManyToOne(targetEntity=Local::class, inversedBy="tournois")
     */
    private ?Local $location = null;

    /**
     * @ORM\Column(length=255)
     */
    private ?string $image = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNomTournoi(): ?string
    {
        return $this->nomTournoi;
    }

    public function setNomTournoi(string $nomTournoi): self
    {
        $this->nomTournoi = $nomTournoi;
        return $this;
    }

    public function getNomEquipe(): ?string
    {
        return $this->nomEquipe;
    }

    public function setNomEquipe(string $nomEquipe): self
    {
        $this->nomEquipe = $nomEquipe;
        return $this;
    }

    public function getNombreParticipants(): ?int
    {
        return $this->nombreParticipants;
    }

    public function setNombreParticipants(int $nombreParticipants): self
    {
        $this->nombreParticipants = $nombreParticipants;
        return $this;
    }

    public function getDuree(): ?int
    {
        return $this->duree;
    }

    public function setDuree(int $duree): self
    {
        $this->duree = $duree;
        return $this;
    }

    public function getTypeJeu(): ?string
    {
        return $this->typeJeu;
    }

    public function setTypeJeu(string $typeJeu): self
    {
        $this->typeJeu = $typeJeu;
        return $this;
    }

    public function getFraisParticipant(): ?float
    {
        return $this->fraisParticipant;
    }

    public function setFraisParticipant(float $fraisParticipant): self
    {
        $this->fraisParticipant = $fraisParticipant;
        return $this;
    }

    public function getLocation(): ?Local
    {
        return $this->location;
    }

    public function setLocation(?Local $location): self
    {
        $this->location = $location;
        return $this;
    }

    public function getImage(): ?string
    {
        return $this->image;
    }

    public function setImage(string $image): self
    {
        $this->image = $image;
        return $this;
    }
}
