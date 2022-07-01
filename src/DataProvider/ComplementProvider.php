<?php
// api/src/DataProvider/BlogPostCollectionDataProvider.php

namespace App\DataProvider;
use App\Entity\Complement;
use App\Repository\BoissonRepository;
use App\Repository\PortionFriteRepository;
use ApiPlatform\Core\DataProvider\RestrictedDataProviderInterface;
use ApiPlatform\Core\DataProvider\ContextAwareCollectionDataProviderInterface;


final class     ComplementProvider implements ContextAwareCollectionDataProviderInterface, RestrictedDataProviderInterface
{

    private $frite;
    private $boisson;

    public function __construct(PortionFriteRepository $frite,BoissonRepository $boisson)
    {
        $this->frite=$frite;
        $this->boisson=$boisson;
        
    }
    public function supports(string $resourceClass, string $operationName = null, array $context = []): bool
    {
        return Complement::class === $resourceClass;
    }

    public function getCollection(string $resourceClass, string $operationName = null, array $context = []): iterable
    {


    $complement=[];
    $complement['boisson']=$this->boisson-> findAll();
    $complement['frite']=$this->frite-> findAll();

    return $complement;

    

        // Retrieve the blog post collection from somewhere
        // yield new BlogPost(1);
        // yield new BlogPost(2);
    }


}