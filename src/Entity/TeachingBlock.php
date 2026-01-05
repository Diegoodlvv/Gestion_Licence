<?php

namespace App\Entity;

use App\Repository\TeachingBlockRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
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

    /**
     * @var Collection<int, Module>
     */
    #[ORM\OneToMany(targetEntity: Module::class, mappedBy: 'teaching_block_id')]
    private Collection $modules;

    public function __construct()
    {
        $this->modules = new ArrayCollection();
    }


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

    /**
     * @return Collection<int, Module>
     */
    public function getModules(): Collection
    {
        return $this->modules;
    }

    public function addModule(Module $module): static
    {
        if (!$this->modules->contains($module)) {
            $this->modules->add($module);
            $module->setTeachingBlockId($this);
        }

        return $this;
    }

    public function removeModule(Module $module): static
    {
        if ($this->modules->removeElement($module)) {
            // set the owning side to null (unless already changed)
            if ($module->getTeachingBlockId() === $this) {
                $module->setTeachingBlockId(null);
            }
        }

        return $this;
    }
}
