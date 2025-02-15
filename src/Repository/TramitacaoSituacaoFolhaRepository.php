<?php

namespace App\Repository;

use App\Entity\SituacaoFolha;
use App\Entity\FolhaPagamento;

class TramitacaoSituacaoFolhaRepository extends \Doctrine\ORM\EntityRepository
{
    /**
     * 
     * @param FolhaPagamento $folhaPagamento
     * @return \App\Entity\TramitacaoSituacaoFolha[]
     */
    public function findEnvioFnsReferenciaByFolha(FolhaPagamento $folhaPagamento)
    {
        $qb = $this->createQueryBuilder('tsf');
        
        return $qb
            ->select('tsf')
            ->innerJoin('tsf.folhaPagamento', 'fp')
            ->where('fp.nuMes = :nuMes')
            ->andWhere('fp.nuAno = :nuAno')            
            ->andWhere('fp.stRegistroAtivo = :stAtivo')
            ->andWhere('tsf.situacaoFolha = :situacao')
            ->setParameters([
                'nuMes' => $folhaPagamento->getNuMes(),
                'nuAno' => $folhaPagamento->getNuAno(),
                'stAtivo' => 'S',
                'situacao' => SituacaoFolha::ENVIADA_FUNDO,
            ])->getQuery()->getResult();
    }
}
