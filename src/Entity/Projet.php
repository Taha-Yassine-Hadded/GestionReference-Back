<?php

namespace App\Entity;

use App\Repository\ProjetRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\Common\Collections\Collection;


#[ORM\Entity(repositoryClass: ProjetRepository::class)]
class Projet
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank]
    private ?string $projetLibelle = null;

    #[ORM\Column(type: 'text')]
 #[Assert\NotBlank]
   private ?string $projetDescription = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank]
    private ?string $projetReference = null;

    #[ORM\Column(type: 'date')]
    #[Assert\NotBlank]
    private ?\DateTimeInterface $projetDateDemarrage = null;

    #[ORM\Column(type: 'date')]
    #[Assert\NotBlank]
    private ?\DateTimeInterface $projetDateAchevement = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank]
    private ?string $projetUrlFonctionnel = null;

  #[ORM\Column(type: 'text')]
    #[Assert\NotBlank]
    private ?string $projetDescriptionServiceEffectivementRendus = null;

    #[ORM\ManyToOne(targetEntity: Lieu::class)]
    private ?Lieu $lieu;

    #[ORM\ManyToOne(targetEntity: Client::class)]
    private ?Client $client; 

    #[ORM\OneToMany(targetEntity: ProjetEmployePoste::class, mappedBy: 'projet', cascade: ["persist","remove"])]
    private Collection $projetsEmployePostes;

    #[ORM\OneToMany(targetEntity: ProjetPreuve::class, mappedBy: "projet", cascade: ["persist","remove"])]
    private Collection $projetPreuves;

    #[ORM\ManyToMany(targetEntity: Categorie::class, inversedBy: 'projets')]
    #[Assert\NotBlank]
    private $categories;

    public function __construct()
    {
        $this->projetPreuves = new ArrayCollection();
       $this->categories = new ArrayCollection();
       $this->projetsEmployePostes = new ArrayCollection();
       $this->employes = new ArrayCollection();
    
    
    }

    public function clearCategories(): void
    {
        $this->categories->clear();
    }
    public function __toString()
    {
        return $this->id;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getProjetLibelle(): ?string
    {
        return $this->projetLibelle;
    }

    public function setProjetLibelle(string $projetLibelle): static
    {
        $this->projetLibelle = $projetLibelle;

        return $this;
    }


    public function getProjetDescirption(): ?string
    {
        return $this->projetDescription;
    }
    
    public function setProjetDescription(?string $projetDescription): self
    {
        $this->projetDescription = $projetDescription;
    
        return $this;
    }
    
    public function getProjetReference(): ?string
    {
        return $this->projetReference;
    }

    public function setProjetReference(string $projetReference): static
    {
        $this->projetReference = $projetReference;

        return $this;
    }

    public function getProjetDateDemarrage(): ?\DateTimeInterface
    {
        return $this->projetDateDemarrage;
    }

    public function setProjetDateDemarrage(\DateTimeInterface $projetDateDemarrage): static
    {
        $this->projetDateDemarrage = $projetDateDemarrage;

        return $this;
    }

    public function getProjetDateAchevement(): ?\DateTimeInterface
    {
        return $this->projetDateAchevement;
    }

    public function setProjetDateAchevement(\DateTimeInterface $projetDateAchevement): static
    {
        $this->projetDateAchevement = $projetDateAchevement;

        return $this;
    }

    public function getProjetUrlFonctionnel(): ?string
    {
        return $this->projetUrlFonctionnel;
    }

    public function setProjetUrlFonctionnel(string $projetUrlFonctionnel): static
    {
        $this->projetUrlFonctionnel = $projetUrlFonctionnel;

        return $this;
    }

    public function getProjetDescriptionServiceEffectivementRendus(): ?string
    {
        return $this->projetDescriptionServiceEffectivementRendus;
    }
    

    public function setProjetDescriptionServiceEffectivementRendus(string $projetDescriptionServiceEffectivementRendus): static
    {
        $this->projetDescriptionServiceEffectivementRendus = $projetDescriptionServiceEffectivementRendus;

        return $this;
    }

    // Méthode pour obtenir les projetsEmployePostes
    public function getProjetsEmployePostes(): Collection
    {
        return $this->projetsEmployePostes;
    }

    // Méthode pour ajouter un projetEmployePoste à la collection
    public function addProjetEmployePoste(ProjetEmployePoste $projetEmployePoste): self
    {
        if (!$this->projetsEmployePostes->contains($projetEmployePoste)) {
            $this->projetsEmployePostes[] = $projetEmployePoste;
            $projetEmployePoste->setProjet($this); // Assurez-vous que le projet est défini pour le projetEmployePoste ajouté
        }

        return $this;
    }

    // Méthode pour retirer un projetEmployePoste de la collection
    public function removeProjetEmployePoste(ProjetEmployePoste $projetEmployePoste): self
    {
        if ($this->projetsEmployePostes->contains($projetEmployePoste)) {
            $this->projetsEmployePostes->removeElement($projetEmployePoste);
            // Mise à jour de la relation de ProjetEmployePoste avec le projet à NULL lorsqu'il est retiré
            if ($projetEmployePoste->getProjet() === $this) {
                $projetEmployePoste->setProjet(null);
            }
        }

        return $this;
    }

    public function getLieu(): ?Lieu
    {
        return $this->lieu;
    }

    public function setLieu(?Lieu $lieu): static
    {
        $this->lieu = $lieu;

        return $this;
    }
    public function getClient(): ?Client
    {
        return $this->client;
    }

    public function setClient(?Client $client): self
    {
        $this->client = $client;

        return $this;
    }
    public function addProjetPreuve(ProjetPreuve $projetPreuve): self
    {
        if (!$this->projetPreuves->contains($projetPreuve)) {
            $this->projetPreuves[] = $projetPreuve;
            $projetPreuve->setProjet($this);
        }

        return $this;
    }

    public function removeProjetPreuve(ProjetPreuve $projetPreuve): self
    {
        if ($this->projetPreuves->removeElement($projetPreuve)) {
            // set the owning side to null (unless already changed)
            if ($projetPreuve->getProjet() === $this) {
                $projetPreuve->setProjet(null);
            }
        }

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
            $employe->addProjet($this);
        }

        return $this;
    }

    public function removeEmploye(Employe $employe): self
    {
        if ($this->employes->contains($employe)) {
            $this->employes->removeElement($employe);
            $employe->removeProjet($this);
        }

        return $this;
    }
      /**
     * @return Collection|Categorie[]
     */
    public function getCategories(): Collection
    {
        return $this->categories;
    }

    public function addCategorie(Categorie $categorie): self
    {
        if (!$this->categories->contains($categorie)) {
            $this->categories[] = $categorie;
        }

        return $this;
    }

    public function removeCategorie(Categorie $categorie): self
    {
        $this->categories->removeElement($categorie);

        return $this;
    }


}
