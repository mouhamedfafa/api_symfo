<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Repository\QuartierRepository;
use ApiPlatform\Core\Annotation\ApiResource;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: QuartierRepository::class)]
#[ApiResource(
    collectionOperations: [
    "get" => [
        'method' => 'get',
        'status' => Response::HTTP_OK,
        'normalization_context' => ['groups' => ['quar:read:simple']],
    ],
    
     "post" => [
        // "access_control" => "is_granted('ROLE_GESTIONNAIRE')",
        //"security_message"=>"Vous n'avez pas access à cette Ressource",
        'method' => 'Post',
        'status' => Response::HTTP_CREATED,
        'denormalization_context' => ['groups' => ['write:simplqr', 'write:allqr']],
        'normalization_context' => ['groups' => ['quar:read:all']],
        // 'input_formats' => [
        //     'multipart' => ['multipart/form-data'],]
    ]
],
itemOperations: ["put" => [
    'method' => 'put',
    // "security" => "is_granted('ROLE_GESTIONNAIRE')",
    // "security_message"=>"Vous n'avez pas access à cette Ressource",
    'status' => Response::HTTP_OK,
], "get" => [
    'method' => 'get',
    "path" => "/quartiers/{id}",
    'requirements' => ['id' => '\d+'],
    'normalization_context' => ['groups' => ['allqr']],
],]
)]  

class Quartier
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[Groups(['quar:read:simple','allqr'])]
    #[ORM\Column(type: 'integer')]
    private $id;

    #[Groups(['quar:read:simple','write:simplqr','allqr'])]
    #[ORM\Column(type: 'string', length: 255)]
    private $libelle;

    #[Groups(['quar:read:simple','write:simplqr','allqr'])]
    #[ORM\ManyToOne(targetEntity: Zone::class, inversedBy: 'quartiers')]
    private $zone;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getLibelle(): ?string
    {
        return $this->libelle;
    }

    public function setLibelle(string $libelle): self
    {
        $this->libelle = $libelle;

        return $this;
    }

    public function getZone(): ?Zone
    {
        return $this->zone;
    }

    public function setZone(?Zone $zone): self
    {
        $this->zone = $zone;

        return $this;
    }
}
