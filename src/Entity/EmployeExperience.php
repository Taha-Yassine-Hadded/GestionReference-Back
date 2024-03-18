<?php

namespace App\Entity;

use App\Repository\EmployeExperienceRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;

#[ORM\Entity(repositoryClass: EmployeExperienceRepository::class)]
class EmployeExperience
{

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

      #[ORM\Column(length: 255, nullable: true)]
      private ?string $employeExperiencePoste = null;
  
      #[ORM\Column(length: 255, nullable: true)]
      private ?string $employeExperienceSociete = null;
  
      #[ORM\Column(length: 255, nullable: true)]
      private ?string $employeExperienceOragnismeEmployeur = null;
  
      #[ORM\Column(type: 'datetime', nullable: true)]
      private ?\DateTimeInterface $employeExperiencePeriode = null;
  
      #[ORM\Column(length: 255, nullable: true)]
      private ?string $employeExperienceFonctionOccupe = null;
  
      #[ORM\ManyToOne(targetEntity: Employe::class)]
      private ?Employe $employe = null;
  
      public function __toString()
      {
          return $this->id;
      }
  
      public function getId(): ?int
      {
          return $this->id;
      }

    public function getEmployeExperiencePoste(): ?string
    {
        return $this->employeExperiencePoste;
    }

    public function setEmployeExperiencePoste(?string $employeExperiencePoste): static
    {
        $this->employeExperiencePoste = $employeExperiencePoste;

        return $this;
    }

    public function getEmployeExperienceSociete(): ?string
    {
        return $this->employeExperienceSociete;
    }

    public function setEmployeExperienceSociete(?string $employeExperienceSociete): static
    {
        $this->employeExperienceSociete = $employeExperienceSociete;

        return $this;
    }

    public function getEmployeExperienceOragnismeEmployeur(): ?string
    {
        return $this->employeExperienceOragnismeEmployeur;
    }

    public function setEmployeExperienceOragnismeEmployeur(?string $employeExperienceOragnismeEmployeur): static
    {
        $this->employeExperienceOragnismeEmployeur = $employeExperienceOragnismeEmployeur;

        return $this;
    }

    public function getEmployeExperiencePeriode(): ?\DateTimeInterface
    {
        return $this->employeExperiencePeriode;
    }

    public function setEmployeExperiencePeriode(?\DateTimeInterface $employeExperiencePeriode): static
    {
        $this->employeExperiencePeriode = $employeExperiencePeriode;

        return $this;
    }

    public function getEmployeExperienceFonctionOccupe(): ?string
    {
        return $this->employeExperienceFonctionOccupe;
    }

    public function setEmployeExperienceFonctionOccupe(?string $employeExperienceFonctionOccupe): static
    {
        $this->employeExperienceFonctionOccupe = $employeExperienceFonctionOccupe;

        return $this;
    }
    public function getEmploye(): ?Employe
    {
        return $this->employe;
    }

    public function setEmploye(?Employe $employe): self
    {
        $this->employe = $employe;

        return $this;
    }
}
