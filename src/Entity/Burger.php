<?php

namespace App\Entity;

use App\Entity\User;
use Doctrine\ORM\Mapping as ORM;
use App\Repository\BurgerRepository;
use Doctrine\Common\Collections\Collection;
use ApiPlatform\Core\Annotation\ApiProperty;
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
        
         "post" => [
            // "access_control" => "is_granted('ROLE_GESTIONNAIRE')",
            //"security_message"=>"Vous n'avez pas access à cette Ressource",
            'method' => 'Post',
            'status' => Response::HTTP_CREATED,
            'denormalization_context' => ['groups' => ['write:simpleb', 'write:allb']],
            'normalization_context' => ['groups' => ['burger:read:all']],
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


class Burger extends Produit
{
    // #[Groups(['burger:read:simple','write:simpleb', 'write:allb'])]
    #[ORM\ManyToOne(targetEntity: User::class, inversedBy: 'burgers')]
    private $user;

    


    // #[Groups(['burger:read:simpleb', 'write:simpleb', 'write:allb','write:allb','write:simb', 'write:alb'])]

    // #[ORM\Column(type: 'blob')]
    // private $image;


    #[ApiProperty(iri: '/api/buregrs')]
    #[Groups(['burger:read:simple'])]
    public ?string $contentUrl = null;

    #[ORM\OneToMany(mappedBy: 'burgers', targetEntity: MenuBurger::class)]
    private $menuBurgers;

    
    //  #[Vich\UploadableField(mapping:"media_object", fileNameProperty:"filePath")]
    // #[Groups(['write:all'])]
    // public ?File $file = null;

    // #[ORM\Column(nullable: true)] 
    // #[Groups(['write:simple','write:all'])]
    // public ?string $filePath = null;

    public function __construct()
    {
        $this->menus = new ArrayCollection();
        $this->menuBurgers = new ArrayCollection();
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

   

    // public function getImage()
    // {
    //     return $this->image;
    // }

    // public function setImage($image): self
    // {
    //     $this->image = $image;

    //     return $this;
    // }

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
            $menuBurger->setBurgers($this);
        }

        return $this;
    }

    public function removeMenuBurger(MenuBurger $menuBurger): self
    {
        if ($this->menuBurgers->removeElement($menuBurger)) {
            // set the owning side to null (unless already changed)
            if ($menuBurger->getBurgers() === $this) {
                $menuBurger->setBurgers(null);
            }
        }

        return $this;
    }
}
