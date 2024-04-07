<?php

namespace App\Entity;

use App\Repository\EmployeRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection; // Add this line
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: EmployeRepository::class)]
class Employe
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank]
    private ?string $personneContact = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    #[Assert\NotBlank]
    private ?\DateTimeInterface $employeDateNaissance = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank]
    private ?string $employeAdresse = null;

    #[ORM\Column(type: Types::TEXT)]
    #[Assert\NotBlank]
    private ?string $employePrincipaleQualification = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank]
    private ?string $employeFormation = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank]
    private ?string $employeAffiliationDesAssociationsGroupPro = null;

    #[ORM\ManyToOne(targetEntity: Nationalite::class)]
    #[Assert\NotBlank]
    private ?Nationalite $nationalite;

    #[ORM\ManyToOne(targetEntity: SituationFamiliale::class)]
    #[Assert\NotBlank]
    private ?SituationFamiliale $situationFamiliale;

    #[ORM\ManyToOne(targetEntity: Poste::class)]
    #[Assert\NotBlank]
    private ?Poste $poste;

    #[ORM\OneToMany(targetEntity: EmployeExperience::class, mappedBy: 'employe',cascade: ["persist","remove"])]
    private Collection $experiences;

    #[ORM\OneToMany(targetEntity: EmployeEducation::class, mappedBy: 'employe',cascade: ["persist","remove"])]
    private Collection $educations;

 
    #[ORM\ManyToMany(targetEntity: Langue::class, inversedBy: 'employe')]
    #[Assert\NotBlank]
    private Collection $langues;
   
    #[ORM\OneToMany(targetEntity: ProjetEmployePoste::class, mappedBy: 'employe',cascade: ["persist","remove"])]
    private Collection $projetsEmployePostes ;

    public function __construct()
    {
        $this->experiences = new ArrayCollection();
        $this->educations = new ArrayCollection();
        $this->langues = new ArrayCollection();
        $this->projetsEmployePostes = new ArrayCollection();
      
    }
    public function __toString()
    {
        return $this->id;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getPersonneContact(): ?string
    {
        return $this->personneContact;
    }

    public function setPersonneContact(string $personneContact): static
    {
        $this->personneContact = $personneContact;

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
 * @return Collection|Langue[]
 */
public function getLangues(): Collection
{
    return $this->langues;
}

public function addLangue(Langue $langue): self
{
    if (!$this->langues->contains($langue)) {
        $this->langues[] = $langue;
        // Vous n'avez pas besoin de setter l'employé sur la langue car il n'y a pas d'entité intermédiaire
    }

    return $this;
}

public function removeLangue(Langue $langue): self
{
    $this->langues->removeElement($langue);
    // Vous n'avez pas besoin de setter l'employé sur la langue car il n'y a pas d'entité intermédiaire

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

    public function getProjetsEmployePostes(): Collection
    {
        return $this->projetsEmployePostes;
    }

    // Méthode pour ajouter un ProjetEmployePoste à la collection
    public function addProjetEmployePoste(ProjetEmployePoste $projetEmployePoste): self
    {
        if (!$this->projetsEmployePostes->contains($projetEmployePoste)) {
            $this->projetsEmployePostes[] = $projetEmployePoste;
            $projetEmployePoste->setProjet($this); // Assurez-vous que le projet est défini pour le projetEmployePoste ajouté
        }

        return $this;
    }

    // Méthode pour retirer un ProjetEmployePoste de la collection
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
}
