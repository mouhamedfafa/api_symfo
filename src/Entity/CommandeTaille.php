<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use ApiPlatform\Core\Annotation\ApiResource;
use App\Repository\CommandeTailleRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: CommandeTailleRepository::class)]


#[ApiResource(
    collectionOperations: [
    "get" => [
        'method' => 'get',
        'status' => Response::HTTP_OK,
        'normalization_context' => ['groups' => ['comtaille:read:simple']],
    ],
    // "add" => [
    //     'method' => 'Post',
    //     "path" => "/add",
    //     "controller" => MenuController::class,
    // ], 
    "post" => [
        // "access_control" => "is_granted('ROLE_GESTIONNAIRE')",
        //"security_message"=>"Vous n'avez pas access à cette Ressource",
        'method'=>'post',
        'status' => Response::HTTP_CREATED,
        'denormalization_context' => ['groups' => ['write:simplecomtail',]],
        'normalization_context' => ['groups' => ['comtail:read:all']],
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
    "path" => "/commandeBurgers/{id}",
    'requirements' => ['id' => '\d+'],
    'normalization_context' => ['groups' => ['allcomtail']],
],]
)]
class CommandeTaille
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[Groups(['comtaille:read:simple','com:read:simple','allcom'])]
    #[ORM\Column(type: 'integer')]
    private $id;

    #[Groups(['comtaille:read:simple','allcom', 'write:simplecomtail','allcomtail','com:read:simple', 'write:simplecom','write:allcom'])]
    #[ORM\Column(type: 'integer')]
    #[Assert\Positive(message:'veuillez indiquez une quantité superieur ou egale à 1')]  
    private $quantite;

    #[ORM\ManyToOne(targetEntity: Commande::class, inversedBy: 'CommandeTaille')]
    private $commande;

    #[Groups(['comtaille:read:simple','allcom', 'write:simplecomtail','allcomtail','com:read:simple', 'write:simplecom','write:allcom'])]
    #[ORM\ManyToOne(targetEntity: Taille::class, inversedBy: 'CommandeTailles',cascade:["persist"])]
    private $taille;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getQuantite(): ?int
    {
        return $this->quantite;
    }

    public function setQuantite(int $quantite): self
    {
        $this->quantite = $quantite;

        return $this;
    }

    public function getCommande(): ?Commande
    {
        return $this->commande;
    }

    public function setCommande(?Commande $commande): self
    {
        $this->commande = $commande;

        return $this;
    }

    public function getTaille(): ?Taille
    {
        return $this->taille;
    }

    public function setTaille(?Taille $taille): self
    {
        $this->taille = $taille;

        return $this;
    }
}
