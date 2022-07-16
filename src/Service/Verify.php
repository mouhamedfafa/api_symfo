<?php

namespace App\Service;

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


class Verify {


private $burger;
private $menu;




    public static function validate($object, ExecutionContextInterface $context, $payload)
{

        
        $MenuPortionFrite= $object->getMenuPortionFrites();
        $MenuTaille= $object->getMenuTailles();
        $MenuBurger= $object->getMenuBurgers();


        if( $MenuPortionFrite[0]-> getQuantite()<=0){
            $context->buildViolation('La quantite doit etre positive ')
            ->atPath('MenuPortionfire')
            ->addViolation();
        
          

        };

if(empty( $MenuPortionFrite[0] ) && empty($MenuTaille[0])){
    $context->buildViolation('Vous de devez au moins choisir une portion frite ou une taille de boisson')
    ->atPath('Portionfire/tailleboisson')
    ->addViolation();

  



};


    


if(empty( $MenuBurger[0])){

    $context->buildViolation('Vous de devez au moins choisir une burger')
    ->atPath('Portionfire/tailleboisson')
    ->addViolation();


}
// dd($object->getMenuBurgers()[1]->getBurgers()->getId());
for ($i=0; $i<count($MenuBurger)-1; $i++)

if ($MenuBurger[$i]->getBurgers()->getId() ==$MenuBurger[$i+1]->getBurgers()->getId() ) {
     $context->buildViolation('Il y a un doublon Veuillez indiquez ce produit une seule fois et choisir sa quantité ')
     ->atPath('CommandeBurger')
     ->addViolation();
}   
for ($i=0; $i<count($MenuPortionFrite)-1; $i++)

if ($MenuPortionFrite[$i]->getPortionFrite()->getId() ==$MenuPortionFrite[$i+1]->getPortionFrite()->getId() ) {
     $context->buildViolation('Il y a un doublon Veuillez indiquez ce produit une seule fois et choisir sa quantité ')
     ->atPath('CommandePfrite')
     ->addViolation();
}   
for ($i=0; $i<count($MenuTaille)-1; $i++)

if ($MenuTaille[$i]->getTaille()->getId() ==$MenuTaille[$i+1]->getTaille()->getId() ) {
     $context->buildViolation('Il y a un doublon Veuillez indiquez ce produit une seule fois et choisir sa quantité ')
     ->atPath('CommandeTailleboisson')
     ->addViolation();
}   

    }
}