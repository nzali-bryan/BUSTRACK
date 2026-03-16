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
    private ?Trajets $trajet = null;

    #[ORM\OneToOne(inversedBy: 'bus', cascade: ['persist', 'remove'])]
    private ?Conducteurs $conducteurs = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $direction = null;

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

    public function getConducteurs(): ?Conducteurs
    {
        return $this->conducteurs;
    }

    public function setConducteurs(?Conducteurs $conducteurs): static
    {
        $this->conducteurs = $conducteurs;

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
}
