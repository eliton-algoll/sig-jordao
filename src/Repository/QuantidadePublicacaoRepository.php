<?php

namespace App\Repository;

use App\Entity\AgenciaBancaria;
use App\Entity\QuantidadePublicacao;
use App\Repository\RepositoryAbstract;
use Doctrine\Persistence\ManagerRegistry;

/**
 * QuantidadePublicacaoRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class QuantidadePublicacaoRepository extends RepositoryAbstract
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, QuantidadePublicacao::class);
    }
}
