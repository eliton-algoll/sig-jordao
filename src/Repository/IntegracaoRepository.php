<?php

namespace App\Repository;

use App\Entity\AgenciaBancaria;
use App\Repository\RepositoryAbstract;
use Doctrine\Persistence\ManagerRegistry;

/**
 * IntegracaoRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class IntegracaoRepository extends RepositoryAbstract
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, AgenciaBancaria::class);
    }
}
