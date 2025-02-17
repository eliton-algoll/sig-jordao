<?php

namespace App\Repository;

use App\Entity\Pessoa;
use App\Repository\RepositoryAbstract;
use Doctrine\Persistence\ManagerRegistry;

class PessoaRepository extends RepositoryAbstract
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Pessoa::class);
    }
}
