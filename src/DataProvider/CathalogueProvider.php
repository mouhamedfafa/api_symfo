<?php
// api/src/DataProvider/BlogPostCollectionDataProvider.php

namespace App\DataProvider;
use App\Entity\Cathalogue;
use App\Repository\MenuRepository;
use App\Repository\BurgerRepository;
use ApiPlatform\Core\DataProvider\RestrictedDataProviderInterface;
use ApiPlatform\Core\DataProvider\ContextAwareCollectionDataProviderInterface;


final class  CathalogueProvider implements ContextAwareCollectionDataProviderInterface, RestrictedDataProviderInterface
{

    private $burger;
    private $menu;

    public function __construct(BurgerRepository $burger,MenuRepository $menu)
    {
        $this->burger=$burger;
        $this->menu=$menu;
        
    }
    public function supports(string $resourceClass, string $operationName = null, array $context = []): bool
    {
        return Cathalogue::class === $resourceClass;
    }

    public function getCollection(string $resourceClass, string $operationName = null, array $context = []): iterable
    {


    $complement=[];
    $complement['menu']=$this->menu-> findAll();
    $complement['burger']=$this->burger-> findAll();

    return $complement;

    

        // Retrieve the blog post collection from somewhere
        // yield new BlogPost(1);
        // yield new BlogPost(2);
    }


}