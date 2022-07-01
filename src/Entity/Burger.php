<?php

namespace App\Entity;

use App\Entity\User;
use Doctrine\ORM\Mapping as ORM;
use App\Repository\BurgerRepository;
use Doctrine\Common\Collections\Collection;
use ApiPlatform\Core\Annotation\ApiResource;
use Symfony\Component\HttpFoundation\Response;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: BurgerRepository::class)]
#[ApiResource(
    collectionOperations: [
        "get" => [
            'method' => 'get',
            'status' => Response::HTTP_OK,
            'normalization_context' => ['groups' => ['burger:read:simple']],
        ],
        "add" => [
            'method' => 'Post',
            "path" => "/add",
            "controller" => BurgerController::class,
        ], "post" => [
            // "access_control" => "is_granted('ROLE_GESTIONNAIRE')",
            //"security_message"=>"Vous n'avez pas access à cette Ressource",
            'status' => Response::HTTP_CREATED,
            'denormalization_context' => ['groups' => ['write:simple', 'write:all']],
            'normalization_context' => ['groups' => ['burger:read:all']],
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


class Burger
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    #[Groups(['burger:read:simple'])]
    private $id;


    #[Groups(['burger:read:simple', 'write:simple', 'write:all'])]
    #[ORM\Column(type: 'string', length: 255)]
    private $nom;

    #[Groups(['burger:read:simple', 'write:simple', 'write:all'])]
    #[ORM\Column(type: 'float')]
    private $prix;

    #[Groups(['burger:read:simple'])]
    #[ORM\Column(type: 'boolean')]
    private $isEtat;

    // #[Groups(['burger:read:simple'])]
    #[Groups(['burger:read:simple'])]
    #[ORM\ManyToOne(targetEntity: User::class, inversedBy: 'burgers')]
    private $user;

    #[ORM\ManyToMany(targetEntity: Menu::class, mappedBy: 'burgers')]
    private $menus;

    #[ORM\Column(type: 'blob')]
    private $image;

    public function __construct()
    {
        $this->menus = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNom(): ?string
    {
        return $this->nom;
    }

    public function setNom(string $nom): self
    {
        $this->nom = $nom;

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

    public function isIsEtat(): ?bool
    {
        return $this->isEtat;
    }

    public function setIsEtat(bool $isEtat): self
    {
        $this->isEtat = $isEtat;

        return $this;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): self
    {
        $this->user = $user;

        return $this;
    }

    /**
     * @return Collection<int, Menu>
     */
    public function getMenus(): Collection
    {
        return $this->menus;
    }

    public function addMenu(Menu $menu): self
    {
        if (!$this->menus->contains($menu)) {
            $this->menus[] = $menu;
            $menu->addBurger($this);
        }

        return $this;
    }

    public function removeMenu(Menu $menu): self
    {
        if ($this->menus->removeElement($menu)) {
            $menu->removeBurger($this);
        }

        return $this;
    }

    public function getImage()
    {
        return $this->image;
    }

    public function setImage($image): self
    {
        $this->image = $image;

        return $this;
    }
}
