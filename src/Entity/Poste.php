<?php

namespace App\Entity;

use App\Repository\PosteRepository;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;


#[ORM\Entity(repositoryClass: PosteRepository::class)]
class Poste
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $posteNom = null;

    #[ORM\OneToMany(targetEntity: Employe::class, mappedBy: 'nationalite')]
    private Collection $employes;

   

    public function __construct()
    {
        $this->employes = new ArrayCollection();
        $this->projetEmployePostes = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getPosteNom(): ?string
    {
        return $this->posteNom;
    }

    public function setPosteNom(string $posteNom): static
    {
        $this->posteNom = $posteNom;

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
     * @return Collection|ProjetEmployePoste[]
     */
    public function getProjetEmployePostes()
    {
        return $this->projetEmployePostes;
    }

    public function addProjetEmployePoste(ProjetEmployePoste $projetEmployePoste): self
    {
        if (!$this->projetEmployePostes->contains($projetEmployePoste)) {
            $this->projetEmployePostes[] = $projetEmployePoste;
            $projetEmployePoste->setPoste($this);
        }

        return $this;
    }

    public function removeProjetEmployePoste(ProjetEmployePoste $projetEmployePoste): self
    {
        if ($this->projetEmployePostes->removeElement($projetEmployePoste)) {
            // Définit le côté propriétaire à null (sauf si déjà défini)
            if ($projetEmployePoste->getPoste() === $this) {
                $projetEmployePoste->setPoste(null);
            }
        }

        return $this;
    }
}
