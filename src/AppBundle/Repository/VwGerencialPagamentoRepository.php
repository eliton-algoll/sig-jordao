<?php

namespace AppBundle\Repository;

use AppBundle\Report\GerencialPagamentoFilter;
use Doctrine\ORM\EntityRepository;

class VwGerencialPagamentoRepository extends EntityRepository
{   
    /**
     * 
     * @param GerencialPagamentoFilter $filter
     * @return array
     */
    public function searchRelatorio(GerencialPagamentoFilter $filter)
    {
        $qb = $this->createQueryBuilder('vw')
            ->select($filter->getSelectPart())
            ->groupBy($filter->getGroupByPart());
        
        if ($filter->getPublicacao()) {            
            $qb->andWhere('vw.coSeqPublicacao = :publicacao')
                ->setParameter('publicacao', $filter->getPublicacao()->getCoSeqPublicacao());
        }
        if ($filter->getNuSipar()) {
            $qb->andWhere('vw.nuSiparProjeto = :nuSipar')
                ->setParameter('nuSipar', $filter->getNuSipar());
        }
        if ($filter->getNuAno()) {
            $qb->andWhere('vw.nuAno = :nuAno')
                ->setParameter('nuAno', $filter->getNuAno());
        }
        if ($filter->getNuMes()) {
            $qb->andWhere('vw.nuMes = :nuMes')
                ->setParameter('nuMes', $filter->getNuMes());
        }
        
        $result = new \stdClass();
        $result->data = $qb->getQuery()->getResult();
        $result->titles = $filter->getTitles();
        
        return $result;
    }
}
