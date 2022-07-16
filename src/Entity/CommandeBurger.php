<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use ApiPlatform\Core\Annotation\ApiResource;
use App\Repository\CommandeBurgerRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: CommandeBurgerRepository::class)]

#[ApiResource(
    collectionOperations: [
    "get" => [
        'method' => 'get',
        'status' => Response::HTTP_OK,
        'normalization_context' => ['groups' => ['comburger:read:simple']],
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
        'denormalization_context' => ['groups' => ['write:simplecombg',]],
        'normalization_context' => ['groups' => ['combg:read:all']],
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
    'normalization_context' => ['groups' => ['allcombg']],
],]
)]
class CommandeBurger
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[Groups(['allcom', 'write:simplecombg','com:read:simple','allcom'])]

    #[ORM\Column(type: 'integer')]
    private $id;

    #[Groups(['comburger:read:simple','allcom', 'write:simplecombg','allcombg','com:read:simple', 'write:simplecom','write:allcom'])]
    #[Assert\Positive(message:'veuillez indiquez une quantité superieur ou egale à 1')]
    #[ORM\Column(type: 'integer', length: 255)]
    private $quantite;

   
    #[ORM\ManyToOne(targetEntity: Commande::class, inversedBy: 'CommandeBurger')]
    private $commande;

    #[Groups(['comburger:read:simple','allcom', 'write:simplecombg','allcombg','com:read:simple', 'write:simplecom','write:allcom'])]
    #[ORM\ManyToOne(targetEntity: Burger::class, inversedBy: 'CommandeBurgers')]
    private $burger;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getQuantite(): ?string
    {
        return $this->quantite;
    }

    public function setQuantite(string $quantite): self
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

    public function getBurger(): ?Burger
    {
        return $this->burger;
    }

    public function setBurger(?Burger $burger): self
    {
        $this->burger = $burger;

        return $this;
    }
}
