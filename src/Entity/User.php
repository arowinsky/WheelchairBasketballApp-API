<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiFilter;
use ApiPlatform\Core\Annotation\ApiResource;
use App\Repository\UserRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\UserInterface;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\SearchFilter;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ORM\Entity(repositoryClass=UserRepository::class)
 * @ApiResource(
 *     collectionOperations={"get","post"},
 *     itemOperations={"get","put","delete","patch"},
 *     normalizationContext={"groups"={"user:read"},"swagger_definition_name"="Read"},
 *     denormalizationContext={"groups"={"user:write"},"swagger_definition_name"="Write"},
 *     shortName="User",
 *     attributes={"pagination_items_per_page"=10,
 *     "formats"={"jsonld","json","html"}
 *     }
 * )
 * @ApiFilter(SearchFilter::class,properties={"firstname":"partial", "lastname":"partial"})
 */
class User implements UserInterface
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     * @Groups({"user:read"})
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=180, unique=true)
     * @Groups({"user:read","user:write"})
     */
    private $email;

    /**
     * @ORM\Column(type="json")
     * @Groups({"user:read","user:write"})
     */
    private $roles = [];

    /**
     * @var string The hashed password
     * @ORM\Column(type="string")
     * @Groups({"user:write"})
     */
    private $password;

    /**
     * @ORM\Column(type="string", length=50)
     * @Groups({"user:read","user:write"})
     */
    private $firstname;

    /**
     * @ORM\Column(type="string", length=100)
     * @Groups({"user:read","user:write"})
     */
    private $lastname;

    /**
     * @ORM\Column(type="boolean")
     * @Groups({"user:read","user:write"})
     */
    private $statusAccaunt;

    /**
     * @ORM\Column(type="boolean")
     * @Groups({"user:read","user:write"})
     */
    private $statusPlayer;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUsername(): string
    {
        return (string) $this->email;
    }

    /**
     * @see UserInterface
     */
    public function getRoles(): array
    {
        $roles = $this->roles;
        // guarantee every user at least has ROLE_USER
        $roles[] = 'ROLE_USER';

        return array_unique($roles);
    }

    public function setRoles(array $roles): self
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function getPassword(): string
    {
        return (string) $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function getSalt()
    {
        // not needed when using the "bcrypt" algorithm in security.yaml
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials()
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
    }

    public function getFirstname(): ?string
    {
        return $this->firstname;
    }

    public function setFirstname(string $firstname): self
    {
        $this->firstname = $firstname;

        return $this;
    }

    public function getLastname(): ?string
    {
        return $this->lastname;
    }

    public function setLastname(string $lastname): self
    {
        $this->lastname = $lastname;

        return $this;
    }

    public function getStatusAccaunt(): ?bool
    {
        return $this->statusAccaunt;
    }

    public function setStatusAccaunt(bool $statusAccaunt): self
    {
        $this->statusAccaunt = $statusAccaunt;

        return $this;
    }

    public function getStatusPlayer(): ?bool
    {
        return $this->statusPlayer;
    }

    public function setStatusPlayer(bool $statusPlayer): self
    {
        $this->statusPlayer = $statusPlayer;

        return $this;
    }
}
