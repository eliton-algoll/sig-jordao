<?php

namespace App\Repository;

use App\Entity\VwGrupoatuacaoQtprofissional;
use Doctrine\Persistence\ManagerRegistry;

class VwGrupoatuacaoQtprofissionalRepository extends RepositoryAbstract
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, VwGrupoatuacaoQtprofissional::class);
    }
    
    public function quantidadeDePerfisPorGrupoDeAtuacao($coProjeto)
    {
        $qb = $this->createQueryBuilder('vw');
        
        return $qb->select('vw')
                ->where('vw.coSeqProjeto = :coProjeto')
                ->setParameter('coProjeto', $coProjeto, \PDO::PARAM_INT)
                ->getQuery()
                ->getArrayResult();
    }
}
