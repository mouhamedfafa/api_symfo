<?php
// src/DataPersister/UserDataPersister.php

namespace App\DataPersister;

use App\Entity\Menu;
use App\Entity\User;
use App\Entity\Taille;
use App\Entity\Boisson;


use App\Entity\Produit;
use App\Entity\MenuBurgers;

use App\Entity\PortionFrite;
use App\Services\EnvoieEmail;
use Doctrine\ORM\EntityManagerInterface;
use ApiPlatform\Core\DataPersister\DataPersisterInterface;
use ApiPlatform\Core\DataPersister\ContextAwareDataPersisterInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

/**
 *
 */
class ProduitDataPersister implements DataPersisterInterface
{
    private $_entityManager;
    private $prix = 0;
    


   

    public function __construct(
        EntityManagerInterface $entityManager,

    ) {
        $this->_entityManager = $entityManager;
    }

    /**
     * {@inheritdoc}
     */
    public function supports($data, array $context = []): bool
    {
        return $data instanceof Produit;
    }
   
    /**
     * @param Produit $data
     */
    public function persist($data, array $context = []){ 
    


    
        
    if ( $data instanceof Produit) {
       
        if($data->getImagefile()){
        
            $data->setImage(file_get_contents($data->getImagefile()));
            
        }
        // dd($data);
    }
        
        if ($data instanceof Menu) {    
            foreach ($data->getMenuBurgers() as $burgers) {
                $this->prix += $burgers->getBurgers()->getPrix();
                dd($this->prix);
            }
            foreach ($data->getMenuPortionFrites() as $portionfrite) {
                $this->prix += $portionfrite->getPortionFrite()->getPrix();
            }
            //  foreach($data->getBoisson() as $boisson){

            foreach ($data->getMenuTailles() as $taille) {
                    $this->prix += $taille->getTaille()->getPrix();
            }
            $data->setPrix($this->prix);
        }
        $this->_entityManager->persist($data);
        $this->_entityManager->flush();
    }

    /**
     * {@inheritdoc}
     */
    public function remove($data, array $context = [])
    {
        $this->_entityManager->remove($data);
        $this->_entityManager->flush();
    }
}