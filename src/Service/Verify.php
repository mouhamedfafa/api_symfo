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

if(empty( $MenuPortionFrite[0] ) && empty($MenuTaille[0])){
    $context->buildViolation('Vous de devez au moins choisir une portion frite ou une taille de boisson')
    ->atPath('firstName')
    ->addViolation();

  



};

    


 $id=$MenuBurger[0]->getBurgers();

// for (i==1; i<= 100; i++){

    // ($MenuBurger[i] as $key => $value) {
    //  }
//  }

    }
}