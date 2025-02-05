<?php

namespace AppBundle\Repository;

class VwGrupoatuacaoQtprofissionalRepository extends RepositoryAbstract
{
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
