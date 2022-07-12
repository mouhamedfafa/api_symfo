<?php

namespace App\Entity;

use App\Entity\Boisson;
use App\Entity\Produit;
use App\Service\Verify;
use Doctrine\ORM\Mapping as ORM;
use App\Repository\MenuRepository;
use Doctrine\Common\Collections\Collection;
use ApiPlatform\Core\Annotation\ApiResource;
use Symfony\Component\HttpFoundation\Response;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: MenuRepository::class)]

#[ApiResource(
    collectionOperations: [
    "get" => [
        'method' => 'get',
        'status' => Response::HTTP_OK,
        'normalization_context' => ['groups' => ['menu:read:simple']],
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
        'denormalization_context' => ['groups' => ['write:simplem',]],
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
#[Assert\Callback([Verify::class, 'validate'])]

class Menu extends Produit
{
   
    #[ORM\ManyToOne(targetEntity: User::class, inversedBy: 'menu')]
    private $user;
    #[Groups(['menu:read:simple', 'write:simplem','write:allm'],)]
    #[ORM\ManyToMany(targetEntity: Boisson::class, inversedBy: 'menus',cascade:["persist"])]
    private $boissons;

    #[Groups(['menu:read:simple', 'write:simplem'])]
    #[Assert\Valid]
    #[Assert\NotBlank]
    #[Assert\Count(
        min: 1,
        minMessage: 'You must specify at least one email',
       
    )]
    #[ORM\OneToMany(mappedBy: 'menu', targetEntity: MenuBurger::class,cascade:["persist"])]
    private $menuBurgers;

    #[Groups(['menu:read:simple', 'write:simplem'])]
    #[ORM\OneToMany(mappedBy: 'menu', targetEntity: MenuPortionFrite::class,cascade:["persist"])]
    #[Assert\Valid]
    private $menuPortionFrites;

    #[Groups(['menu:read:simple', 'write:simplem'])]
    // #[Assert\Valid]
    #[ORM\OneToMany(mappedBy: 'menu', targetEntity: MenuTaille::class,cascade:["persist"])]
    private $menuTailles;



 


    public function __construct()
    {
        
        $this->boissons = new ArrayCollection();
        $this->menuBurgers = new ArrayCollection();
        $this->menuPortionFrites = new ArrayCollection();
        $this->menuTailles = new ArrayCollection();
       
        

        
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
     * @return Collection<int, Boisson>
     */
    public function getBoissons(): Collection
    {
        return $this->boissons;
    }

    public function addBoisson(Boisson $boisson): self
    {
        if (!$this->boissons->contains($boisson)) {
            $this->boissons[] = $boisson;
        }

        return $this;
    }

    public function removeBoisson(Boisson $boisson): self
    {
        $this->boissons->removeElement($boisson);

        return $this;
    }

    /**
     * @return Collection<int, MenuBurger>
     */
    public function getMenuBurgers(): Collection
    {
        return $this->menuBurgers;
    }

    public function addMenuBurger(MenuBurger $menuBurger): self
    {
        if (!$this->menuBurgers->contains($menuBurger)) {
            $this->menuBurgers[] = $menuBurger;
            $menuBurger->setMenu($this);
        }

        return $this;
    }

    public function removeMenuBurger(MenuBurger $menuBurger): self
    {
        if ($this->menuBurgers->removeElement($menuBurger)) {
            // set the owning side to null (unless already changed)
            if ($menuBurger->getMenu() === $this) {
                $menuBurger->setMenu(null);
            }
        }

        return $this;
    }

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
            $menuPortionFrite->setMenu($this);
        }

        return $this;
    }

    public function removeMenuPortionFrite(MenuPortionFrite $menuPortionFrite): self
    {
        if ($this->menuPortionFrites->removeElement($menuPortionFrite)) {
            // set the owning side to null (unless already changed)
            if ($menuPortionFrite->getMenu() === $this) {
                $menuPortionFrite->setMenu(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, MenuTaille>
     */
    public function getMenuTailles(): Collection
    {
        return $this->menuTailles;
    }

    public function addMenuTaille(MenuTaille $menuTaille): self
    {
        if (!$this->menuTailles->contains($menuTaille)) {
            $this->menuTailles[] = $menuTaille;
            $menuTaille->setMenu($this);
        }

        return $this;
    }

    public function removeMenuTaille(MenuTaille $menuTaille): self
    {
        if ($this->menuTailles->removeElement($menuTaille)) {
            // set the owning side to null (unless already changed)
            if ($menuTaille->getMenu() === $this) {
                $menuTaille->setMenu(null);
            }
        }

        return $this;
    }


    

   
   

   

    
}
