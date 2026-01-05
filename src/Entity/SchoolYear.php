<?php

namespace App\Entity;

use App\Repository\SchoolYearRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;


#[ORM\Entity(repositoryClass: SchoolYearRepository::class)]
class SchoolYear
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(type: Types::TEXT, length: 255)]
    #[Assert\Type('string')]
    #[Assert\NotNull()]
    #[Assert\NotBlank()]
    private ?string $name = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    #[Assert\DateTime(format: 'YYYY')]
    #[Assert\NotNull()]
    #[Assert\NotBlank()]
    private ?\DateTime $start_date = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    #[Assert\DateTime(format: 'YYYY')]
    #[Assert\NotNull()]
    #[Assert\NotBlank()]
    private ?\DateTime $end_date = null;

    /**
     * @var Collection<int, CoursePeriod>
     */
    #[ORM\OneToMany(targetEntity: CoursePeriod::class, mappedBy: 'school_year_id')]
    private Collection $coursePeriods;

    public function __construct()
    {
        $this->coursePeriods = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
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

    public function getStartDate(): ?\DateTime
    {
        return $this->start_date;
    }

    public function setStartDate(\DateTime $start_date): static
    {
        $this->start_date = $start_date;

        return $this;
    }

    public function getEndDate(): ?\DateTime
    {
        return $this->end_date;
    }

    public function setEndDate(\DateTime $end_date): static
    {
        $this->end_date = $end_date;

        return $this;
    }

    /**
     * @return Collection<int, CoursePeriod>
     */
    public function getCoursePeriods(): Collection
    {
        return $this->coursePeriods;
    }

    public function addCoursePeriod(CoursePeriod $coursePeriod): static
    {
        if (!$this->coursePeriods->contains($coursePeriod)) {
            $this->coursePeriods->add($coursePeriod);
            $coursePeriod->setSchoolYearId($this);
        }

        return $this;
    }

    public function removeCoursePeriod(CoursePeriod $coursePeriod): static
    {
        if ($this->coursePeriods->removeElement($coursePeriod)) {
            // set the owning side to null (unless already changed)
            if ($coursePeriod->getSchoolYearId() === $this) {
                $coursePeriod->setSchoolYearId(null);
            }
        }

        return $this;
    }
}
