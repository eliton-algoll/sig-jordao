<?php

namespace AppBundle\Repository;

use AppBundle\Entity\SituacaoFolha;
use AppBundle\Entity\FolhaPagamento;

class TramitacaoSituacaoFolhaRepository extends \Doctrine\ORM\EntityRepository
{
    /**
     * 
     * @param FolhaPagamento $folhaPagamento
     * @return \AppBundle\Entity\TramitacaoSituacaoFolha[]
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
