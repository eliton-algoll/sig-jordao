<?php

namespace App\Repository;

use App\Entity\DetalheRetornoCriacaoConta;
use App\Repository\RepositoryAbstract;
use Doctrine\Persistence\ManagerRegistry;

class DetalheRetornoCriacaoContaRepository extends RepositoryAbstract
{    
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry,  DetalheRetornoCriacaoConta::class);
    }
}
