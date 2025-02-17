<?php

namespace App\Repository;

use App\Entity\CabecalhoArquivoRetornoPagamento;
use App\Repository\RepositoryAbstract;
use Doctrine\Persistence\ManagerRegistry;

class CabecalhoArquivoRetornoPagamentoRepository extends RepositoryAbstract
{    
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, CabecalhoArquivoRetornoPagamento::class);
    }
}
