<?php

namespace App\Entity;

use App\Repository\CoursePeriodRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: CoursePeriodRepository::class)]
class CoursePeriod
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'coursePeriods')]
    private ?SchoolYear $school_year_id = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private ?\DateTime $start_date = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private ?\DateTime $end_date = null;


    public function getId(): ?int
    {
        return $this->id;
    }

    public function getSchoolYearId(): ?SchoolYear
    {
        return $this->school_year_id;
    }

    public function setSchoolYearId(?SchoolYear $school_year_id): static
    {
        $this->school_year_id = $school_year_id;

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
}
