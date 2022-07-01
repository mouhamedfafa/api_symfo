<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use App\Repository\CathalogueRepository;
use Doctrine\ORM\Mapping as ORM;

// #[ORM\Entity(repositoryClass: CathalogueRepository::class)]
#[ApiResource]
class Cathalogue
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;

    public function getId(): ?int
    {
        return $this->id;
    }
}
