<?php
// api/src/DataProvider/BlogPostCollectionDataProvider.php

namespace App\DataProvider;
use App\Entity\Complement;
use App\Repository\TailleRepository;
use App\Repository\BoissonRepository;
use App\Repository\PortionFriteRepository;
use ApiPlatform\Core\DataProvider\RestrictedDataProviderInterface;
use ApiPlatform\Core\DataProvider\ContextAwareCollectionDataProviderInterface;


final class     ComplementProvider implements ContextAwareCollectionDataProviderInterface, RestrictedDataProviderInterface
{

    private $frite;
    private $tailles;

    public function __construct(PortionFriteRepository $frite,TailleRepository $tailles)
    {
        $this->frite=$frite;
        $this->tailles=$tailles;
        
    }
    public function supports(string $resourceClass, string $operationName = null, array $context = []): bool
    {
        return Complement::class === $resourceClass;
    }

    public function getCollection(string $resourceClass, string $operationName = null, array $context = []): iterable
    {


    $complement=[];
    $complement['boisson']=$this->tailles-> findAll();
    $complement['frite']=$this->frite-> findAll();

    return $complement;

    

        // Retrieve the blog post collection from somewhere
        // yield new BlogPost(1);
        // yield new BlogPost(2);
    }


}