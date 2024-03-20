<?php

namespace App\Entity;

use App\Repository\PaysRepository;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\DBAL\Types\Types;

#[ORM\Entity(repositoryClass: PaysRepository::class)]
class Pays
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $paysId = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank]
    private ?string $paysNom = null;

    #[ORM\OneToMany(targetEntity: Lieu::class, mappedBy: "pays",cascade: ["persist","remove"])]
    private $lieux;

    public function __construct()
    {
        $this->lieux = new ArrayCollection();
    }


    public function getPaysId(): ?int
    {
        return $this->paysId;
    }

    public function getPaysNom(): ?string
    {
        return $this->paysNom;
    }

    public function setPaysNom(string $paysNom): static
    {
        $this->paysNom = $paysNom;

        return $this;
    }
     /**
     * @return Collection|Lieu[]
     */
    public function getLieux(): Collection
    {
        return $this->lieux;
    }

    public function addLieu(Lieu $lieu): self
    {
        if (!$this->lieux->contains($lieu)) {
            $this->lieux[] = $lieu;
            $lieu->setPays($this);
        }

        return $this;
    }

    public function removeLieu(Lieu $lieu): self
    {
        if ($this->lieux->removeElement($lieu)) {
            // set the owning side to null (unless already changed)
            if ($lieu->getPays() === $this) {
                $lieu->setPays(null);
            }
        }

        return $this;
    }
    
}
