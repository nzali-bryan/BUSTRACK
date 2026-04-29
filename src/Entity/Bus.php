<?php

namespace App\Entity;

use App\Repository\BusRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: BusRepository::class)]
class Bus
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(type: Types::TEXT)]
    private ?string $nom = null;

    #[ORM\Column]
    private ?int $capacite = null;

    #[ORM\Column(type: Types::TEXT)]
    private ?string $etat = null;

    #[ORM\ManyToOne(inversedBy: 'buses')]
    #[ORM\JoinColumn(nullable: true, onDelete:"SET NULL")]
    private ?Trajets $trajet = null;

    // #[ORM\OneToOne(inversedBy: 'bus', cascade: ['persist', 'remove'])]
    // private ?Conducteurs $conducteurs = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $direction = null;

    #[ORM\OneToOne(inversedBy: 'bus', cascade: ['persist', 'remove'])]
    private ?Administrateur $administrateur = null;

    #[ORM\Column(type: Types::FLOAT, precision: 10, scale: 2,nullable: true)]
    private ?float $Vitesse = null;

    #[ORM\Column(nullable: true)]
    private ?float $tempsInitial = null;

    #[ORM\Column(nullable: true)]
    private ?float $tempsEcoule = null;

 

    #[ORM\Column(nullable: true)]
    private ?\DateTimeImmutable $startTime = null;

    #[ORM\Column(nullable: true)]
    private ?int $tempsRestant = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNom(): ?string
    {
        return $this->nom;
    }

    public function setNom(string $nom): static
    {
        $this->nom = $nom;

        return $this;
    }

    public function getCapacite(): ?int
    {
        return $this->capacite;
    }

    public function setCapacite(int $capacite): static
    {
        $this->capacite = $capacite;

        return $this;
    }

    public function getEtat(): ?string
    {
        return $this->etat;
    }

    public function setEtat(string $etat): static
    {
        $this->etat = $etat;

        return $this;
    }

    public function getTrajet(): ?Trajets
    {
        return $this->trajet;
    }

    public function setTrajet(?Trajets $trajet): static
    {
        $this->trajet = $trajet;

        return $this;
    }



    public function getDirection(): ?string
    {
        return $this->direction;
    }

    public function setDirection(?string $direction): static
    {
        $this->direction = $direction;

        return $this;
    }

    public function getAdministrateur(): ?Administrateur
    {
        return $this->administrateur;
    }

    public function setAdministrateur(?Administrateur $administrateur): static
    {
        $this->administrateur = $administrateur;

        return $this;
    }

    public function getVitesse(): ?float
    {
        return $this->Vitesse;
    }

    public function setVitesse(float $Vitesse): static
    {
        $this->Vitesse = $Vitesse;

        return $this;
    }

public function getTempsInitial(): ?float
{
    return $this->tempsInitial;
}

public function setTempsInitial(?float $tempsInitial): static
{
    $this->tempsInitial = $tempsInitial;

    return $this;
}

public function getTempsEcoule(): ?float
{
    return $this->tempsEcoule;
}

public function setTempsEcoule(?float $tempsEcoule): static
{
    $this->tempsEcoule = $tempsEcoule;

    return $this;
}



public function getStartTime(): ?\DateTimeImmutable
{
    return $this->startTime;
}

public function setStartTime(?\DateTimeImmutable $startTime): static
{
    $this->startTime = $startTime;

    return $this;
}

public function getTempsRestant(): ?int
{
    return $this->tempsRestant;
}

public function setTempsRestant(?int $tempsRestant): static
{
    $this->tempsRestant = $tempsRestant;

    return $this;
}

}
