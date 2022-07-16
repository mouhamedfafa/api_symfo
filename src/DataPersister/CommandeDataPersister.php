<?php
// src/DataPersister/UserDataPersister.php

namespace App\DataPersister;

use App\Entity\Commande;
use App\Entity\User;


use App\Entity\Produit;
use App\Entity\MenuBurgers;

use App\Entity\PortionFrite;
use App\Services\EnvoieEmail;
use Doctrine\ORM\EntityManagerInterface;
use ApiPlatform\Core\DataPersister\DataPersisterInterface;
use ApiPlatform\Core\DataPersister\ContextAwareDataPersisterInterface;
use DateTime;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

/**
 *
 */
class CommandeDataPersister implements DataPersisterInterface
{
    private $_entityManager;
    private $prix = 0;
    private $i=0;





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
        return $data instanceof Commande;
    }

    /**
     * @param Commande $data
     */
    public function persist($data, array $context = [])
    {

        if ($data instanceof Commande) {
//  $ncom=$data;
            
            $pzone = $data->getZone()->getPrixzone();

            $this->pzone = $pzone;
            foreach ($data->getCommandeBurger() as $produit) {
                // dd($produit->getBurger()->getPrix());
                $this->prix += (($produit->getBurger()->getPrix())*($produit->getQuantite()));

            }
          

            foreach ($data->getCommandeMenu() as $produit) {
                $this->prix += ($produit->getMenu()->getPrix())*($produit->getQuantite());
            //     // dd($this->prix);
            }foreach ($data->getCommandeTaille() as $produit) {
                $this->prix += ($produit->getTaille()->getPrix())*($produit->getQuantite());
            }
                // dd($this->prix);
           
       

            $data->setMontant($this->prix + $this->pzone);
            $data->setEtat('en cours');
    

            // $data->getProduitCommandes()[0]->setQuantiteProduit(count($data->getProduitCommandes()[0]->getProduits()));

            // $data->setMontant($this->prix );
            $data->setDate(new DateTime());
        }
    /*     // if ($data->getEtat()=='termine'){
        //     $data->getTicket()[0]-> setDateTicket(new DateTime);
        //     // $data->getTicket()->getCommande()->;
        // }; */
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
