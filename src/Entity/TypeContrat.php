<?php

namespace App\Entity;

use App\Repository\TypeContratRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: TypeContratRepository::class)]
class TypeContrat
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(unique: true)]
    private ?int $id = null;

    #[ORM\Column(type: Types::TEXT)]
    #[Assert\Type('string')]
    #[Assert\NotNull()]
    #[Assert\NotBlank()]
    private ?string $libelle = null;

    #[ORM\OneToMany(targetEntity: Instructor::class, mappedBy: 'typeContrat')]
    private Collection $enseignants;

    public function __construct()
    {
        $this->enseignants = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getLibelle(): ?string
    {
        return $this->libelle;
    }

    public function setLibelle(string $libelle): static
    {
        $this->libelle = $libelle;

        return $this;
    }

    public function getEnseignants(): Collection
    {
        return $this->enseignants;
    }

    public function ajouterEnseignant(Instructor $enseignant): static
    {
        if (!$this->enseignants->contains($enseignant)) {
            $this->enseignants->add($enseignant);
            $enseignant->setTypeContrat($this);
        }

        return $this;
    }

    public function retirerEnseignant(Instructor $enseignant): static
    {
        if ($this->enseignants->removeElement($enseignant)) {
            if ($enseignant->getTypeContrat() === $this) {
                $enseignant->setTypeContrat(null);
            }
        }

        return $this;
    }

    public function __toString(): string
    {
        return $this->libelle ?? '';
    }
}
