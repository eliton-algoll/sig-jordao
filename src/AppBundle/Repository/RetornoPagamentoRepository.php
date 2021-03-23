<?php

namespace AppBundle\Repository;

use Symfony\Component\HttpFoundation\ParameterBag;
use Doctrine\ORM\Query;
use AppBundle\Entity\RetornoPagamento;

class RetornoPagamentoRepository extends RepositoryAbstract
{    
    /**
     * 
     * @param string $noArquivoOriginal
     * @return RetornoPagamento     
     */
    public function getByArquivoOriginal($noArquivoOriginal)
    {
        $qb = $this->createQueryBuilder('rp');
        
        return $qb
            ->select('rp')
            ->where('rp.stRegistroAtivo = :stAtivo')
            ->andWhere('LOWER(rp.noArquivoOriginal) = LOWER(:noArquivoOriginal)')
            ->setMaxResults(1)
            ->setParameters([
                'stAtivo' => 'S',
                'noArquivoOriginal' => $noArquivoOriginal,
            ])->getQuery()->getSingleResult();
    }
    
    /**
     * 
     * @param ParameterBag $pb
     * @return Query
     */
    public function search(ParameterBag $pb)
    {
        $qb = $this->createQueryBuilder('rp');
        
        $qb->select('rp, fp')
            ->innerJoin('rp.folhaPagamento', 'fp')
            ->where('rp.stRegistroAtivo = :stAtivo')
            ->andWhere('fp.stRegistroAtivo = :stAtivo')
            ->setParameter('stAtivo', 'S')
        ;
        
        if ($pb->get('publicacao')) {
            $qb->andWhere('fp.publicacao = :publicacao')
                ->setParameter('publicacao', $pb->get('publicacao'));
        }
        if ($pb->get('referencia')) {
            
            $referencia = explode('/', $pb->get('referencia'));
            
            $qb->andWhere('fp.nuAno = :nuAno')
                ->andWhere('fp.nuMes = :nuMes')
                ->setParameter('nuAno', $referencia[1])
                ->setParameter('nuMes', $referencia[0]);
        }
        
        $this->orderPagination($qb, $pb);
        $query = $qb->getQuery();
        $query->setHydrationMode(Query::HYDRATE_ARRAY);
        
        return $query;
    }
    
    /**
     * 
     * @param RetornoPagamento $retornoPagamento
     * @param ParameterBag $pb
     * @return array
     */
    public function report(RetornoPagamento $retornoPagamento, ParameterBag $pb)
    {
        $qb = $this->createQueryBuilder('rp');
        
        $qb->select('rp, fp, pub, prog, carp, rarp, darp, af, pp, pperf, pf', 'pes')
            ->innerJoin('rp.folhaPagamento', 'fp')
            ->innerJoin('fp.publicacao', 'pub')
            ->innerJoin('pub.programa', 'prog')
            ->innerJoin('rp.cabecalhoArquivoRetornoPagamento', 'carp')
            ->innerJoin('rp.rodapeArquivoRetornoPagamento', 'rarp');
        
        if ($pb->get('stCredito')) {
            $qb->leftJoin('rp.detalheArquivoRetornoPagamento', 'darp', 'WITH', 'darp.nuSituacaoCredito IN (:stCredito)')
                ->setParameter('stCredito', (array) $pb->get('stCredito'));
        } else {
            $qb->innerJoin('rp.detalheArquivoRetornoPagamento', 'darp');
        }
        
        $qb->leftJoin('darp.autorizacaoFolha', 'af')
            ->leftJoin('af.projetoPessoa', 'pp')
            ->leftJoin('pp.pessoaPerfil', 'pperf')
            ->leftJoin('pperf.pessoaFisica', 'pf')
            ->leftJoin('pf.pessoa', 'pes')
            ->where('rp.coSeqRetornoPagamento = :retornoPagamento')
            ->setParameter('retornoPagamento', $retornoPagamento)
            ->orderBy('darp.nuSituacaoCredito')
            ->addOrderBy('pes.noPessoa')
        ;
        
        return $qb->getQuery()->getOneOrNullResult(Query::HYDRATE_ARRAY);
    }
}
