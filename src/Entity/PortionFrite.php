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
#[ApiResource(collectionOperations : ["get"=>['method' => 'get','status' => Response::HTTP_OK,'normalization_context' => ['groups' => ['pfrite:read:simple']]],"post"], itemOperations:["put","get"]  )]
class PortionFrite extends Produit
{
   
}
