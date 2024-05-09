<?php

namespace App\Entity;

use App\Repository\UploadFileRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: UploadFileRepository::class)]
class UploadFile
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank]
    private $fileName;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank]
    private $filePath;

    #[ORM\ManyToOne(targetEntity: ProjetPreuve::class)]
    private ?ProjetPreuve $projetPreuve ; 



    public function __toString()
    {
        return $this->id;
    }

  public function getId(): ?int
    {
        return $this->id;
    }

    public function getFileName(): ?string
    {
        return $this->fileName;
    }

    public function setFileName(string $fileName): self
    {
        $this->fileName = $fileName;

        return $this;
    }

    public function getFilePath(): ?string
    {
        return $this->filePath;
    }

    public function setFilePath(string $filePath): self
    {
        $this->filePath = $filePath;

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
