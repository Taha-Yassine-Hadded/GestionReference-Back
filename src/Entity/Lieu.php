<?php

namespace App\Entity;

use App\Repository\LieuRepository;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\DBAL\Types\Types;

#[ORM\Entity(repositoryClass: LieuRepository::class)]
class Lieu
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $lieuId = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank]
    private ?string $lieuNom = null;

    
    #[ORM\ManyToOne(targetEntity: Pays::class)]
    #[ORM\JoinColumn(name: "pays_id", referencedColumnName: "pays_id", nullable: false)]
    #[Assert\NotBlank]
    private ?Pays $pays = null;

    // Ajout de la relation OneToMany avec Projet
    #[ORM\OneToMany(targetEntity: Projet::class, mappedBy: "lieu",cascade: ["persist","remove"])]
    private $projets;

    public function __construct()
    {
        $this->projets = new ArrayCollection();
    }
 
    public function getLieuId(): ?int
    {
        return $this->lieuId;
    }

    public function getLieuNom(): ?string
    {
        return $this->lieuNom;
    }

    public function setLieuNom(string $lieuNom): static
    {
        $this->lieuNom = $lieuNom;

        return $this;
    }

    public function getPays(): ?Pays
    {
        return $this->pays;
    }

    public function setPays(?Pays $pays): static
    {
        $this->pays = $pays;

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
            $projet->setLieu($this);
        }

        return $this;
    }

    public function removeProjet(Projet $projet): self
    {
        if ($this->projets->removeElement($projet)) {
            // set the owning side to null (unless already changed)
            if ($projet->getLieu() === $this) {
                $projet->setLieu(null);
            }
        }

        return $this;
    }
}
