<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use App\Repository\CodeActiveRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ApiResource(
 *     normalizationContext={"groups"={"codeActive:read"}},
 *     denormalizationContext={"groups"={"codeActive:write"}}
 * )
 * @ORM\Entity(repositoryClass=CodeActiveRepository::class)
 */
class CodeActive
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255),
     * @Groups({"codeActive:read","codeActive:write"})
     */
    private $code;

    /**
     * @ORM\OneToOne(targetEntity=User::class, inversedBy="codeActive", cascade={"persist", "remove"})
     * @ORM\JoinColumn(nullable=false)
     * @Groups({"codeActive:read","codeActive:write"})
     */
    private $User;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCode(): ?string
    {
        return $this->code;
    }

    public function setCode(string $code): self
    {
        $this->code = $code;

        return $this;
    }

    public function getUser(): ?User
    {
        return $this->User;
    }

    public function setUser(User $User): self
    {
        $this->User = $User;

        return $this;
    }
}
