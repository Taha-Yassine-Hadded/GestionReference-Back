<?php

namespace App\Entity;

use App\Repository\EmployeRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection; // Add this line


#[ORM\Entity(repositoryClass: EmployeRepository::class)]
class Employe
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255, )]
    private ?string $employeNom = null;

    #[ORM\Column(length: 255)]
    private ?string $employePrenom = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private ?\DateTimeInterface $employeDateNaissance = null;

    #[ORM\Column(length: 255)]
    private ?string $employeAdresse = null;

    #[ORM\Column(type: Types::TEXT)]
    private ?string $employePrincipaleQualification = null;

    #[ORM\Column(length: 255)]
    private ?string $employeFormation = null;

    #[ORM\Column(length: 255)]
    private ?string $employeAffiliationDesAssociationsGroupPro = null;

    #[ORM\ManyToOne(targetEntity: Nationalite::class)]
    private ?Nationalite $nationalite;

    #[ORM\ManyToOne(targetEntity: SituationFamiliale::class)]
    private ?SituationFamiliale $situationFamiliale;

    #[ORM\ManyToOne(targetEntity: Poste::class)]
    private ?Poste $poste;

    #[ORM\OneToMany(targetEntity: EmployeExperience::class, mappedBy: 'employe')]
    private Collection $experiences;

    #[ORM\OneToMany(targetEntity: EmployeEducation::class, mappedBy: 'employe')]
    private Collection $educations;

 
    #[ORM\ManyToMany(targetEntity: Langue::class, inversedBy: 'employes')]
    private Collection $langues;
   

    public function __construct()
    {
        $this->experiences = new ArrayCollection();
        $this->educations = new ArrayCollection();
        $this->langues = new ArrayCollection();
        $this->projets = new ArrayCollection();
      
    }
    public function __toString()
    {
        return $this->id;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getEmployeNom(): ?string
    {
        return $this->employeNom;
    }

    public function setEmployeNom(?string $employeNom): static
    {
        $this->employeNom = $employeNom;

        return $this;
    }

    public function getEmployePrenom(): ?string
    {
        return $this->employePrenom;
    }

    public function setEmployePrenom(?string $employePrenom): static
    {
        $this->employePrenom = $employePrenom;

        return $this;
    }

    public function getEmployeDateNaissance(): ?\DateTimeInterface
    {
        return $this->employeDateNaissance;
    }

    public function setEmployeDateNaissance(\DateTimeInterface $employeDateNaissance): static
    {
        $this->employeDateNaissance = $employeDateNaissance;

        return $this;
    }

    public function getEmployeAdresse(): ?string
    {
        return $this->employeAdresse;
    }

    public function setEmployeAdresse(?string $employeAdresse): static
    {
        $this->employeAdresse = $employeAdresse;

        return $this;
    }

    public function getEmployePrincipaleQualification(): ?string
    {
        return $this->employePrincipaleQualification;
    }

    public function setEmployePrincipaleQualification(?string $employePrincipaleQualification): static
    {
        $this->employePrincipaleQualification = $employePrincipaleQualification;

        return $this;
    }

    public function getEmployeFormation(): ?string
    {
        return $this->employeFormation;
    }

    public function setEmployeFormation(?string $employeFormation): static
    {
        $this->employeFormation = $employeFormation;

        return $this;
    }

    public function getEmployeAffiliationDesAssociationsGroupPro(): ?string
    {
        return $this->employeAffiliationDesAssociationsGroupPro;
    }

    public function setEmployeAffiliationDesAssociationsGroupPro(?string $employeAffiliationDesAssociationsGroupPro): static
    {
        $this->employeAffiliationDesAssociationsGroupPro = $employeAffiliationDesAssociationsGroupPro;

        return $this;
    }
    public function getNationalite(): ?Nationalite
    {
        return $this->nationalite;
    }

    public function setNationalite(?Nationalite $nationalite): self
    {
        $this->nationalite = $nationalite;

        return $this;
    }

    public function getSituationFamiliale(): ?SituationFamiliale
    {
        return $this->situationFamiliale;
    }

    public function setSituationFamiliale(?SituationFamiliale $situationFamiliale): self
    {
        $this->situationFamiliale = $situationFamiliale;

        return $this;
    }
    /**
     * @return Collection|EmployeExperience[]
     */
    public function getExperiences(): Collection
    {
        return $this->experiences;
    }

    public function addExperience(EmployeExperience $experience): self
    {
        if (!$this->experiences->contains($experience)) {
            $this->experiences[] = $experience;
            $experience->setEmploye($this);
        }

        return $this;
    }

    public function removeExperience(EmployeExperience $experience): self
    {
        if ($this->experiences->removeElement($experience)) {
            // set the owning side to null (unless already changed)
            if ($experience->getEmploye() === $this) {
                $experience->setEmploye(null);
            }
        }

        return $this;
    }
     /**
     * @return Collection|EmployeEducation[]
     */
    public function getEducations(): Collection
    {
        return $this->educations;
    }

    public function addEducation(EmployeEducation $education): self
    {
        if (!$this->educations->contains($education)) {
            $this->educations[] = $education;
            $education->setEmploye($this);
        }

        return $this;
    }

    public function removeEducation(EmployeEducation $education): self
    {
        if ($this->educations->removeElement($education)) {
            // set the owning side to null (unless already changed)
            if ($education->getEmploye() === $this) {
                $education->setEmploye(null);
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
    public function getPoste(): ?Poste
{
    return $this->poste;
}

public function setPoste(?Poste $poste): self
{
    $this->poste = $poste;

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
