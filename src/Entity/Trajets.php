<?php

namespace App\Entity;

use App\Repository\TrajetsRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: TrajetsRepository::class)]
class Trajets
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $nom = null;

    #[ORM\Column(length: 255)]
    private ?string $etat = null;

    #[ORM\Column(type: Types::TEXT)]
    private ?string $ligne = null;

    /**
     * @var Collection<int, Point>
     */
    #[ORM\OneToMany(targetEntity: Point::class, mappedBy: 'trajet')]
    private Collection $id_point;

    /**
     * @var Collection<int, Bus>
     */
    #[ORM\OneToMany(targetEntity: Bus::class, mappedBy: 'trajet')]
    private Collection $buses;

    #[ORM\Column(length: 255)]
    private ?string $zone = null;

    /**
     * @var Collection<int, Favoris>
     */
    #[ORM\OneToMany(targetEntity: Favoris::class, mappedBy: 'trajets', orphanRemoval: true)]
    private Collection $favoris;

    public function __construct()
    {
        $this->id_point = new ArrayCollection();
        $this->buses = new ArrayCollection();
        $this->favoris = new ArrayCollection();
    }

  

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

    public function getEtat(): ?string
    {
        return $this->etat;
    }

    public function setEtat(string $etat): static
    {
        $this->etat = $etat;

        return $this;
    }

    public function getLigne(): ?string
    {
        return $this->ligne;
    }

    public function setLigne(string $ligne): static
    {
        $this->ligne = $ligne;

        return $this;
    }

    /**
     * @return Collection<int, Point>
     */
    public function getPoint(): Collection
    {
        return $this->id_point;
    }

    public function addPoint(Point $idPoint): static
    {
        if (!$this->id_point->contains($idPoint)) {
            $this->id_point->add($idPoint);
            $idPoint->setTrajet($this);
        }

        return $this;
    }

    public function removePoint(Point $idPoint): static
    {
        if ($this->id_point->removeElement($idPoint)) {
            // set the owning side to null (unless already changed)
            if ($idPoint->getTrajet() === $this) {
                $idPoint->setTrajet(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Bus>
     */
    public function getBuses(): Collection
    {
        return $this->buses;
    }

    public function addBus(Bus $bus): static
    {
        if (!$this->buses->contains($bus)) {
            $this->buses->add($bus);
            $bus->setTrajet($this);
        }

        return $this;
    }

    public function removeBus(Bus $bus): static
    {
        if ($this->buses->removeElement($bus)) {
            // set the owning side to null (unless already changed)
            if ($bus->getTrajet() === $this) {
                $bus->setTrajet(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Point>
     */
    public function getIdPoint(): Collection
    {
        return $this->id_point;
    }

    public function addIdPoint(Point $idPoint): static
    {
        if (!$this->id_point->contains($idPoint)) {
            $this->id_point->add($idPoint);
            $idPoint->setTrajet($this);
        }

        return $this;
    }

    public function removeIdPoint(Point $idPoint): static
    {
        if ($this->id_point->removeElement($idPoint)) {
            // set the owning side to null (unless already changed)
            if ($idPoint->getTrajet() === $this) {
                $idPoint->setTrajet(null);
            }
        }

        return $this;
    }

    public function getZone(): ?string
    {
        return $this->zone;
    }

    public function setZone(string $zone): static
    {
        $this->zone = $zone;

        return $this;
    }

    /**
     * @return Collection<int, Favoris>
     */
    public function getFavoris(): Collection
    {
        return $this->favoris;
    }

    public function addFavori(Favoris $favori): static
    {
        if (!$this->favoris->contains($favori)) {
            $this->favoris->add($favori);
            $favori->setTrajets($this);
        }

        return $this;
    }

    public function removeFavori(Favoris $favori): static
    {
        if ($this->favoris->removeElement($favori)) {
            // set the owning side to null (unless already changed)
            if ($favori->getTrajets() === $this) {
                $favori->setTrajets(null);
            }
        }

        return $this;
    }


}
