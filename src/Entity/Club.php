<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiFilter;
use ApiPlatform\Core\Annotation\ApiResource;
use App\Repository\ClubRepository;
use Doctrine\ORM\Mapping as ORM;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\SearchFilter;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 *   @ApiResource( *     collectionOperations={"get","post"},
 *     itemOperations={"get","put","delete","patch"},
 *     normalizationContext={"groups"={"Club:read"}},
 *     denormalizationContext={"groups"={"Club:read"}},
 *     shortName="Club",
 *     attributes={"pagination_items_per_page"=10,
 *     "formats"={"jsonld","json","html"}
 *     }

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
     * @Groups({"Club:read","Club:write"})
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"Club:read","Club:write"})
     *
     */
    private $name;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"Club:read","Club:write"})
     */
    private $city;

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
}
