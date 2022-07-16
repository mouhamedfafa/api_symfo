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
        // "add" => [
        //     'method' => 'Post',
        //     "path" => "/add",
        //     "controller" => BoissonController::class,
        // ], 
        "post" => [
            'method' => 'Post',
            // "access_control" => "is_granted('ROLE_GESTIONNAIRE')",
            //"security_message"=>"Vous n'avez pas access à cette Ressource",
            'status' => Response::HTTP_CREATED,
            'denormalization_context' => ['groups' => ['write:simpleboi', 'write:allboi']],
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

    
   class Boisson extends Produit
{ 
   

    // #[Groups(['boisson:read:simple', 'write:simple', 'write:all'])]
    // #[ORM\ManyToMany(targetEntity: Taille::class, mappedBy: 'boissons')]
    // private $tailles;

  

    #[ORM\ManyToMany(targetEntity: Menu::class, mappedBy: 'boissons')]
    private $menus;

    #[ORM\ManyToMany(targetEntity: Taille::class, mappedBy: 'boissons')]
    private $tailles;

    public function __construct()
    {
        
        $this->tailles = new ArrayCollection();
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
            $taille->addBoisson($this);
        }

        return $this;
    }

    public function removeTaille(Taille $taille): self
    {
        if ($this->tailles->removeElement($taille)) {
            $taille->removeBoisson($this);
        }

        return $this;
    }
}
