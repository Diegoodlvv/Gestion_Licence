<?php

namespace App\Entity;

use App\Repository\SchoolYearRepository;
use DateTime;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Validator\Constraints as Assert;


#[ORM\Entity(repositoryClass: SchoolYearRepository::class)]
#[UniqueEntity('year')]
#[UniqueEntity('saison')]
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

    #[ORM\Column(type: Types::TEXT, length:9)]
    #[Assert\Type('string')]
    #[Assert\NotNull()]
    #[Assert\NotBlank()]
    private ?string $saison = null;

    /**
     * @var Collection<int, CoursePeriod>
     */
    #[ORM\OneToMany(targetEntity: CoursePeriod::class, mappedBy: 'school_year')]
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
            $coursePeriod->setSchoolYear($this);
        }

        return $this;
    }

    public function removeCoursePeriod(CoursePeriod $coursePeriod): static
    {
        if ($this->coursePeriods->removeElement($coursePeriod)) {
            // set the owning re to null (unless already changed)
            if ($coursePeriod->getSchoolYear() === $this) {
                $coursePeriod->setSchoolYear(null);
            }
        }

        return $this;
    }

    public static function getActualYear(): ?string 
    {
        $date = new DateTime();

        $month = (int)$date->format('m');

        $year = $date->format('Y');

        if($month > 8 ){
            $year = (int)$year + 1;
        } 
        return $year;
    }
}
