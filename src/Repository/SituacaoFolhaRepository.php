<?php

namespace App\Repository;

use App\Entity\SituacaoFolha;
use App\Repository\RepositoryAbstract;
use Doctrine\Persistence\ManagerRegistry;

class SituacaoFolhaRepository extends RepositoryAbstract
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, SituacaoFolha::class);
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
