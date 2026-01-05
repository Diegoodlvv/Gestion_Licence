<?php

namespace App\Entity;

use App\Repository\TeachingBlockRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: TeachingBlockRepository::class)]
class TeachingBlock
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(type: Types::TEXT, length: 50, unique: true)]
    #[Assert\Unique()]
    #[Assert\Type('string')]
    #[Assert\NotNull()]
    #[Assert\NotBlank()]
    private ?string $code = null;

    #[ORM\Column(type: Types::TEXT, length: 255)]
    #[Assert\Type('string')]
    #[Assert\NotNull()]
    #[Assert\NotBlank()]
    private ?string $name = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $description = null;

    #[ORM\Column(nullable: true)]
    private ?int $hours_count = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCode(): ?string
    {
        return $this->code;
    }

    public function setCode(string $code): static
    {
        $this->code = $code;

        return $this;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): static
    {
        $this->name = $name;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): static
    {
        $this->description = $description;

        return $this;
    }

    public function getHoursCount(): ?int
    {
        return $this->hours_count;
    }

    public function setHoursCount(?int $hours_count): static
    {
        $this->hours_count = $hours_count;

        return $this;
    }
}
