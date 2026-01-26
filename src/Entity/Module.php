<?php

namespace App\Entity;

use App\Repository\ModuleRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: ModuleRepository::class)]
class Module
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(type: Types::TEXT, length: 50)]
    #[Assert\Type('string')]
    #[Assert\NotNull()]
    #[Assert\NotBlank()]
    private ?string $code = null;

    #[ORM\ManyToOne(targetEntity: self::class, inversedBy: 'childrens', cascade: ["persist"])]
    #[ORM\JoinColumn(onDelete: "SET NULL")]
    private ?self $parent = null;


    /**
     * @var Collection<int, self>
     */
    #[ORM\OneToMany(targetEntity: self::class, mappedBy: 'parent', cascade: ["persist"])]
    private ?Collection $childrens = null;

    #[ORM\Column(type: Types::TEXT, length: 255)]
    #[Assert\Type('string')]
    #[Assert\NotNull()]
    #[Assert\NotBlank()]
    private ?string $name = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    #[Assert\Type('string')]
    private ?string $description = null;

    #[ORM\Column(type: Types::INTEGER)]
    #[Assert\Type('integer')]
    #[Assert\NotNull()]
    #[Assert\NotBlank()]
    private ?int $hours_count = null;

    #[ORM\Column(type: Types::BOOLEAN)]
    #[Assert\Type('boolean')]
    #[Assert\NotNull()]
    #[Assert\NotBlank()]
    private ?bool $capstone_project = null;

    #[ORM\ManyToOne(inversedBy: 'modules')]
    private ?TeachingBlock $teaching_block = null;

    /**
     * @var Collection<int, Intervention>
     */
    #[ORM\OneToMany(targetEntity: Intervention::class, mappedBy: 'module')]
    private Collection $interventions;

    /**
     * @var Collection<int, Instructor>
     */
    #[ORM\ManyToMany(targetEntity: Instructor::class, mappedBy: 'module')]
    private Collection $instructors;

    public function __construct()
    {
        $this->childrens = new ArrayCollection();
        $this->interventions = new ArrayCollection();
        $this->instructors = new ArrayCollection();
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

    public function getParentId(): ?self
    {
        return $this->parent;
    }

    public function setParentId(?self $parent): static
    {
        $this->parent = $parent;

        return $this;
    }

    /**
     * @return Collection<int, self>
     */
    public function getModulesChildren(): Collection
    {
        return $this->childrens;
    }

    public function addModulesChild(self $modulesChild): static
    {
        if (!$this->childrens->contains($modulesChild)) {
            $this->childrens->add($modulesChild);
            $modulesChild->setParentId($this);
        }

        return $this;
    }

    public function removeModulesChild(self $modulesChild): static
    {
        if ($this->childrens->removeElement($modulesChild)) {
            // set the owning side to null (unless already changed)
            if ($modulesChild->getParentId() === $this) {
                $modulesChild->setParentId(null);
            }
        }

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

    public function setHoursCount(int $hours_count): static
    {
        $this->hours_count = $hours_count;

        return $this;
    }

    public function isCapstoneProject(): ?bool
    {
        return $this->capstone_project;
    }

    public function setCapstoneProject(bool $capstone_project): static
    {
        $this->capstone_project = $capstone_project;

        return $this;
    }

    public function getTeachingBlock(): ?TeachingBlock
    {
        return $this->teaching_block;
    }

    public function setTeachingBlock(?TeachingBlock $teaching_block): static
    {
        $this->teaching_block = $teaching_block;

        return $this;
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
            $intervention->setModule($this);
        }

        return $this;
    }

    public function removeIntervention(Intervention $intervention): static
    {
        if ($this->interventions->removeElement($intervention)) {
            // set the owning sde to null (unless already changed)
            if ($intervention->getModule() === $this) {
                $intervention->setModule(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Instructor>
     */
    public function getInstructors(): Collection
    {
        return $this->instructors;
    }

    public function addInstructor(Instructor $instructor): static
    {
        if (!$this->instructors->contains($instructor)) {
            $this->instructors->add($instructor);
            $instructor->addModule($this);
        }

        return $this;
    }

    public function removeInstructor(Instructor $instructor): static
    {
        if ($this->instructors->removeElement($instructor)) {
            $instructor->removeModule($this);
        }

        return $this;
    }

   public function getFullName(): string
    {
        return $this->parent
            ?  $this->parent->getFullName() . ' — ' . $this->name
            : $this->name;
    }
}
