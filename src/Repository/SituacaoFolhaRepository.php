<?php

namespace App\Repository;

use App\Entity\SituacaoFolha;
use App\Entity\AgenciaBancaria;
use App\Repository\RepositoryAbstract;
use Doctrine\Persistence\ManagerRegistry;

class SituacaoFolhaRepository extends RepositoryAbstract
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, AgenciaBancaria::class);
    }
    
    /**
     * 
     * @return SituacaoFolha
     */
    public function getFinalizada()
    {
        return $this->find(SituacaoFolha::FINALIZADA);
    }
}
