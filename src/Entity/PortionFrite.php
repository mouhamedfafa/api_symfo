<?php

namespace App\Entity;

use App\Entity\Produit;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use App\Repository\PortionFriteRepository;
use ApiPlatform\Core\Annotation\ApiResource;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: PortionFriteRepository::class)]
#[ApiResource(collectionOperations : ["get"=>
['method' => 'get','status' => Response::HTTP_OK,
'normalization_context' => ['groups' => ['pfrite:read:simple']]],

 "post" => [
        // "access_control" => "is_granted('ROLE_GESTIONNAIRE')",
        //"security_message"=>"Vous n'avez pas access à cette Ressource",
        'method'=>'post',
        'status' => Response::HTTP_CREATED,
        'denormalization_context' => ['groups' => ['write:simplepf', 'write:allpf']],
        'normalization_context' => ['groups' => ['menu:read:all']],
        // 'input_formats' => [
        //     'multipart' => ['multipart/form-data'],]
    ]],
 itemOperations:["put","get"]  )]
class PortionFrite extends Produit
{



    
#[ORM\OneToMany(mappedBy: 'portionFrite', targetEntity: MenuPortionFrite::class)]
private $menuPortionFrites;

public function __construct()
{
    $this->menuPortionFrites = new ArrayCollection();
}

//     #[ORM\ManyToMany(targetEntity: Menu::class, mappedBy: 'portionfrite')]
//     private $menus;

//     public function __construct()
//     {
//         parent::__construct();
//         $this->menus = new ArrayCollection();
//     }

//     /**
//      * @return Collection<int, Menu>
//      */
//     public function getMenus(): Collection
//     {
//         return $this->menus;
//     }

//     public function addMenu(Menu $menu): self
//     {
//         if (!$this->menus->contains($menu)) {
//             $this->menus[] = $menu;
//             $menu->addPortionfrite($this);
//         }

//         return $this;
//     }

//     public function removeMenu(Menu $menu): self
//     {
//         if ($this->menus->removeElement($menu)) {
//             $menu->removePortionfrite($this);
//         }

//         return $this;
//     }

/**
 * @return Collection<int, MenuPortionFrite>
 */
public function getMenuPortionFrites(): Collection
{
    return $this->menuPortionFrites;
}

public function addMenuPortionFrite(MenuPortionFrite $menuPortionFrite): self
{
    if (!$this->menuPortionFrites->contains($menuPortionFrite)) {
        $this->menuPortionFrites[] = $menuPortionFrite;
        $menuPortionFrite->setPortionFrite($this);
    }

    return $this;
}

public function removeMenuPortionFrite(MenuPortionFrite $menuPortionFrite): self
{
    if ($this->menuPortionFrites->removeElement($menuPortionFrite)) {
        // set the owning side to null (unless already changed)
        if ($menuPortionFrite->getPortionFrite() === $this) {
            $menuPortionFrite->setPortionFrite(null);
        }
    }

    return $this;
}
}
