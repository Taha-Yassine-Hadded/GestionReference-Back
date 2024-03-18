<?php

namespace App\Entity;

use App\Repository\ProjetRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;


#[ORM\Entity(repositoryClass: ProjetRepository::class)]
class Projet
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $projetLibelle = null;

    #[ORM\Column(type: Types::TEXT)]
    private ?string $projetDescirption = null;

    #[ORM\Column(length: 255)]
    private ?string $projetReference = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private ?\DateTimeInterface $projetDateDemarrage = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private ?\DateTimeInterface $projetDateAchevement = null;

    #[ORM\Column(length: 255)]
    private ?string $projetUrlFonctionnel = null;

    #[ORM\Column(type: Types::TEXT)]
    private ?string $ProjetDescriptionServiceEffectivementRendus = null;

    #[ORM\ManyToOne(targetEntity: Lieu::class)]
    #[ORM\JoinColumn(name: 'lieu_id', referencedColumnName: 'lieu_id', nullable: false)]
    private ?Lieu $lieu;

    #[ORM\ManyToOne(targetEntity: Client::class)]
    #[ORM\JoinColumn(name: 'client_id', referencedColumnName: 'client_id', nullable: false)]
    private ?Client $client; 

    

    #[ORM\OneToMany(targetEntity: ProjetPreuve::class, mappedBy: "projet")]
    private Collection $projetPreuves;
  

    #[ORM\ManyToMany(targetEntity: Categorie::class, inversedBy: 'projets')]
    private $categories;

    #[ORM\ManyToMany(targetEntity: Employe::class, inversedBy: "projets")]
    #[ORM\JoinTable(name: "projet_employe_poste")]
    private $employes;
 
    public function __construct()
    {
        $this->projetPreuves = new ArrayCollection();
       $this->categories = new ArrayCollection();
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
        return $this->projetDescirption;
    }

    public function setProjetDescirption(string $projetDescirption): static
    {
        $this->projetDescirption = $projetDescirption;

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
        return $this->ProjetDescriptionServiceEffectivementRendus;
    }

    public function setProjetDescriptionServiceEffectivementRendus(string $ProjetDescriptionServiceEffectivementRendus): static
    {
        $this->ProjetDescriptionServiceEffectivementRendus = $ProjetDescriptionServiceEffectivementRendus;

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
