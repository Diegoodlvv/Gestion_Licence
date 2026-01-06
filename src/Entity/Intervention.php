<?php

namespace App\Entity;

use App\Repository\InterventionRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: InterventionRepository::class)]
class Intervention
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private ?\DateTime $start_date = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private ?\DateTime $end_date = null;

    #[ORM\ManyToOne(inversedBy: 'interventions')]
    private ?CoursePeriod $course_period_id = null;

    #[ORM\ManyToOne(inversedBy: 'interventions')]
    private ?InterventionType $interventon_type_id = null;

    #[ORM\ManyToOne(inversedBy: 'interventions')]
    private ?Module $module_id = null;

    #[ORM\Column]
    private ?bool $remotely = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $title = null;

    public function getId(): ?int
    {
        return $this->id;
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

    public function getCoursePeriodId(): ?CoursePeriod
    {
        return $this->course_period_id;
    }

    public function setCoursePeriodId(?CoursePeriod $course_period_id): static
    {
        $this->course_period_id = $course_period_id;

        return $this;
    }

    public function getInterventonTypeId(): ?InterventionType
    {
        return $this->interventon_type_id;
    }

    public function setInterventonTypeId(?InterventionType $interventon_type_id): static
    {
        $this->interventon_type_id = $interventon_type_id;

        return $this;
    }

    public function getModuleId(): ?Module
    {
        return $this->module_id;
    }

    public function setModuleId(?Module $module_id): static
    {
        $this->module_id = $module_id;

        return $this;
    }

    public function isRemotely(): ?bool
    {
        return $this->remotely;
    }

    public function setRemotely(bool $remotely): static
    {
        $this->remotely = $remotely;

        return $this;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(?string $title): static
    {
        $this->title = $title;

        return $this;
    }
}
