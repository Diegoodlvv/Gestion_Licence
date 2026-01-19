<?php

namespace App\Entity;

use App\Repository\InterventionTypeRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;


#[ORM\Entity(repositoryClass: InterventionTypeRepository::class)]
class InterventionType
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(unique: true)]
    private ?int $id = null;

    #[ORM\Column(type: Types::TEXT)]
    #[Assert\Type('string')]
    #[Assert\NotNull()]
    #[Assert\NotBlank()]
    private ?string $name = null;

    #[ORM\Column(type: Types::TEXT)]
    #[Assert\Type('string')]
    #[Assert\NotNull()]
    #[Assert\NotBlank()]
    private ?string $description = null;

    #[ORM\Column(type: Types::TEXT)]
    #[Assert\Type('string')]
    #[Assert\NotNull()]
    #[Assert\NotBlank()]
    private ?string $color = null;

    /**
     * @var Collection<int, Intervention>
     */
    #[ORM\OneToMany(targetEntity: Intervention::class, mappedBy: 'interventon_type_id')]
    private Collection $interventions;

    public function __construct()
    {
        $this->interventions = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): ?string
    {
        $this->name = $name;

        return $this->name;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): ?string
    {
        $this->description = $description;

        return $this->description;
    }

    public function getColor(): ?string
    {
        return $this->color;
    }

    public function setColor(string $color): ?string
    {
        $this->color = $color;

        return $this->color;
    }

    /**
     * @return Collection<int, Intervention>
     */
    public function getInterventions(): Collection
    {
        return $this->interventions;
    }

    public function addIntervention(Intervention $intervention): static
    {
        if (!$this->interventions->contains($intervention)) {
            $this->interventions->add($intervention);
            $intervention->setInterventionType($this);
        }

        return $this;
    }

    public function removeIntervention(Intervention $intervention): static
    {
        if ($this->interventions->removeElement($intervention)) {
            // set the owning se to null (unless already changed)
            if ($intervention->getInterventionType() === $this) {
                $intervention->setInterventionType(null);
            }
        }

        return $this;
    }
}
