<?php

namespace App\Entity;

use App\Repository\LangueRepository;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\DBAL\Types\Types;

#[ORM\Entity(repositoryClass: LangueRepository::class)]
class Langue
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank]
    private ?string $langueNom = null;
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
    
    public function getLangue(): ?string
    {
        return $this->langueNom;
    }

    public function setLangueNom(string $langueNom): static
    {
        $this->langueNom = $langueNom;

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
     /**
     * @return Collection|EmployeLangue[]
     */
    public function getLangues(): Collection
    {
        return $this->langues;
    }

    public function addLangue(EmployeLangue $langue): self
    {
        if (!$this->langues->contains($langue)) {
            $this->langues[] = $langue;
            $langue->setEmploye($this);
        }

        return $this;
    }

    public function removeLangue(EmployeLangue $langue): self
    {
        if ($this->langues->removeElement($langue)) {
            // set the owning side to null (unless already changed)
            if ($langue->getEmploye() === $this) {
                $langue->setEmploye(null);
            }
        }

        return $this;
    }
}
