<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Repository\MenuTailleRepository;
use ApiPlatform\Core\Annotation\ApiResource;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: MenuTailleRepository::class)]
#[ApiResource(
    collectionOperations: [
    "get" => [
        'method' => 'get',
        'status' => Response::HTTP_OK,
        'normalization_context' => ['groups' => ['menumt:read:simple']],
    ],
    "add" => [
        'method' => 'Post',
        "path" => "/add",
        "controller" => MenuController::class,
    ], 
    "post" => [
        // "access_control" => "is_granted('ROLE_GESTIONNAIRE')",
        //"security_message"=>"Vous n'avez pas access à cette Ressource",
        'method'=>'post',
        'status' => Response::HTTP_CREATED,
        'denormalization_context' => ['groups' => ['write:simplemt', 'write:allmt']],
        'normalization_context' => ['groups' => ['menu:read:all']],
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
    "path" => "/menus/{id}",
    'requirements' => ['id' => '\d+'],
    'normalization_context' => ['groups' => ['all']],
],]
)]

class MenuTaille
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    #[Groups(['menumt:read:simple','write:simplem',])]
    private $id;

    #[Groups(['menumt:read:simple','write:simplemt','write:simplem'])]
    #[ORM\Column(type: 'integer',nullable:true)]
    private $quantite;

    #[Groups(['menumt:read:simple','write:simplemt','write:simplem'])]
    #[ORM\Column(type: 'float',nullable:true)]
    private $prix;

    // #[Groups(['menumt:read:simple','write:simplemt','write:simplem'])]
    #[ORM\ManyToOne(targetEntity: Menu::class, inversedBy: 'menuTailles')]
    private $menu;


    #[Groups(['menumt:read:simple','write:simplemt','write:simplem'])]
    #[ORM\ManyToOne(targetEntity: Taille::class, inversedBy: 'menuTailles',cascade:["persist"])]
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

    public function getPrix(): ?float
    {
        return $this->prix;
    }

    public function setPrix(float $prix): self
    {
        $this->prix = $prix;

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
