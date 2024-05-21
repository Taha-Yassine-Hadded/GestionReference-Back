<?php

namespace App\Entity;

use App\Repository\CategorieRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: CategorieRepository::class)]
class Categorie
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private ?int $id;

    #[ORM\Column(length: 255,unique: true)]
    #[Assert\NotBlank]
    private ?string $categorie;

    #[ORM\OneToMany(targetEntity: Projet::class, mappedBy: "client")]
    private $projets;

    public function __construct()
    {
        $this->projets = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCategorieNom(): ?string
    {
        return $this->categorie;
    }

    public function setCategorie(string $categorie): self
    {
        $this->categorie = $categorie;

        return $this;
    }

  
    /**
     * @return Collection|Projet[]
     */
    public function getProjets(): Collection
    {
        return $this->projets;
    }

    public function addProjet(Projet $projet): self
    {
        if (!$this->projets->contains($projet)) {
            $this->projets[] = $projet;
            $projet->setClient($this);
        }

        return $this;
    }

    public function removeProjet(Projet $projet): self
    {
        if ($this->projets->removeElement($projet)) {
            // set the owning side to null (unless already changed)
            if ($projet->getClient() === $this) {
                $projet->setClient(null);
            }
        }

        return $this;
    }
    public function addCategorie(ProjetCategorie $categorie): self
    {
        if (!$this->categorie->contains($categorie)) {
            $this->categories[] = $categorie;
            $categorie->setProjet($this);
        }

        return $this;
    }

    public function removeCategorie(ProjetCategorie $categorie): self
    {
        if ($this->langues->removeElement($categorie)) {
            // set the owning side to null (unless already changed)
            if ($langue->getProjet() === $this) {
                $langue->setProjet(null);
            }
        }

        return $this;
    }
}
