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
    private ?string $year = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    #[Assert\Type('string')]
    #[Assert\NotNull()]
    #[Assert\NotBlank()]
    private ?string $saison = null;

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

    public function getYear(): ?string
    {
        return $this->year;
    }

    public function setYear(string $year): static
    {
        $this->year = $year;

        return $this;
    }

    public function getSaison(): ?string
    {
        return $this->saison;
    }

    public function setSaison(string $saison): static
    {
        $this->saison = $saison;

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
