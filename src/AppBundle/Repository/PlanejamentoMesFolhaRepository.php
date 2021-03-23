<?php

namespace AppBundle\Repository;

class PlanejamentoMesFolhaRepository extends RepositoryAbstract
{
    /**
     * 
     * @param \DateTime $date
     * @return array<\AppBundle\Entity\PlanejamentoMesFolha>
     */
    public function findNaoExecutadosByDate(\DateTime $date)
    {
        $qb = $this->createQueryBuilder('pmf');
        
        return $qb
            ->select('pmf')
            ->innerJoin('pmf.planejamentoAnoFolha', 'paf')
            ->leftJoin(
                'pmf.folhaPagamento',
                'fp',
                'WITH',
                'paf.nuAno = fp.nuAno AND pmf.nuMes = fp.nuMes AND fp.publicacao = paf.publicacao AND fp.stRegistroAtivo = :stAtivo'
            )->where('CONCAT(CONCAT(paf.nuAno, pmf.nuMes), pmf.nuDiaAbertura) <= :nuReferencia')
            ->andWhere('pmf.stRegistroAtivo = :stAtivo')
            ->andWhere('paf.stRegistroAtivo = :stAtivo')
            ->andWhere('fp.coSeqFolhaPagamento IS NULL')
            ->setParameters([
                'nuReferencia' => $date->format('Ymd'),
                'stAtivo' => 'S',
            ])->getQuery()->getResult();
    }
}
