<?php

namespace App\Repository;

use App\Entity\SituacaoProjetoFolha;
use App\Repository\RepositoryAbstract;
use Doctrine\Persistence\ManagerRegistry;

/**
 * SituacaoProjetoFolhaRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class SituacaoProjetoFolhaRepository extends RepositoryAbstract
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, SituacaoProjetoFolha::class);
    }
    
    /**
     * @return \App\Entity\SituacaoProjetoFolha
     */
    public function getSituacaoAutorizada()
    {
        return $this->find(\App\Entity\SituacaoProjetoFolha::AUTORIZADA);
    }
}
