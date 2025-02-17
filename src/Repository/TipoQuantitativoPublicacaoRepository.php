<?php

namespace App\Repository;

use App\Entity\AgenciaBancaria;
use App\Entity\TipoQuantitativoPublicacao;
use App\Repository\RepositoryAbstract;
use Doctrine\Persistence\ManagerRegistry;

/**
 * TipoQuantitativoPublicacaoRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class TipoQuantitativoPublicacaoRepository extends RepositoryAbstract
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, TipoQuantitativoPublicacao::class);
    }
}
