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
    #[ORM\Column(name: "categorieId", type: 'integer')]
    private ?int $id;

    #[ORM\Column(name: "categorieLibelle", length: 255,unique: true)]
    #[Assert\NotBlank]
    private ?string $categorieLibelle;

    #[ORM\Column(name: "categorieShort", length: 255,unique: true)]
    #[Assert\NotBlank]
    private ?string $categorieShort;

    #[ORM\Column(name: "categorieCodeRef", length: 255,unique: true)]
    #[Assert\NotBlank]
    private ?string $categorieCodeRef;

    #[ORM\Column(name: "categorieCodeCouleur", length: 255,unique: true)]
    #[Assert\NotBlank]
    private ?string $categorieCodeCouleur;

    #[ORM\OneToMany(targetEntity: Reference::class, mappedBy: "client")]
    private $references;

    public function __construct()
    {
        $this->references = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCategorieLibelle(): ?string
    {
        return $this->categorieLibelle;
    }

    public function setCategorieLibelle(string $categorieLibelle): self
    {
        $this->categorieLibelle = $categorieLibelle;

        return $this;
    }

    public function getCategorieShort(): ?string
    {
        return $this->categorieShort;
    }

    public function setCategorieShort(string $categorieShort): self
    {
        $this->categorieShort = $categorieShort;

        return $this;
    }

    public function getCategorieCodeRef(): ?string
    {
        return $this->categorieCodeRef;
    }

    public function setCategorieCodeRef(string $categorieCodeRef): self
    {
        $this->categorieCodeRef = $categorieCodeRef;

        return $this;
    }

    public function getCategorieCodeCouleur(): ?string
    {
        return $this->categorieCodeCouleur;
    }

    public function setCategorieCodeCouleur(string $categorieCodeCouleur): self
    {
        $this->categorieCodeCouleur = $categorieCodeCouleur;

        return $this;
    }

    /**
     * @return Collection|Reference[]
     */
    public function getReferences(): Collection
    {
        return $this->references;
    }

    public function addReference(Reference $reference): self
    {
        if (!$this->references->contains($reference)) {
            $this->references[] = $reference;
            $reference->setClient($this);
        }

        return $this;
    }

    public function removeReference(Reference $reference): self
    {
        if ($this->references->removeElement($reference)) {
            $reference->removeCategory($this);
        }

        return $this;
    }
}
