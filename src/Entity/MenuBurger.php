<?php

namespace App\Entity;

use App\Entity\Burger;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use App\Repository\MenuBurgerRepository;
use ApiPlatform\Core\Annotation\ApiResource;
use Symfony\Component\HttpFoundation\Response;
use phpDocumentor\Reflection\DocBlock\Tags\Author;
use Symfony\Component\Serializer\Annotation\Groups;


#[ORM\Entity(repositoryClass: MenuBurgerRepository::class)]
#[ApiResource(
    collectionOperations: [
    "get" => [
        'method' => 'get',
        'status' => Response::HTTP_OK,
        'normalization_context' => ['groups' => ['menuBurger:read:simple']],
    ],
    
     "post" => [
        // "access_control" => "is_granted('ROLE_GESTIONNAIRE')",
        //"security_message"=>"Vous n'avez pas access à cette Ressource",
        'method' => 'Post',
        'status' => Response::HTTP_CREATED,
        'denormalization_context' => ['groups' => ['write:simplemBurger', 'write:allmBurger']],
        'normalization_context' => ['groups' => ['menuBburger:read:all']],
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
    "path" => "/burgers/{id}",
    'requirements' => ['id' => '\d+'],
    'normalization_context' => ['groups' => ['all']],
],]
)]



class MenuBurger
{

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[Groups(['menub:read:simple','write:simplem'])]
    #[ORM\Column(type: 'integer')]
    private $id;


    #[Groups(['menuBurger:read:simple','write:simplemBurger', 'write:allmBurger','write:simplem'])]
    #[ORM\Column(type: 'integer')]
    #[Assert\NotBlank]
    #[Assert\Positive(message:"la quantite doit etre superieur a zero")]

    private $quantite;

    #[Groups(['menuBurger:read:simple','write:simplemBurger', 'write:allmBurger'])]
    #[ORM\Column(type: 'float',nullable:true)]
    private $prix;

    #[Groups(['menuBurger:read:simple','write:simplemBurger', 'write:allmBurger'])]
    #[ORM\ManyToOne(targetEntity: Menu::class, inversedBy: 'menuBurgers')]
    private $menu;

    #[Groups(['menuBurger:read:simple','write:simplemBurger', 'write:allmBurger','write:simplem'])]
    #[ORM\ManyToOne(targetEntity: Burger::class, inversedBy: 'menuBurgers',cascade:["persist"])]
    #[Assert\NotBlank]
    private $burgers;

   

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

    public function getBurgers(): ?Burger
    {
        return $this->burgers;
    }

    public function setBurgers(?Burger $burgers): self
    {
        $this->burgers = $burgers;

        return $this;
    }
  
  
  
}
