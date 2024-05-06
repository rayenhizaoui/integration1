<?php

namespace App\Entity;

use App\Repository\EquipeRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=EquipeRepository::class)
 */
class Equipe
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private ?int $id = null;

    /**
     * @ORM\Column(type="string", length=50, nullable=false)
     */
    private ?string $nom = null;

    /**
     * @ORM\Column(type="string", length=50)
     */
    private ?string $listeJoueur = null;

    /**
     * @ORM\Column(type="integer")
     */
    private ?int $nbrJoueur = null;

    /**
     * @ORM\ManyToOne(targetEntity=Jeu::class, inversedBy="equipes")
     * @ORM\JoinColumn(name="id_jeu", referencedColumnName="id")
     */
    private ?Jeu $jeu = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNom(): ?string
    {
        return $this->nom;
    }

    public function setNom(string $nom): self
    {
        $this->nom = $nom;

        return $this;
    }

    public function getNbrJoueur(): ?int
    {
        return $this->nbrJoueur;
    }

    public function setNbrJoueur(int $nbrJoueur): self
    {
        $this->nbrJoueur = $nbrJoueur;

        return $this;
    }

    public function getListeJoueur(): ?string
    {
        return $this->listeJoueur;
    }

    public function setListeJoueur(string $listeJoueur): self
    {
        $this->listeJoueur = $listeJoueur;

        return $this;
    }

    public function getJeu(): ?Jeu
    {
        return $this->jeu;
    }

    public function setJeu(?Jeu $jeu): self
    {
        $this->jeu = $jeu;

        return $this;
    }
}
