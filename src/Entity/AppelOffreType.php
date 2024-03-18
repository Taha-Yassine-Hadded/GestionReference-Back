<?php

namespace App\Entity;

use App\Repository\AppelOffreTypeRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: AppelOffreTypeRepository::class)]
class AppelOffreType
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(name: 'appel_offre_type_id', type: 'integer')]
    private ?int $appelOffreTypeId;

    #[ORM\Column(name: 'appel_offre_type', type: 'string', length: 255)]
    private ?string $appelOffreType;

    #[ORM\OneToMany(mappedBy: 'appelOffreType', targetEntity: AppelOffre::class)]
    private Collection $appelOffres;

    public function __construct()
    {
        $this->appelOffres = new ArrayCollection();
    }

    public function getAppelOffreTypeId(): ?int
    {
        return $this->appelOffreTypeId;
    }

    public function getAppelOffreType(): ?string
    {
        return $this->appelOffreType;
    }

    public function setAppelOffreType(?string $appelOffreType): static
    {
        $this->appelOffreType = $appelOffreType;

        return $this;
    }
   
    /**
     * @return Collection|AppelOffre[]
     */
    public function getAppelOffres(): Collection
    {
        return $this->appelOffres;
    }

    public function addAppelOffre(AppelOffre $appelOffre): self
    {
        if (!$this->appelOffres->contains($appelOffre)) {
            $this->appelOffres[] = $appelOffre;
            $appelOffre->setAppelOffreType($this);
        }

        return $this;
    }

    public function removeAppelOffre(AppelOffre $appelOffre): self
    {
        if ($this->appelOffres->removeElement($appelOffre)) {
            // set the owning side to null (unless already changed)
            if ($appelOffre->getAppelOffreType() === $this) {
                $appelOffre->setAppelOffreType(null);
            }
        }

        return $this;
    }
}
