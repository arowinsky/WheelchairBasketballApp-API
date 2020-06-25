<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiFilter;
use ApiPlatform\Core\Annotation\ApiResource;
use App\Repository\UserRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\UserInterface;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\SearchFilter;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

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
 * @ApiFilter(SearchFilter::class,properties={"firstname":"partial", "lastname":"partial",
 *     "email":"partial","club":"partial"
 *     })
 * @UniqueEntity(fields={"email"})
 */
class User implements UserInterface
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     * @Groups({"user:read","codeActive:read"})
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=180, unique=true)
     * @Groups({"user:read","user:write","codeActive:read"})
     * @Assert\NotBlank(
     *     message="Email not be empty"
     * )
     * @Assert\Email(
     *     message="Wrong Email"
     * )
     */
    private $email;

    /**
     * @ORM\Column(type="json")
     * @Groups({"user:read","user:write",})
     */
    private $roles = ['ROLE_PLAYER'];

    /**
     * @var string The hashed password
     * @ORM\Column(type="string")
     * @Groups({"user:write"})
     * @Assert\NotBlank(
     *     message="Password not be empty"
     * )
     */
    private $password;

    /**
     * @ORM\Column(type="string", length=50)
     * @Groups({"user:read","user:write"})
     * @Assert\NotBlank(
     *     message="lastname not be empty"
     * )
     * @Assert\Length(
     *     min="3",
     *     minMessage="Min length firstname is 3 chars",
     *     max="50",
     *     maxMessage="Max length firstname is 50 chars",
     *  )
     */
    private $firstname;

    /**
     * @ORM\Column(type="string", length=100)
     * @Groups({"user:read","user:write"})
     * @Assert\NotBlank(
     *     message="lastname not be empty"
     * )
     *  @Assert\Length(
     *     min="3",
     *     minMessage="Min length lastname is 3 chars",
     *     max="100",
     *     maxMessage="Max length lastname is 100 chars",
     *  )
     */
    private $lastname;

    /**
     * @ORM\Column(type="boolean")
     * @Groups({"user:read","user:write"})
     */
    private $statusAccaunt = false;

    /**
     * @ORM\Column(type="boolean")
     * @Groups({"user:read","user:write"})
     */
    private $statusPlayer = false;

    /**
     * @ORM\ManyToOne(targetEntity=Club::class, inversedBy="User")
     * @ORM\JoinColumn(nullable=false)
     * @Groups({"user:read","user:write"})
     */
    private $club;

    /**
     * @ORM\OneToOne(targetEntity=CodeActive::class, mappedBy="User", cascade={"persist", "remove"})
     */
    private $codeActive;

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
        $roles[] = 'ROLE_PLAYER';

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

    public function getClub(): ?Club
    {
        return $this->club;
    }

    public function setClub(?Club $club): self
    {
        $this->club = $club;

        return $this;
    }

    public function getCodeActive(): ?CodeActive
    {
        return $this->codeActive;
    }

    public function setCodeActive(CodeActive $codeActive): self
    {
        $this->codeActive = $codeActive;

        // set the owning side of the relation if necessary
        if ($codeActive->getUser() !== $this) {
            $codeActive->setUser($this);
        }

        return $this;
    }
}
