<?php

namespace App\Repository;

use App\Entity\DetalheArquivoRetornoPagamento;
use App\Repository\RepositoryAbstract;
use Doctrine\Persistence\ManagerRegistry;

class DetalheArquivoRetornoPagamentoRepository extends RepositoryAbstract
{    
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, DetalheArquivoRetornoPagamento::class);
    }
}
