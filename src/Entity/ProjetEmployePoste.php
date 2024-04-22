<?php

namespace App\Entity;

use App\Repository\ProjetEmployePosteRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: ProjetEmployePosteRepository::class)]
class ProjetEmployePoste
{
        #[ORM\Id]
        #[ORM\GeneratedValue]
        #[ORM\Column]
        private ?int $id = null;
    
        #[ORM\Column(length: 255)]
        private ?string $duree = null;
    
        #[ORM\ManyToOne(targetEntity: Employe::class, inversedBy: 'projetsEmployePostes')]
        private ?Employe $employe = null;
    
        #[ORM\ManyToOne(targetEntity: Projet::class, inversedBy: 'projetsEmployePostes')]
        private ?Projet $projet = null;
    
        #[ORM\ManyToOne(targetEntity: Poste::class, inversedBy: 'projetsEmployePostes')]
        private ?Poste $poste = null;

    public function __toString()
    {
        return $this->id;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDuree(): ?string
    {
        return $this->duree;
    }

    public function setDuree(?string $duree): self
    {
        $this->duree = $duree;

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

    public function getProjet(): ?Projet
    {
        return $this->projet;
    }

    public function setProjet(?Projet $projet): self
    {
        $this->projet = $projet;

        return $this;
    }

    public function getPoste(): ?Poste
    {
        return $this->poste;
    }

    public function setPoste(?Poste $poste): self
    {
        $this->poste = $poste;

        return $this;
    }
}
