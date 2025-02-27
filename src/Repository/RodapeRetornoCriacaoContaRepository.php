<?php

namespace App\Repository;

use App\Entity\AgenciaBancaria;
use App\Entity\RodapeRetornoCriacaoConta;
use App\Repository\RepositoryAbstract;
use Doctrine\Persistence\ManagerRegistry;

class RodapeRetornoCriacaoContaRepository extends RepositoryAbstract
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, RodapeRetornoCriacaoConta::class);
    }
}
