<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Repository\CommandeMenuRepository;
use ApiPlatform\Core\Annotation\ApiResource;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: CommandeMenuRepository::class)]

#[ApiResource(
    collectionOperations: [
    "get" => [
        'method' => 'get',
        'status' => Response::HTTP_OK,
        'normalization_context' => ['groups' => ['commenu:read:simple']],
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
        'denormalization_context' => ['groups' => ['write:simplecommn',]],
        'normalization_context' => ['groups' => ['commn:read:all']],
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
    "path" => "/commandeMenus/{id}",
    'requirements' => ['id' => '\d+'],
    'normalization_context' => ['groups' => ['allcommn']],
],]
)]

class CommandeMenu
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[Groups(['allcom','com:read:simple','write:allcom','allcom'])]
    #[ORM\Column(type: 'integer')]
    private $id;

    #[Groups(['commenu:read:simple','allcom', 'write:simplecommn','allcommn','com:read:simple', 'write:simplecom','write:allcom'])]
    #[ORM\Column(type: 'integer', length: 255)]
    #[Assert\Positive(message:'veuillez indiquez une quantité superieur ou egale à 1')]
    private $quantite;

    #[ORM\ManyToOne(targetEntity: Commande::class, inversedBy: 'CommandeMenu')]
    private $commande;

    #[Groups(['commenu:read:simple','allcom', 'write:simplecommn','allcommn','com:read:simple', 'write:simplecom','write:allcom'])]
    #[ORM\ManyToOne(targetEntity: Menu::class, inversedBy: 'CommandeMenus')]
    private $menu;

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

    public function getMenu(): ?Menu
    {
        return $this->menu;
    }

    public function setMenu(?Menu $menu): self
    {
        $this->menu = $menu;

        return $this;
    }
}
