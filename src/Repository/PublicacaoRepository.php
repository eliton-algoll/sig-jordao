<?php

namespace App\Repository;

use App\Entity\Publicacao;
use App\Repository\RepositoryAbstract;
use Doctrine\Persistence\ManagerRegistry;

class PublicacaoRepository extends RepositoryAbstract
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Publicacao::class);
    }
}
