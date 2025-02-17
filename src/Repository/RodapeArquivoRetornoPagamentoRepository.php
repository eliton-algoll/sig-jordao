<?php

namespace App\Repository;

use App\Entity\RodapeArquivoRetornoPagamento;
use App\Repository\RepositoryAbstract;
use Doctrine\Persistence\ManagerRegistry;

class RodapeArquivoRetornoPagamentoRepository extends RepositoryAbstract
{    
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, RodapeArquivoRetornoPagamento::class);
    }
}
