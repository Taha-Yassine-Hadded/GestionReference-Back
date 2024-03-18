<?php

namespace App\Entity;

use App\Repository\UploadFileRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: UploadFileRepository::class)]
class UploadFile
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(type: Types::BLOB)]
    private $fichier = null;

    #[ORM\ManyToOne(targetEntity: ProjetPreuve::class)]
    private ?ProjetPreuve $projetPreuve ; 




    public function getId(): ?int
    {
        return $this->id;
    }

    public function getFichier()
    {
        return $this->fichier;
    }

    public function setFichier($fichier): static
    {
        $this->fichier = $fichier;

        return $this;
    }

    public function getProjetPreuve(): ?ProjetPreuve
    {
        return $this->projetPreuve;
    }

    public function setProjetPreuve(?ProjetPreuve $projetPreuve): self
    {
        $this->projetPreuve = $projetPreuve;

        return $this;
    }

    public function getEmployeEducation(): ?EmployeEducation
    {
        return $this->employeEducation;
    }

    public function setEmployeEducation(?EmployeEducation $employeEducation): self
    {
        $this->employeEducation = $employeEducation;

        return $this;
    }
}
