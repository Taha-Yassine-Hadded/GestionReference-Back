<?php

namespace App\Entity;

use App\Repository\ClientRepository;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;

#[ORM\Entity(repositoryClass: ClientRepository::class)]
class Client
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $clientId ;

    #[ORM\Column(length: 255)]
    private ?string $personneContact = null;

    #[ORM\Column(length: 255)]
    private ?string $clientRaisonSociale = null;

    #[ORM\Column(length: 255)]
    private ?string $clientAdresse = null;

    #[ORM\Column(length: 255)]
    private ?string $clientTelephone = null;

    #[ORM\Column(length: 255)]
    private ?string $clientEmail = null;

    #[ORM\OneToMany(targetEntity: Projet::class, mappedBy: "projet")]
    private $projets;

    #[ORM\ManyToOne(targetEntity: NatureClient::class)]
    #[ORM\JoinColumn(name: "nature_client_id", referencedColumnName: "nature_client_id")]
    private ?NatureClient $natureClient; 

  

    public function __construct()
    {
        $this->projets = new ArrayCollection();
    }
    public function getClientId(): ?int
    {
        return $this->clientId;
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