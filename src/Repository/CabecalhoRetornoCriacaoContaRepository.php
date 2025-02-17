<?php

namespace App\Repository;

use App\Entity\CabecalhoRetornoCriacaoConta;
use App\Repository\RepositoryAbstract;
use Doctrine\Persistence\ManagerRegistry;


class CabecalhoRetornoCriacaoContaRepository extends RepositoryAbstract
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, CabecalhoRetornoCriacaoConta::class);
    }
}
