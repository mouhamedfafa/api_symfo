<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use App\Repository\ProduitRepository;
use Doctrine\ORM\Mapping\InheritanceType;
use Doctrine\ORM\Mapping\DiscriminatorMap;
use ApiPlatform\Core\Annotation\ApiResource;
use Doctrine\ORM\Mapping\DiscriminatorColumn;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Serializer\Annotation\Groups;


#[ORM\Entity(repositoryClass: ProduitRepository::class)]
#[InheritanceType("JOINED")]
#[DiscriminatorColumn(name: "discr", type: "string")]
#[DiscriminatorMap(["produit" => "Produit", "boisson" => "Boisson","portionfrite"=>"PortionFrite"])]

#[ApiResource(collectionOperations : ["get"=>[
'method' => 'get',
'status' => Response::HTTP_OK,
'normalization_context' => ['groups' => ['produit:read:simple']],
],
"add" => [
    'method' => 'Post',
    "path"=>"/add",
    "controller"=>ProduitController::class,
    ]
,"post"=>[
  // "access_control" => "is_granted('ROLE_GESTIONNAIRE')",
//"security_message"=>"Vous n'avez pas access à cette Ressource",
 'status' => Response::HTTP_CREATED,
 'denormalization_context' => ['groups' => ['write:simple','write:all']],
 'normalization_context' => ['groups' => ['produit:read:all']],]], 
 itemOperations:["put"=>[
                                     'method' => 'put',
                                     // "security" => "is_granted('ROLE_GESTIONNAIRE')",
                                     // "security_message"=>"Vous n'avez pas access à cette Ressource",
                                     'status' => Response::HTTP_OK,
                                     ],"get"=>[
                                     'method' => 'get',
                                     "path"=>"/produits/{id}",
                                     'requirements' => ['id' => '\d+'],
                                     'normalization_context' => ['groups' => ['all']],
                                     ],])]
 class Produit
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    // #[Groups(['produit:read:simple'])]

    private $id;

   
    // #[Groups(['produit:read:simple','write:simple','write:all'])]
    // #[ORM\Column(type: 'string', length: 255)]
    // private $image;

    #[Groups(['produit:read:simple','write:simple','write:all'])]
    #[ORM\Column(type: 'string', length: 255,nullable:true)]
    private $nom;

  
    #[Groups(['produit:read:simple','write:simple','write:all'])]
    #[ORM\Column(type: 'float')]
    private $prix;

    #[Groups(['produit:read:simple','write:simple','write:all'])]
    #[ORM\Column(type: 'boolean',nullable:true)]
    private $isEtat;

    #[ORM\ManyToMany(targetEntity: Menu::class, mappedBy: 'produits')]
    private $menus;

    public function __construct()
    {
        $this->menus = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }



    public function getImage(): ?string
    {
        return $this->image;
    }

    public function setImage(string $image): self
    {
        $this->image = $image;

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

    public function getNom(): ?string
    {
        return $this->nom;
    }

    public function setNom(string $nom): self
    {
        $this->nom = $nom;

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
            $menu->addProduit($this);
        }

        return $this;
    }

    public function removeMenu(Menu $menu): self
    {
        if ($this->menus->removeElement($menu)) {
            $menu->removeProduit($this);
        }

        return $this;
    }
}
