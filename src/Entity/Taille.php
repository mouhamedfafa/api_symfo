<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Repository\TailleRepository;
use Doctrine\Common\Collections\Collection;
use ApiPlatform\Core\Annotation\ApiResource;
use Symfony\Component\HttpFoundation\Response;
use Doctrine\Common\Collections\ArrayCollection;
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
    #[Groups(['taille:read:simple'])]
    #[ORM\Column(type: 'integer')]
    private $id;

    #[Groups(['taille:read:simple','write:simpletaille','write:alltaille'])]
    #[ORM\Column(type: 'string', length: 255)]
    private $libelle;

    #[ORM\ManyToMany(targetEntity: Boisson::class, mappedBy: 'tailles')]
    private $boissons;
    
    // #[Groups(['taille:read:simple','write:simple','write:all'])]
    // #[ORM\Column(type: 'float')]
    // private $prix;

    public function __construct()
    {
        $this->boissons = new ArrayCollection();
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
            $boisson->addTaille($this);
        }

        return $this;
    }

    public function removeBoisson(Boisson $boisson): self
    {
        if ($this->boissons->removeElement($boisson)) {
            $boisson->removeTaille($this);
        }

        return $this;
    }

  
}
