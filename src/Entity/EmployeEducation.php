<?php

namespace App\Entity;

use App\Repository\EmployeEducationRepository;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\DBAL\Types\Types;

#[ORM\Entity(repositoryClass: EmployeEducationRepository::class)]
class EmployeEducation
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank]
    private ?string $employeEducationNatureEtudes = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank]
    private ?string $employeEducationEtablissement = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank]
    private ?string $employeEducationDiplomes = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank]
    private ?string $employeEducationAnneeObtention = null;

    #[ORM\ManyToOne(targetEntity: Employe::class)]
    #[Assert\NotBlank]
    private ?Employe $employe;

    public function __toString()
    {
        return $this->id;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getEmployeEducationNatureEtudes(): ?string
    {
        return $this->employeEducationNatureEtudes;
    }

    public function setEmployeEducationNatureEtudes(?string $employeEducationNatureEtudes): static
    {
        $this->employeEducationNatureEtudes = $employeEducationNatureEtudes;

        return $this;
    }

    public function getEmployeEducationEtablissement(): ?string
    {
        return $this->employeEducationEtablissement;
    }

    public function setEmployeEducationEtablissement(?string $employeEducationEtablissement): static
    {
        $this->employeEducationEtablissement = $employeEducationEtablissement;

        return $this;
    }

    public function getEmployeEducationDiplomes(): ?string
    {
        return $this->employeEducationDiplomes;
    }

    public function setEmployeEducationDiplomes(?string $employeEducationDiplomes): static
    {
        $this->employeEducationDiplomes = $employeEducationDiplomes;

        return $this;
    }

    public function getEmployeEducationAnneeObtention(): ?string
    {
        return $this->employeEducationAnneeObtention;
    }

    public function setEmployeEducationAnneeObtention(?string $employeEducationAnneeObtention): static
    {
        $this->employeEducationAnneeObtention = $employeEducationAnneeObtention;

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
