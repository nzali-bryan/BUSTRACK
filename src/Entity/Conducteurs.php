<?php

namespace App\Entity;

use App\Repository\ConducteursRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;

#[ORM\Entity(repositoryClass: ConducteursRepository::class)]
// #[ORM\UniqueConstraint(name: 'UNIQ_IDENTIFIER_NOM', fields: ['nom'])]
class Conducteurs implements UserInterface, PasswordAuthenticatedUserInterface
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 180)]
    private ?string $nom = null;

    /**
     * @var list<string> The user roles
     */
    #[ORM\Column]
    private array $roles = ['ROLE_DRIVER'];
    /**
     * @var string The hashed password
     */
    #[ORM\Column]
    private ?string $password = null;

    #[ORM\Column]
    private ?int $telephone = null;

    #[ORM\OneToOne(mappedBy: 'conducteurs', cascade: ['persist', 'remove'])]
    private ?Bus $bus = null;

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

    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUserIdentifier(): string
    {
        return (string) $this->nom;
    }

    /**
     * @see UserInterface
     *
     */
   

    public function getRoles(): array
    {
        return $this->roles;
    }
    /**
     * @param list<string> $roles
     */
    public function setRoles(array $roles): static
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * @see PasswordAuthenticatedUserInterface
     */
    public function getPassword(): ?string
    {
        return $this->password;
    }

    public function setPassword(string $password): static
    {
        $this->password = $password;

        return $this;
    }

    #[\Deprecated]
    public function eraseCredentials(): void
    {
        // @deprecated, to be removed when upgrading to Symfony 8
    }

    public function gettelephone(): ?int
    {
        return $this->telephone;
    }

    public function settelephone(int $telephone): static
    {
        $this->telephone = $telephone;

        return $this;
    }

    public function getBus(): ?Bus
    {
        return $this->bus;
    }

    public function setBus(?Bus $bus): static
    {
        // unset the owning side of the relation if necessary
        if ($bus === null && $this->bus !== null) {
            $this->bus->setConducteurs(null);
        }

        // set the owning side of the relation if necessary
        if ($bus !== null && $bus->getConducteurs() !== $this) {
            $bus->setConducteurs($this);
        }

        $this->bus = $bus;

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
