<?php

namespace AppBundle\Repository;

use Symfony\Component\HttpFoundation\ParameterBag;

class FormularioAvaliacaoAtividadeRepository extends RepositoryAbstract
{
    /**
     * 
     * @param ParameterBag $pb
     * @return Doctrine\ORM\Query
     */
    public function search(ParameterBag $pb)
    {
        $qb = $this->createQueryBuilder('faa');
        
        $qb->select('faa, p, pfa, perf')
            ->innerJoin('faa.periodicidade', 'p')
            ->innerJoin('faa.perfilFormularioAvaliacaoAtividade', 'pfa')
            ->innerJoin('pfa.perfil', 'perf');
            
        if ($pb->get('titulo')) {
            $qb->andWhere('UPPER(faa.noFormulario) LIKE UPPER(:noFormulario)')
                ->setParameter('noFormulario', $pb->get('titulo') . '%');
        }
        if ($pb->get('stFormulario')) {
            $qb->andWhere('faa.stRegistroAtivo = :stAtivo')
                ->setParameter('stAtivo', $pb->get('stFormulario'));
        }
        if ($pb->get('periodicidade')) {
            $qb->andWhere('p.coSeqPeriodicidade = :periodicidade')
                ->setParameter('periodicidade', $pb->get('periodicidade'));
        }
        if ($pb->get('perfil')) {
            $qb->andWhere('perf.coSeqPerfil = :perfil')
                ->setParameter('perfil', $pb->get('perfil'));
        }
        
        $this->orderPagination($qb, $pb);
        
        return $qb->getQuery();
    }
}
