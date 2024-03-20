<?php

namespace App\Entity;

use App\Entity\AppelOffreType;
use App\Repository\AppelOffreRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: AppelOffreRepository::class)]
class AppelOffre
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $appelOffreId ;

    #[ORM\Column]
    #[Assert\NotBlank]
    private ?int $appelOffreDevis ;

    #[ORM\Column(type: Types::TEXT)]
    #[Assert\NotBlank]
    private ?string $appelOffreObjet ;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    #[Assert\NotBlank]
    private ?\DateTimeInterface $appelOffreDateRemise ;

    #[ORM\Column()]
    #[Assert\NotBlank]
    private ?int $appelOffreRetire ;

    #[ORM\Column()]
    #[Assert\NotBlank]
    private ?int $appelOffreParticipation ;

    #[ORM\Column()]
    #[Assert\NotBlank]
    private ?int $appelOffreEtat;

    #[ORM\ManyToOne(targetEntity: AppelOffreType::class, inversedBy: 'appelOffres',cascade: ["persist","remove"])]
    #[ORM\JoinColumn(name: "appel_offre_type_id", referencedColumnName: "appel_offre_type_id")]
    private ?AppelOffreType $appelOffreType;
    
    #[ORM\ManyToOne(targetEntity: MoyenLivraison::class, inversedBy: 'appelOffres',cascade: ["persist","remove"])]
    #[ORM\JoinColumn(name:"moyen_livraison_id", referencedColumnName:"moyen_livraison_id")]
    private ?MoyenLivraison $moyenLivraison= null;
    
    #[ORM\ManyToOne(targetEntity: OrganismeDemandeur::class, inversedBy: 'appelOffres',cascade: ["persist","remove"])]
    #[ORM\JoinColumn(name:"organisme_demandeur_id", referencedColumnName:"organisme_demandeur_id")]
    private OrganismeDemandeur $organismeDemandeur;

    public function getAppelOffreId(): ?int
    {
        return $this->appelOffreId;
    }

    public function getAppelOffreDevis(): ?int
    {
        return $this->appelOffreDevis;
    }

    public function setAppelOffreDevis(int $appelOffreDevis): static
    {
        $this->appelOffreDevis = $appelOffreDevis;

        return $this;
    }

    public function getAppelOffreObjet(): ?string
    {
        return $this->appelOffreObjet;
    }

    public function setAppelOffreObjet(?string $appelOffreObjet): static
    {
        $this->appelOffreObjet = $appelOffreObjet;

        return $this;
    }

    public function getAppelOffreDateRemise(): ?\DateTimeInterface
    {
        return $this->appelOffreDateRemise;
    }

    public function setAppelOffreDateRemise(?\DateTimeInterface $appelOffreDateRemise): static
    {
        $this->appelOffreDateRemise = $appelOffreDateRemise;

        return $this;
    }

    public function getAppelOffreRetire(): ?int
    {
        return $this->appelOffreRetire;
    }

    public function setAppelOffreRetire(?int $appelOffreRetire): static
    {
        $this->appelOffreRetire = $appelOffreRetire;

        return $this;
    }

    public function getAppelOffreParticipation(): ?int
    {
        return $this->appelOffreParticipation;
    }

    public function setAppelOffreParticipation(?int $appelOffreParticipation): static
    {
        $this->appelOffreParticipation = $appelOffreParticipation;

        return $this;
    }

    public function getAppelOffreEtat(): ?int
    {
        return $this->appelOffreEtat;
    }

    public function setAppelOffreEtat(?int $appelOffreEtat): static
    {
        $this->appelOffreEtat = $appelOffreEtat;

        return $this;
    }
    public function getAppelOffreType(): ?AppelOffreType
    {
        return $this->appelOffreType;
    }

    public function setAppelOffreType(?AppelOffreType $appelOffreType): self
    {
        $this->appelOffreType = $appelOffreType;

        return $this;
    }
    public function getMoyenLivraison(): ?MoyenLivraison
    {
        return $this->moyenLivraison;
    }

    public function setMoyenLivraison(?MoyenLivraison $moyenLivraison): self
    {
        $this->moyenLivraison = $moyenLivraison;
    
        return $this;
    }
    

    public function getOrganismeDemandeur(): ?OrganismeDemandeur
    {
        return $this->organismeDemandeur;
    }

    public function setOrganismeDemandeur(?OrganismeDemandeur $organismeDemandeur): self
    {
        $this->organismeDemandeur = $organismeDemandeur;

        return $this;
    }
    
}