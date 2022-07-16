<?php

namespace App\Entity;
use App\Entity\CommandeTaille;
use Doctrine\ORM\Mapping as ORM;
use App\Repository\CommandeRepository;
use Doctrine\Common\Collections\Collection;
use ApiPlatform\Core\Annotation\ApiResource;
use App\Service\VerifyCommande;
use Symfony\Component\HttpFoundation\Response;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Mime\Message;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: CommandeRepository::class)]
#[ApiResource(
    collectionOperations: [
    "get" => [
        'method' => 'get',
        'status' => Response::HTTP_OK,
        'normalization_context' => ['groups' => ['com:read:simple']],
    ],
    // "add" => [
    //     'method' => 'Post',
    //     "path" => "/add",
    //     "controller" => MenuController::class,
    // ], 
    "post" => [
        // "access_control" => "is_granted('ROLE_GESTIONNAIRE')",
        //"security_message"=>"Vous n'avez pas access à cette Ressource",
        'method'=>'post',
        'status' => Response::HTTP_CREATED,
        'denormalization_context' => ['groups' => ['write:simplecom',]],
        'normalization_context' => ['groups' => ['com:read:all']],
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
    "path" => "/commande/{id}",
    'requirements' => ['id' => '\d+'],
    'normalization_context' => ['groups' => ['allcom']],
],]
)]
#[Assert\Callback([VerifyCommande::class, 'valide'])]

