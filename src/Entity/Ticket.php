<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Repository\TicketRepository;
use ApiPlatform\Core\Annotation\ApiResource;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: TicketRepository::class)]
#[ApiResource(
    collectionOperations: [
    "get" => [
        'method' => 'get',
        'status' => Response::HTTP_OK,
        'normalization_context' => ['groups' => ['ticket:read:simple']],
    ],
 
],
itemOperations: ["put" => [
  
], "get" => [
    'method' => 'get',
    "path" => "/commandes/{id}",
    'requirements' => ['id' => '\d+'],
    'normalization_context' => ['groups' => ['alltick']],
],]
)]

class Ticket
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;
    
    #[Groups(['ticket:read:simple','alltick'],)]
    #[ORM\Column(type: 'date')]
    private $dateTicket;

    #[Groups(['ticket:read:simple','alltick'],)]
    #[ORM\Column(type: 'string', length: 255)]
    private $reference;

    #[Groups(['ticket:read:simple','alltick'],)]
    #[ORM\Column(type: 'integer')]
    private $numero;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDateTicket(): ?\DateTimeInterface
    {
        return $this->dateTicket;
    }

    public function setDateTicket(\DateTimeInterface $dateTicket): self
    {
        $this->dateTicket = $dateTicket;

        return $this;
    }

    public function getReference(): ?string
    {
        return $this->reference;
    }

    public function setReference(string $reference): self
    {
        $this->reference = $reference;

        return $this;
    }

    public function getNumero(): ?int
    {
        return $this->numero;
    }

    public function setNumero(int $numero): self
    {
        $this->numero = $numero;

        return $this;
    }

    
}
