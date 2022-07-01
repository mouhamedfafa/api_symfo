<?php

namespace App\Entity;

use App\Entity\Menu;
use App\Entity\Burger;
use Doctrine\ORM\Mapping as ORM;
use App\Repository\UserRepository;
use Doctrine\ORM\Mapping\InheritanceType;
use Doctrine\ORM\Mapping\DiscriminatorMap;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping\DiscriminatorColumn;
use Symfony\Component\HttpFoundation\Response;
use ApiPlatform\Core\Annotation\ApiSubresource;
use ApiPlatform\Core\Annotation\ApiResource;
use DateTime;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;


#[ORM\Entity(repositoryClass: UserRepository::class)]
#[InheritanceType("JOINED")]
#[DiscriminatorColumn(name: "discr", type: "string")]
#[DiscriminatorMap(["user" => "User", "client" => "Client","gestionnaire"=>"Gestionnaire"])]

#[ApiResource(collectionOperations:["get"=>["method" => "get",
'status' => Response::HTTP_OK,
'normalization_context' => ['groups' => ['user:read:simple']],
],
    
    "post_register" => [
    "method"=>"post",
    'status' => Response::HTTP_CREATED,
    
    'denormalization_context' => ['groups' => ['user:write']],
    'normalization_context' => ['groups' => ['user:read:simple']]
    ],
    ],itemOperations:["put","get"])]


class User implements UserInterface, PasswordAuthenticatedUserInterface
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;

    
    #[Groups(['user:write','user:read:simple'])]
    #[ORM\Column(type: 'string', length: 180, unique: true)]
    private $login;

    #[Groups(['user:write','user:read:simple'])]
    #[ORM\Column(type: 'json')]
    private $roles = [];

    #[Groups(['user:write','user:read:simple'])]
    #[ORM\Column(type: 'string')]
    private $password;

    #[ORM\OneToMany(mappedBy: 'user', targetEntity: Burger::class)]
    #[ApiSubresource]
    private $burgers;

    #[ORM\OneToMany(mappedBy: 'user', targetEntity: Menu::class)]
    private $menu;

    #[Groups(['user:write','user:read:simple'])]
    #[ORM\Column(type: 'string', length: 255)]
    private $nom;

    #[Groups(['user:write','user:read:simple'])]
    #[ORM\Column(type: 'string', length: 255)]
    private $prenom;

    #[Groups(['user:write','user:read:simple'])]
    #[ORM\Column(type: 'string', length: 255)]
    private $adresse;

    #[ORM\Column(type: 'string', length: 255)]
    private $token;

    #[ORM\Column(type: 'boolean')]
    private $isActivate=false;

    #[ORM\Column(type: 'datetime')]
    private $ExpireAt;

    public function __construct()
    {
        $this->burgers = new ArrayCollection();
        $this->menu = new ArrayCollection();
        $this -> generateToken();

    }
    public function generateToken(){
      $this->token = str_replace(['+', '/', '='], ['-', '_', ''], base64_encode(random_bytes(128)));
        $this->ExpireAt =new DateTime('+ 1 day');
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getLogin(): ?string
    {
        return $this->login;
    }

    public function setLogin(string $login): self
    {
        $this->login = $login;

        return $this;
    }

    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUserIdentifier(): string
    {
        return (string) $this->login;
    }

    /**
     * @see UserInterface
     */
    public function getRoles(): array
    {
        $roles = $this->roles;
        // guarantee every user at least has ROLE_USER
        $roles[] = 'ROLE_VISITEUR';

        return array_unique($roles);
    }

    public function setRoles(array $roles): self
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * @see PasswordAuthenticatedUserInterface
     */
    public function getPassword(): string
    {
        return $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials()
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
    }

    /**
     * @return Collection<int, Burger>
     */
    public function getBurgers(): Collection
    {
        return $this->burgers;
    }

    public function addBurger(Burger $burger): self
    {
        if (!$this->burgers->contains($burger)) {
            $this->burgers[] = $burger;
            $burger->setUser($this);
        }

        return $this;
    }

    public function removeBurger(Burger $burger): self
    {
        if ($this->burgers->removeElement($burger)) {
            // set the owning side to null (unless already changed)
            if ($burger->getUser() === $this) {
                $burger->setUser(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Menu>
     */
    public function getMenu(): Collection
    {
        return $this->menu;
    }

    public function addMenu(Menu $menu): self
    {
        if (!$this->menu->contains($menu)) {
            $this->menu[] = $menu;
            $menu->setUser($this);
        }

        return $this;
    }

    public function removeMenu(Menu $menu): self
    {
        if ($this->menu->removeElement($menu)) {
            // set the owning side to null (unless already changed)
            if ($menu->getUser() === $this) {
                $menu->setUser(null);
            }
        }

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

    public function getPrenom(): ?string
    {
        return $this->prenom;
    }

    public function setPrenom(string $prenom): self
    {
        $this->prenom = $prenom;

        return $this;
    }

    public function getAdresse(): ?string
    {
        return $this->adresse;
    }

    public function setAdresse(string $adresse): self
    {
        $this->adresse = $adresse;

        return $this;
    }

    public function getToken(): ?string
    {
        return $this->token;
    }

    public function setToken(string $token): self
    {
        $this->token = $token;

        return $this;
    }

    public function isIsActivate(): ?bool
    {
        return $this->isActivate;
    }

    public function setIsActivate(bool $isActivate): self
    {
        $this->isActivate = $isActivate;

        return $this;
    }

    public function getExpireAt(): ?\DateTimeInterface
    {
        return $this->ExpireAt;
    }

    public function setExpireAt(\DateTimeInterface $ExpireAt): self
    {
        $this->ExpireAt = $ExpireAt;

        return $this;
    }
}