class Commande
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[Groups(['com:read:simple','allcom'],)]
    #[ORM\Column(type: 'integer')]
    private $id;

    #[Groups(['com:read:simple','allcom','write:simplecom','write:allcom'])]
    #[ORM\Column(type: 'integer', length: 255)]
    private $nCommande;

    #[Groups(['com:read:simple','allcom',])]
    #[ORM\Column(type: 'datetime')]
    private $date;


    #[Groups(['com:read:simple','write:allcom'])]
    #[ORM\Column(type: 'string', length: 255)]
    private $etat;


    #[Groups(['com:read:simple','allcom',])]
    #[ORM\Column(type: 'float',nullable:true)]
    private $montant;

    // #[Groups(['com:read:simple', 'write:simplecom','write:allcom'])]
    #[ORM\ManyToOne(targetEntity: Livraison::class, inversedBy: 'commandes')]
    private $livraison;

    // #[Groups(['com:read:simple', 'write:simplecom','write:allcom'],)]
    #[ORM\ManyToOne(targetEntity: Gestionnaire::class, inversedBy: 'commandes')]
    private $gestionnaire;

    #[Groups(['com:read:simple','allcom'])]
    #[ORM\ManyToOne(targetEntity: Client::class, inversedBy: 'commandes')]
    private $client;

    #[Groups(['com:read:simple', 'write:simplecom','write:allcom','allcom'])]
    #[ORM\ManyToOne(targetEntity: Zone::class, inversedBy: 'commandes')]
    private $zone;


    // #[Groups(['com:read:simple', 'write:simplecom','write:allcom'])]
    
    #[ORM\OneToMany(mappedBy: 'commande', targetEntity: CommandeBoisson::class)]
    private $commandeBoisson;

    #[Groups(['com:read:simple', 'write:simplecom','write:allcom','allcom'])]
    #[Assert\Valid]
    #[ORM\OneToMany(mappedBy: 'commande', targetEntity: CommandeBurger::class,cascade:["persist"])]
    private $CommandeBurger;

    #[Groups(['com:read:simple', 'write:simplecom','write:allcom','allcom'])]
    #[Assert\Valid]
    #[ORM\OneToMany(mappedBy: 'commande', targetEntity: CommandeTaille::class,cascade:["persist"])]
    private $CommandeTaille;

    #[Groups(['com:read:simple', 'write:simplecom','write:allcom','allcom'])]
    #[Assert\Valid]
    #[ORM\OneToMany(mappedBy: 'commande', targetEntity: CommandeMenu::class,cascade:["persist"])]
    private $CommandeMenu;

    public function __construct()
    {
        $this->commandeBoisson = new ArrayCollection();
        $this->CommandeBurger = new ArrayCollection();
        $this->CommandeTaille = new ArrayCollection();
        $this->CommandeMenu = new ArrayCollection();
    }

   

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNCommande(): ?string
    {
        return $this->nCommande;
    }

    public function setNCommande(string $nCommande): self
    {
        $this->nCommande = $nCommande;

        return $this;
    }

    public function getDate(): ?\DateTimeInterface
    {
        return $this->date;
    }

    public function setDate(\DateTimeInterface $date): self
    {
        $this->date = $date;

        return $this;
    }

    public function getEtat(): ?string
    {
        return $this->etat;
    }

    public function setEtat(string $etat): self
    {
        $this->etat = $etat;

        return $this;
    }

    public function getMontant(): ?float
    {
        return $this->montant;
    }

    public function setMontant(float $montant): self
    {
        $this->montant = $montant;

        return $this;
    }

   

    public function getLivraison(): ?Livraison
    {
        return $this->livraison;
    }

    public function setLivraison(?Livraison $livraison): self
    {
        $this->livraison = $livraison;

        return $this;
    }

    public function getGestionnaire(): ?Gestionnaire
    {
        return $this->gestionnaire;
    }

    public function setGestionnaire(?Gestionnaire $gestionnaire): self
    {
        $this->gestionnaire = $gestionnaire;

        return $this;
    }

    public function getClient(): ?Client
    {
        return $this->client;
    }

    public function setClient(?Client $client): self
    {
        $this->client = $client;

        return $this;
    }

    public function getZone(): ?Zone
    {
        return $this->zone;
    }

    public function setZone(?Zone $zone): self
    {
        $this->zone = $zone;

        return $this;
    }

   
    /**
     * @return Collection<int, CommandeBoisson>
     */
    public function getCommandeBoisson(): Collection
    {
        return $this->commandeBoisson;
    }

    public function addCommandeBoisson(CommandeBoisson $commandeBoisson): self
    {

        if (!$this->commandeBoisson->contains($commandeBoisson)) {
            $this->commandeBoisson[] = $commandeBoisson;
            $commandeBoisson->setCommande($this);
        }

        return $this;
    }

    public function removeCommandeBoisson(CommandeBoisson $commandeBoisson): self
    {
        if ($this->commandeBoisson->removeElement($commandeBoisson)) {
            // set the owning side to null (unless already changed)
            if ($commandeBoisson->getCommande() === $this) {
                $commandeBoisson->setCommande(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, CommandeBurger>
     */
    public function getCommandeBurger(): Collection
    {
        return $this->CommandeBurger;
    }

    public function addCommandeBurger(CommandeBurger $commandeBurger): self
    {
        if (!$this->CommandeBurger->contains($commandeBurger)) {
            $this->CommandeBurger[] = $commandeBurger;
            $commandeBurger->setCommande($this);
        }

        return $this;
    }

    public function removeCommandeBurger(CommandeBurger $commandeBurger): self
    {
        if ($this->CommandeBurger->removeElement($commandeBurger)) {
            // set the owning side to null (unless already changed)
            if ($commandeBurger->getCommande() === $this) {
                $commandeBurger->setCommande(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, CommandeTaille>
     */
    public function getCommandeTaille(): Collection
    {
        return $this->CommandeTaille;
    }

    public function addCommandeTaille(CommandeTaille $commandeTaille): self
    {
        if (!$this->CommandeTaille->contains($commandeTaille)) {
            $this->CommandeTaille[] = $commandeTaille;
            $commandeTaille->setCommande($this);
        }

        return $this;
    }

    public function removeCommandeTaille(CommandeTaille $commandeTaille): self
    {
        if ($this->CommandeTaille->removeElement($commandeTaille)) {
            // set the owning side to null (unless already changed)
            if ($commandeTaille->getCommande() === $this) {
                $commandeTaille->setCommande(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, CommandeMenu>
     */
    public function getCommandeMenu(): Collection
    {
        return $this->CommandeMenu;
    }

    public function addCommandeMenu(CommandeMenu $commandeMenu): self
    {
        if (!$this->CommandeMenu->contains($commandeMenu)) {
            $this->CommandeMenu[] = $commandeMenu;
            $commandeMenu->setCommande($this);
        }

        return $this;
    }

    public function removeCommandeMenu(CommandeMenu $commandeMenu): self
    {
        if ($this->CommandeMenu->removeElement($commandeMenu)) {
            // set the owning side to null (unless already changed)
            if ($commandeMenu->getCommande() === $this) {
                $commandeMenu->setCommande(null);
            }
        }

        return $this;
    }
}
