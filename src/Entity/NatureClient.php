<?php

namespace App\Entity;

use App\Repository\NatureClientRepository;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\DBAL\Types\Types;

#[ORM\Entity(repositoryClass: NatureClientRepository::class)]
class NatureClient
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $natureClientId = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank]
    private ?string $natureClient = null;

    #[ORM\OneToMany(targetEntity: Client::class, mappedBy: "natureClient",cascade: ["persist","remove"])]
    private $clients;

    public function __construct()
    {
        $this->clients = new ArrayCollection();
    }

    public function getNatureClientId(): ?int
    {
        return $this->natureClientId;
    }

    public function getNatureClient(): ?string
    {
        return $this->natureClient;
    }

    public function setNatureClient(string $natureClient): static
    {
        $this->natureClient = $natureClient;

        return $this;
    }
    /**
     * @return Collection|Client[]
     */
    public function getClients(): Collection
    {
        return $this->clients;
    }

    public function addClient(Client $client): self
    {
        if (!$this->clients->contains($client)) {
            $this->clients[] = $client;
            $client->setNatureClient($this);
        }

        return $this;
    }

    public function removeClient(Client $client): self
    {
        if ($this->clients->removeElement($client)) {
            // set the owning side to null (unless already changed)
            if ($client->getNatureClient() === $this) {
                $client->setNatureClient(null);
            }
        }

        return $this;
    }
}
