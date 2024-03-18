<?php

namespace App\Entity;

use App\Repository\ProjetPreuveRepository;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;

#[ORM\Entity(repositoryClass: ProjetPreuveRepository::class)]
class ProjetPreuve
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $projetPreuveLibelle = null;

    #[ORM\ManyToOne(targetEntity: Projet::class)]
    private ?Projet $projet ; 

    public function __toString()
    {
        return $this->id;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getProjetPreuveLibelle(): ?string
    {
        return $this->projetPreuveLibelle;
    }

    public function setProjetPreuveLibelle(string $projetPreuveLibelle): static
    {
        $this->projetPreuveLibelle = $projetPreuveLibelle;

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
}
