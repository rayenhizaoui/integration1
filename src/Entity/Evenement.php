<?php

namespace App\Entity;

use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * @ORM\Entity(repositoryClass=App\Repository\EvenementRepository::class)
 */
class Evenement
{
    /**
     * @ORM\Column(name="id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;

    /**
     * @ORM\Column(name="nomEvent", type="string", length=25, nullable=false)
     * @Assert\NotBlank(message="The name of the event cannot be blank.")
     * @Assert\Length(
     *      max = 25,
     *      maxMessage = "The name cannot be longer than {{ limit }} characters"
     * )
     */
    private $nomevent;

    /**
     * @ORM\Column(name="lieu", type="string", length=50, nullable=false)
     * @Assert\NotBlank(message="The location cannot be blank.")
     * @Assert\Length(
     *      max = 50,
     *      maxMessage = "The location cannot be longer than {{ limit }} characters"
     * )
     */
    private $lieu;

    /**
     * @ORM\Column(name="dateEvent", type="date", nullable=false)
     * @Assert\NotNull(message="The date of the event cannot be null.")
     * @Assert\Type(
     *      type = "\DateTime",
     *      message = "The value {{ value }} is not a valid date."
     * )
     */
    private $dateevent;

    /**
     * @ORM\Column(name="duree", type="integer", nullable=false)
     * @Assert\NotNull(message="The duration cannot be null.")
     * @Assert\Positive(message="The duration must be positive.")
     */
    private $duree;

    /**
     * @ORM\Column(name="QrCode", type="text", length=16777215, nullable=true)
     */
    private $qrcode;

    /**
     * @ORM\ManyToOne(targetEntity="Recompense")
     * @ORM\JoinColumn(name="id_recompense", referencedColumnName="id")
     * @Assert\NotNull(message="The reward must be selected.")
     */
    private $idRecompense;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNomevent(): ?string
    {
        return $this->nomevent;
    }

    public function setNomevent(?string $nomevent): self
    {
        $this->nomevent = $nomevent;

        return $this;
    }

    public function getLieu(): ?string
    {
        return $this->lieu;
    }

    public function setLieu(?string $lieu): self
    {
        $this->lieu = $lieu;

        return $this;
    }

    public function getDateevent(): ?\DateTimeInterface
    {
        return $this->dateevent;
    }

    public function setDateevent(?\DateTimeInterface $dateevent): self
    {
        $this->dateevent = $dateevent;

        return $this;
    }

    public function getDuree(): ?int
    {
        return $this->duree;
    }

    public function setDuree(?int $duree): self
    {
        $this->duree = $duree;

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

    public function getIdRecompense(): ?Recompense
    {
        return $this->idRecompense;
    }

    public function setIdRecompense(?Recompense $idRecompense): self
    {
        $this->idRecompense = $idRecompense;

        return $this;
    }

}