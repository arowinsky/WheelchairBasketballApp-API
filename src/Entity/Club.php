<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiFilter;
use ApiPlatform\Core\Annotation\ApiResource;
use App\Repository\ClubRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\SearchFilter;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

/**
 *   @ApiResource(
 *     collectionOperations={"get","post"},
 *     itemOperations={"get","put","delete","patch"},
 *     collectionOperations={"get"={"access_control"="is_granted('ROLE_PLAYER')"},
 *     "post"={"access_control"="is_granted('ROLE_PLAYER')"}
 *          },
 *     normalizationContext={"groups"={"Club:read"}},
 *     denormalizationContext={"groups"={"Club:write"}},
 *     shortName="Club",
 *     attributes={"pagination_items_per_page"=10,
 *     "formats"={"jsonld","json","html"}
 *     }
 *

 * )
 * @ApiFilter(SearchFilter::class,properties={"name":"partial", "city":"partial"})
 * @ORM\Entity(repositoryClass=ClubRepository::class)
 */
class Club
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     * @Groups({"Club:read"})
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"Club:read","Club:write","user:read"})
     * @Assert\NotBlank(
     *     message="Name not be empty"
     * )
     * @Assert\Length(
     *     min="3",
     *     max="255",
     *     minMessage="Min lenght is 3 chars",
     *     maxMessage="Max lenght is 255 chars",
     * )
     */
    private $name;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"Club:read","Club:write","user:read"})
     *      * @Assert\NotBlank(
     *     message="City not be empty"
     * )
     * @Assert\Length(
     *     min="3",
     *     minMessage="Min lenght is 3 chars",
     *     max="255",
     *     maxMessage="Max lenght is 255 chars",
     * )
     */
    private $city;

    /**
     * @ORM\OneToMany(targetEntity=User::class, mappedBy="club")
     * @Groups({"Clubs:read"})
     */
    private $User;

    public function __construct()
    {
        $this->User = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getCity(): ?string
    {
        return $this->city;
    }

    public function setCity(string $city): self
    {
        $this->city = $city;

        return $this;
    }

    /**
     * @return Collection|User[]
     */
    public function getUser(): Collection
    {
        return $this->User;
    }

    public function addUser(User $user): self
    {
        if (!$this->User->contains($user)) {
            $this->User[] = $user;
            $user->setClub($this);
        }

        return $this;
    }

    public function removeUser(User $user): self
    {
        if ($this->User->contains($user)) {
            $this->User->removeElement($user);
            // set the owning side to null (unless already changed)
            if ($user->getClub() === $this) {
                $user->setClub(null);
            }
        }

        return $this;
    }
}
