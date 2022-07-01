<?php

namespace App\Entity;

use App\Entity\Produit;
use Doctrine\ORM\Mapping as ORM;
use App\Repository\BoissonRepository;
use Doctrine\Common\Collections\Collection;
use ApiPlatform\Core\Annotation\ApiResource;
use Symfony\Component\HttpFoundation\Response;
use ApiPlatform\Core\Annotation\ApiSubresource; 
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Serializer\Annotation\Groups;


#[ORM\Entity(repositoryClass: BoissonRepository::class)]
#[ApiResource(
    collectionOperations: [
        "get" => [
            'method' => 'get',
            'status' => Response::HTTP_OK,  
            'normalization_context' => ['groups' => ['boisson:read:simple']],
        ],
        "add" => [
            'method' => 'Post',
            "path" => "/add",
            "controller" => BoissonController::class,
        ], "post" => [
            // "access_control" => "is_granted('ROLE_GESTIONNAIRE')",
            //"security_message"=>"Vous n'avez pas access à cette Ressource",
            'status' => Response::HTTP_CREATED,
            'denormalization_context' => ['groups' => ['write:simple', 'write:all']],
            'normalization_context' => ['groups' => ['boisson:read:all']],
        ]
    ],
    itemOperations: ["put" => [
        'method' => 'put',
        // "security" => "is_granted('ROLE_GESTIONNAIRE')",
        // "security_message"=>"Vous n'avez pas access à cette Ressource",
        'status' => Response::HTTP_OK,
    ], "get" => [
        'method' => 'get',
        "path" => "/boissons/{id}",
        'requirements' => ['id' => '\d+'],
        'normalization_context' => ['groups' => ['all']],
    ],],
    // attributes: ["pagination_items_per_page"=> 5]

)]


   class Boisson 
{ 
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]  
    #[Groups(['boisson:read:simple'])]
    private $id;


    // #[Groups(['boisson:read:simple', 'write:simple', 'write:all'])]
    // #[ORM\ManyToMany(targetEntity: Taille::class, mappedBy: 'boissons')]
    // private $tailles;

    #[Groups(['boisson:read:simple', 'write:simple', 'write:all'])]
    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    private $nom;

    #[Groups(['boisson:read:simple', 'write:simple', 'write:all'])]
    #[ORM\Column(type: 'float', nullable: true)]
    private $prix;

    #[Groups(['boisson:read:simple', 'write:simple', 'write:all'])]
    #[ORM\Column(type: 'boolean')]
    private $isEtat;

    #[Groups(['boisson:read:simple', 'write:simple', 'write:all'])]
    #[ORM\ManyToMany(targetEntity: Taille::class, inversedBy: 'boissons')]
    private $tailles;

    #[ORM\ManyToMany(targetEntity: Menu::class, mappedBy: 'boissons')]
    private $menus;

    public function __construct()
    {
        $this->tailles = new ArrayCollection();
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

    public function isIsEtat(): ?bool
    {
        return $this->isEtat;
    }

    public function setIsEtat(bool $isEtat): self
    {
        $this->isEtat = $isEtat;

        return $this;
    }

    public function getPrix(): ?float
    {
        return $this->prix;
    }

    public function setPrix(?float $prix): self
    {
        $this->prix = $prix;

        return $this;
    }

    /**
     * @return Collection<int, Taille>
     */
    public function getTailles(): Collection
    {
        return $this->tailles;
    }

    public function addTaille(Taille $taille): self
    {
        if (!$this->tailles->contains($taille)) {
            $this->tailles[] = $taille;
        }

        return $this;
    }

    public function removeTaille(Taille $taille): self
    {
        $this->tailles->removeElement($taille);

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
            $menu->addBoisson($this);
        }

        return $this;
    }

    public function removeMenu(Menu $menu): self
    {
        if ($this->menus->removeElement($menu)) {
            $menu->removeBoisson($this);
        }

        return $this;
    }
}
