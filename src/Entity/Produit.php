<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

use App\Repository\ProduitRepository;
use Doctrine\ORM\Mapping\InheritanceType;
use Doctrine\ORM\Mapping\DiscriminatorMap;
use ApiPlatform\Core\Annotation\ApiResource;
use Doctrine\ORM\Mapping\DiscriminatorColumn;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Serializer\Annotation\SerializedName;

#[ORM\Entity(repositoryClass: ProduitRepository::class)]
#[InheritanceType("JOINED")]
#[DiscriminatorColumn(name: "discr", type: "string")]
#[DiscriminatorMap(["produit" => "Produit", "boisson" => "Boisson","portionfrite"=>"PortionFrite","menu"=>"Menu","burger"=>"Burger"])]

#[ApiResource(collectionOperations : ["get"=>[
'method' => 'get',
'status' => Response::HTTP_OK,
'normalization_context' => ['groups' => ['produit:read:simple']],
]
// "add" => [
//     'method' => 'Post',
//     "path"=>"/add",
//     "controller"=>ProduitController::class,
//     ]
,"post"=>[
  // "access_control" => "is_granted('ROLE_GESTIONNAIRE')",
//"security_message"=>"Vous n'avez pas access à cette Ressource",
 'status' => Response::HTTP_CREATED,
 'denormalization_context' => ['groups' => ['write:simplep','write:allp']],
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
    #[Groups(['produit:read:simple','write:simplecom','write:simpleb', 'write:allb','write:simplepf', 'write:allpf','write:simplem'])]
    private $id;

    // #[Groups(['produit:read:simple','write:simple','write:all'])]
    // #[ORM\Column(type: 'string', length: 255)]
    // private $image;

    #[Groups(['produit:read:simple','write:simplep','write:allp','write:simplepf', 'write:allpf','write:simpleb', 'write:allb', 'write:simplem','write:allm','write:simpleboi', 'write:allboi','boisson:read:simple','pc:read:simple', 'write:simplepc','write:allpc','com:read:simple'])]
    #[ORM\Column(type: 'string', length: 255,nullable:true)]
    #[Assert\NotBlank(message:'le nom est obligatoire')]
    // #[Assert\Type('string', message: 'The value {{ value }} is not a valid {{ type }}.',)]
    private $nom;  

  
    #[Groups(['produit:read:simple','write:simplep','write:allp','write:simplepf', 'write:allpf','write:simpleb', 'write:allb','write:allm','boisson:read:simple','pc:read:simple', 'write:simplepc','write:allpc','com:read:simple'])]
    #[ORM\Column(type: 'float',nullable:true)]
    #[Assert\Positive(message:'le prix est obligatoire')]
    private $prix;

    #[Groups(['produit:read:simple','write:simplep','write:allp','write:simplepf', 'write:allpf','write:simpleb', 'write:allb', 'write:simplem','write:allm','write:simpleboi', 'write:allboi','boisson:read:simple','pc:read:simple', 'write:simplepc','write:allpc'])]
    #[ORM\Column(type: 'boolean',nullable:true)]
    private $isEtat;


    
    #[Groups(['produit:read:simple','all'])]
    #[ORM\Column(type: 'blob')]
    private $image;


    // #[Groups(['produit:read:simple','write:simplep','write:allp','write:simplepf', 'write:allpf','write:simpleb', 'write:allb', 'write:simplem','write:allm','write:simpleboi', 'write:allboi','boisson:read:simple'])]
    #[SerializedName("images")]
    #[Assert\Image(
        minWidth: 200,
        maxWidth: 500,
        minHeight: 200,
        maxHeight: 400,
        minWidthMessage:'largeur minimale 200 pixels',
        minHeightMessage:'hauteur minimale 200 pixels',
        maxHeightMessage:'hauteur maximale 400 pixels',
        maxWidthMessage:'largeur maxmale 400 pixels',
        maxSize: '102k',
        maxSizeMessage:'la taille ne doit pas depasser 100ko'
        
    )]
    #[Groups(['produit:read:simple','write:simplep','write:allp','write:simplepf', 'write:allpf','write:simpleb', 'write:allb', 'write:simplem','write:allm','write:simpleboi', 'write:allboi','boisson:read:simple'])]
    private string $imagefile;

  

    public function getId(): ?int
    {
        return $this->id;
    }



    // public function getImage(): ?string
    // {
    //     return $this->image;
    // }

    // public function setImage(string $image): self
    // {
    //     $this->image = $image;

    //     return $this;
    // }

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

    public function getImage()
    {
        
        return ( is_resource( $this->image))? base64_encode(stream_get_contents($this->image)) :$this->image
        ;
    }

    public function setImage($image): self
    {
        $this->image = $image;

        return $this;
    }

   
    

    /**
     * Get the value of imagefile
     */ 
    public function getImagefile()
    {
        return $this->imagefile;
    }

    /**
     * Set the value of imagefile
     *
     * @return  self
     */ 
    public function setImagefile($imagefile)
    {
        $this->imagefile = $imagefile;

        return $this;
    }

  

   
}
