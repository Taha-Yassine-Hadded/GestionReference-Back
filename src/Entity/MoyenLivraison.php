<?php

namespace App\Entity;

use App\Repository\MoyenLivraisonRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: MoyenLivraisonRepository::class)]
class MoyenLivraison
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $moyenLivraisonId = null;

    #[ORM\Column(length: 255)]
    private ?string $moyenLivraison = null;

    #[ORM\OneToMany(mappedBy: 'moyenLivraison', targetEntity: AppelOffre::class)]
    private Collection $appelOffres;

    public function __construct()
    {
        $this->appelOffres = new ArrayCollection();
    }

    public function getMoyenLivraisonId(): ?int
    {
        return $this->moyenLivraisonId;
    }

    public function getMoyenLivraison(): ?string
    {
        return $this->moyenLivraison;
    }

    public function setMoyenLivraison(?string $moyenLivraison): static
    {
        $this->moyenLivraison = $moyenLivraison;

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
            $appelOffre->setMoyenLivraison($this);
        }

        return $this;
    }

    public function removeAppelOffre(AppelOffre $appelOffre): self
    {
        if ($this->appelOffres->removeElement($appelOffre)) {
            // set the owning side to null (unless already changed)
            if ($appelOffre->getMoyenLivraison() === $this) {
                $appelOffre->setMoyenLivraison(null);
            }
        }

        return $this;
    }
}