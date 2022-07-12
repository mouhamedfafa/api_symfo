<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use ApiPlatform\Core\Annotation\ApiResource;
use App\Repository\MenuPortionFriteRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: MenuPortionFriteRepository::class)]


#[ApiResource(
    collectionOperations: [
    "get" => [
        'method' => 'get',
        'status' => Response::HTTP_OK,
        'normalization_context' => ['groups' => ['menumpf:read:simple']],
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
        'denormalization_context' => ['groups' => ['write:simplempf', 'write:allpf']],
        'normalization_context' => ['groups' => ['menumpf:read:all']],
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


class MenuPortionFrite
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    #[Groups(['menumpf:read:simple','write:simplem'])]
    private $id;

    #[Groups(['menumpf:read:simple','write:simplempf', 'write:allpf','write:simplem'])]
    #[ORM\Column(type: 'integer',nullable:true)]
    private $quantite;

    #[Groups(['menumpf:read:simple','write:simplempf', 'write:allpf','write:simplem'])]
    #[ORM\Column(type: 'float',nullable:true)]
    private $prix;

    #[Groups(['menumpf:read:simple'])]
    #[ORM\ManyToOne(targetEntity: Menu::class, inversedBy: 'menuPortionFrites')]
    private $menu;

    #[Groups(['menumpf:read:simple','write:simplempf', 'write:allmpf','write:simplem'])]
    #[ORM\ManyToOne(targetEntity: PortionFrite::class, inversedBy: 'menuPortionFrites',cascade:["persist"])]
    private $portionFrite;


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

    public function getPortionFrite(): ?PortionFrite
    {
        return $this->portionFrite;
    }

    public function setPortionFrite(?PortionFrite $portionFrite): self
    {
        $this->portionFrite = $portionFrite;

        return $this;
    }

   
}
