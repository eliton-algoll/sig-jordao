<?php

namespace App\Repository;

use App\Entity\FaleConosco;
use App\Repository\RepositoryAbstract;
use Doctrine\Persistence\ManagerRegistry;

class FaleConoscoRepository extends RepositoryAbstract
{    
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, FaleConosco::class);
    }
}
