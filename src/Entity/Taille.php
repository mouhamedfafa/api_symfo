<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Repository\TailleRepository;
use Doctrine\Common\Collections\Collection;
use ApiPlatform\Core\Annotation\ApiResource;
use Symfony\Component\HttpFoundation\Response;
use Doctrine\Common\Collections\ArrayCollection;
use phpDocumentor\Reflection\Types\Nullable;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: TailleRepository::class)]
#[ApiResource(collectionOperations : ["get"=>[
    'method' => 'get',
'status' => Response::HTTP_OK,
'normalization_context' => ['groups' => ['taille:read:simple']],
],
"add" => [
    'method' => 'Post',
    "path"=>"/add",
    "controller"=>TailleController::class,
    ]
,"post"=>[
  // "access_control" => "is_granted('ROLE_GESTIONNAIRE')",
//"security_message"=>"Vous n'avez pas access à cette Ressource",
 'status' => Response::HTTP_CREATED,
 'denormalization_context' => ['groups' => ['write:simpletaille','write:alltaille']],
 'normalization_context' => ['groups' => ['taille:read:all']],]], 
 itemOperations:["put"=>[
                                                                         'method' => 'put',
                                                                         // "security" => "is_granted('ROLE_GESTIONNAIRE')",
                                                                         // "security_message"=>"Vous n'avez pas access à cette Ressource",
                                                                         'status' => Response::HTTP_OK,
                                                                         ],"get"=>[
                                                                         'method' => 'get',
                                                                         "path"=>"/tailles/{id}",
                                                                         'requirements' => ['id' => '\d+'],
                                                                         'normalization_context' => ['groups' => ['all']],
                                                                         ],])]



class Taille
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[Groups(['write:simplem','com:read:simple','write:allcom','write:simplecom'])]
    #[ORM\Column(type: 'integer')]
    private $id;

    #[Groups(['taille:read:simple','write:simpletaille','write:alltaille'])]
    #[ORM\Column(type: 'string', length: 255,nullable:true)]
    private $libelle;

  
    #[Groups(['taille:read:simple','write:simpletaille','write:alltaille'])]
    #[ORM\Column(type: 'float',nullable:true)]
    private $prix;

    #[Groups(['com:read:simple', 'write:simplecom','write:allcom','write:simplem'])]
    #[ORM\ManyToMany(targetEntity: Boisson::class, inversedBy: 'tailles')]
    private $boissons;

    
    #[ORM\OneToMany(mappedBy: 'taille', targetEntity: MenuTaille::class)]
    private $menuTailles;

    #[ORM\OneToMany(mappedBy: 'taille', targetEntity: CommandeTaille::class)]
    private $CommandeTailles;


    public function __construct()
    {
        $this->boissons = new ArrayCollection();
        $this->menuTailles = new ArrayCollection();
        $this->CommandeTailles = new ArrayCollection();
       
    }

    public function getId(): ?int
    {
        return $this->id;
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

    public function getLibelle(): ?string
    {
        return $this->libelle;
    }

    public function setLibelle(string $libelle): self
    {
        $this->libelle = $libelle;

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
            $menuTaille->setTaille($this);
        }

        return $this;
    }

    public function removeMenuTaille(MenuTaille $menuTaille): self
    {
        if ($this->menuTailles->removeElement($menuTaille)) {
            // set the owning side to null (unless already changed)
            if ($menuTaille->getTaille() === $this) {
                $menuTaille->setTaille(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, CommandeTaille>
     */
    public function getCommandeTailles(): Collection
    {
        return $this->CommandeTailles;
    }

    public function addCommandeTaille(CommandeTaille $commandeTaille): self
    {
        if (!$this->CommandeTailles->contains($commandeTaille)) {
            $this->CommandeTailles[] = $commandeTaille;
            $commandeTaille->setTaille($this);
        }

        return $this;
    }

    public function removeCommandeTaille(CommandeTaille $commandeTaille): self
    {
        if ($this->CommandeTailles->removeElement($commandeTaille)) {
            // set the owning side to null (unless already changed)
            if ($commandeTaille->getTaille() === $this) {
                $commandeTaille->setTaille(null);
            }
        }

        return $this;
    }

   
  

  
}
