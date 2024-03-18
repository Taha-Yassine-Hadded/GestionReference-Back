<?php

namespace App\Entity;

use App\Repository\NationaliteRepository;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;

#[ORM\Entity(repositoryClass: NationaliteRepository::class)]
class Nationalite
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $nationaliteLibelle = null;

    #[ORM\OneToMany(targetEntity: Employe::class, mappedBy: 'nationalite')]
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
    public function getNationaliteLibelle(): ?string
    {
        return $this->nationaliteLibelle;
    }

    public function setNationaliteLibelle(?string $nationaliteLibelle): static
    {
        $this->nationaliteLibelle = $nationaliteLibelle;

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
