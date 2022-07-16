<?php

namespace App\Service;

use ApiPlatform\Core\Filter\Validator\Length;
use App\Entity\MenuPortionFrite;
use Twig\Environment;
use Symfony\Component\Mime\Email;
use App\Repository\MenuRepository;
use Symfony\Component\Mime\Address;
use App\Repository\MenuBurgerRepository;
use App\Repository\MenuTailleRepository;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Validator\Context\ExecutionContextInterface;


class VerifyCommande {


private $burger;
private $menu;
private $i;




    public static function valide($object, ExecutionContextInterface $context, $payload)
{
    // dd(($object->getCommandeMenu()[0]));

// dd($CommandeBurger= $object->getCommandeBurger());
  
$CommandeBurger= $object->getCommandeBurger();
$CommandeTaille= $object->getCommandeTaille();
$CommandeMenu= $object->getCommandeMenu();

// $MenuBurger= $object->getMenuBurgers();   
if(empty($CommandeBurger[0] ) && empty($CommandeTaille[0]) &&empty($CommandeMenu[0]) ){
    $context->buildViolation('Vous devez choisir un produit pour commander')
    ->atPath('firstName')
    ->addViolation();

  

}; 
if(empty($CommandeBurger[0] )  &&empty($CommandeMenu[0]) ){
    $context->buildViolation('Vous devez choisir un burger ou un menu')
    ->atPath('firstName')
    ->addViolation();

}


        for ($i=0; $i<count($CommandeMenu)-1; $i++)
       if ($CommandeMenu[$i]->getMenu()->getId() ==$CommandeMenu[$i+1]->getMenu()->getId()) {
            $context->buildViolation('Il y a un doublon Veuillez indiquez ce produit une seule fois et choisir sa quantité ')
            ->atPath('CommandeMenu')
            ->addViolation();
       }
       for ($i=0; $i<count($CommandeTaille)-1; $i++)

       if ($CommandeTaille[$i]->getTaille()->getId() ==$CommandeTaille[$i+1]->getTaille()->getId() ) {
            $context->buildViolation('Il y a un doublon Veuillez indiquez ce produit une seule fois et choisir sa quantité ')
            ->atPath('CommandeTaille')
            ->addViolation();
       }  
       for ($i=0; $i<count($CommandeBurger)-1; $i++)

       if ($CommandeBurger[$i]->getBurger()->getId() ==$CommandeBurger[$i+1]->getBurger()->getId() ) {
            $context->buildViolation('Il y a un doublon Veuillez indiquez ce produit une seule fois et choisir sa quantité ')
            ->atPath('CommandeBurger')
            ->addViolation();
       }   




    }
}