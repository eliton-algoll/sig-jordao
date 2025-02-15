<?php

namespace App\Repository;

use App\Entity\AgenciaBancaria;
use App\Repository\RepositoryAbstract;
use Doctrine\Persistence\ManagerRegistry;

class EmissaoCertificadoRepository extends RepositoryAbstract
{    
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, AgenciaBancaria::class);
    }
}
