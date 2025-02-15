<?php

namespace App\Repository;

use App\Entity\AgenciaBancaria;
use App\Repository\RepositoryAbstract;
use Doctrine\Persistence\ManagerRegistry;

class PlanejamentoMesFolhaRepository extends RepositoryAbstract
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, AgenciaBancaria::class);
    }
    
    /**
     * 
     * @param \DateTime $date
     * @return array<\App\Entity\PlanejamentoMesFolha>
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
