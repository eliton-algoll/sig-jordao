<?php

namespace App\Repository;

use App\Entity\TipoAssunto;
use App\Repository\RepositoryAbstract;
use Doctrine\Persistence\ManagerRegistry;

class TipoAssuntoRepository extends RepositoryAbstract
{    
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, TipoAssunto::class);
    }
}
