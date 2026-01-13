<?php

namespace App\Entity;

use App\Repository\InterventionRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
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
    private ?CoursePeriod $course_period = null;

    #[ORM\ManyToOne(inversedBy: 'interventions')]
    private ?InterventionType $interventon_type = null;

    #[ORM\ManyToOne(inversedBy: 'interventions')]
    private ?Module $module = null;

    #[ORM\Column]
    private ?bool $remotely = null;


    /**
     * @var Collection<int, Instructor>
     */
    #[ORM\ManyToMany(targetEntity: Instructor::class, inversedBy: 'interventions')]
    private Collection $instructors;

    public function __construct()
    {
        $this->instructors = new ArrayCollection();
    }

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

    public function getCoursePeriod(): ?CoursePeriod
    {
        return $this->course_period;
    }

    public function setCoursePeriod(?CoursePeriod $course_period): static
    {
        $this->course_period = $course_period;

        return $this;
    }

    public function getInterventionType(): ?InterventionType
    {
        return $this->interventon_type;
    }

    public function setInterventionType(?InterventionType $interventon_type): static
    {
        $this->interventon_type = $interventon_type;

        return $this;
    }

    public function getModule(): ?Module
    {
        return $this->module;
    }

    public function setModule(?Module $module): static
    {
        $this->module = $module;

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
        }

        return $this;
    }

    public function removeInstructor(Instructor $instructor): static
    {
        $this->instructors->removeElement($instructor);

        return $this;
    }
}
