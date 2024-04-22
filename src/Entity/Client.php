<?php

namespace App\Entity;

use App\Repository\ClientRepository;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\DBAL\Types\Types;

#[ORM\Entity(repositoryClass: ClientRepository::class)]
class Client
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id ;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank]
    private ?string $personneContact = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank]
    private ?string $clientRaisonSociale = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank]
    private ?string $clientAdresse = null;

    #[ORM\Column(length: 20)] // Changer la longueur selon les besoins
    #[Assert\NotBlank]
    #[Assert\Regex('/^\d{8,}$/')] // Au moins 10 chiffres
    private ?string $clientTelephone = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank]
    #[Assert\Email]
    private ?string $clientEmail = null;

    #[ORM\OneToMany(targetEntity: Projet::class, mappedBy: "client",cascade: ["persist","remove"])]
    private $projets;

    #[ORM\ManyToOne(targetEntity: NatureClient::class)]
    #[Assert\NotBlank]
    private ?NatureClient $natureClient; 

  

    public function __construct()
    {
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
 

    public function getPersonneContact(): ?string
    {
        return $this->personneContact;
    }

    public function setPersonneContact(string $personneContact): static
    {
        $this->personneContact = $personneContact;

        return $this;
    }

    public function getClientRaisonSociale(): ?string
    {
        return $this->clientRaisonSociale;
    }

    public function setClientRaisonSociale(string $clientRaisonSociale): static
    {
        $this->clientRaisonSociale = $clientRaisonSociale;

        return $this;
    }

    public function getClientAdresse(): ?string
    {
        return $this->clientAdresse;
    }

    public function setClientAdresse(string $clientAdresse): static
    {
        $this->clientAdresse = $clientAdresse;

        return $this;
    }

    public function getClientTelephone(): ?string
    {
        return $this->clientTelephone;
    }

    public function setClientTelephone(string $clientTelephone): static
    {
        $this->clientTelephone = $clientTelephone;

        return $this;
    }

    public function getClientEmail(): ?string
    {
        return $this->clientEmail;
    }

    public function setClientEmail(string $clientEmail): static
    {
        $this->clientEmail = $clientEmail;

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
            $projet->setClient($this);
        }

        return $this;
    }

    public function removeProjet(Projet $projet): self
    {
        if ($this->projets->removeElement($projet)) {
            // set the owning side to null (unless already changed)
            if ($projet->getClient() === $this) {
                $projet->setClient(null);
            }
        }

        return $this;
    }
    public function getNatureClient(): ?NatureClient
    {
        return $this->natureClient;
    }

    public function setNatureClient(?NatureClient $natureClient): self
    {
        $this->natureClient = $natureClient;

        return $this;
    }
}
