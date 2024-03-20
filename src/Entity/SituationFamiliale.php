<?php

namespace App\Entity;

use App\Repository\SituationFamilialeRepository;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\DBAL\Types\Types;

#[ORM\Entity(repositoryClass: SituationFamilialeRepository::class)]
class SituationFamiliale
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    
    #[ORM\Column(length: 255)]
    #[Assert\NotBlank]
    private ?string $situationFamiliale = null;

    #[ORM\OneToMany(targetEntity: Employe::class, mappedBy: 'situationFamiliale',cascade: ["persist","remove"])]
    private Collection $employes;

    public function __construct()
    {
        $this->employes = new ArrayCollection();
    }


    public function __toString()
    {
        return $this->id;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getSituationFamiliale(): ?string
    {
        return $this->situationFamiliale;
    }

    public function setSituationFamiliale(?string $situationFamiliale): static
    {
        $this->situationFamiliale = $situationFamiliale;

        return $this;
    }
     /**
     * @return Collection|Employe[]
     */
    public function getEmployes(): Collection
    {
        return $this->employes;
    }

    public function addEmploye(Employe $employe): self
    {
        if (!$this->employes->contains($employe)) {
            $this->employes[] = $employe;
            $employe->setNationalite($this);
        }

        return $this;
    }

    public function removeEmploye(Employe $employe): self
    {
        if ($this->employes->removeElement($employe)) {
            // set the owning side to null (unless already changed)
            if ($employe->getNationalite() === $this) {
                $employe->setNationalite(null);
            }
        }

        return $this;
    }
}
