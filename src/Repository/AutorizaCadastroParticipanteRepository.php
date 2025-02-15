<?php

namespace App\Repository;

use App\Entity\AutorizaCadastroParticipante;
use App\Entity\Projeto;
use App\Exception\AutorizacaoCadastroParticipanteVigenteExistsException;
use Symfony\Component\HttpFoundation\ParameterBag;

class AutorizaCadastroParticipanteRepository extends RepositoryAbstract
{
    /**
     * 
     * @param Projeto $projeto
     * @throws AutorizacaoCadastroParticipanteVigenteExistsException
     */
    public function checkExistisAutorizacaoVigente(
        Projeto $projeto,
        AutorizaCadastroParticipante $acp = null 
    ) {
        $qb = $this->createQueryBuilder('acp');
        
        $autorizaCadastroParticipante = $qb->select('acp')
            ->where('acp.projeto = :projeto')
            ->andWhere('acp.dtInicioPeriodo <= :now AND acp.dtFimPeriodo >= :now')
            ->andWhere('acp.stRegistroAtivo = :stAtivo')
            ->setMaxResults(1)
            ->setParameters([
                'projeto' => $projeto->getCoSeqProjeto(),
                'now' => new \DateTime(),
                'stAtivo' => 'S',
            ])->getQuery()->getOneOrNullResult();
        
        if ($autorizaCadastroParticipante && $autorizaCadastroParticipante != $acp) {
            throw new AutorizacaoCadastroParticipanteVigenteExistsException($autorizaCadastroParticipante);
        }
    }
    
    /**
     * 
     * @param Projeto $projeto
     * @throws AutorizacaoCadastroParticipanteVigenteExistsException
     */
    public function checkExistisAutorizacaoVigenteOuFutura(
        Projeto $projeto,
        AutorizaCadastroParticipante $acp = null 
    ) {
        $qb = $this->createQueryBuilder('acp');
        
        $autorizaCadastroParticipante = $qb->select('acp')
            ->where('acp.projeto = :projeto')
            ->andWhere($qb->expr()->orX(
                'acp.dtInicioPeriodo > :now',
                'acp.dtInicioPeriodo <= :now AND acp.dtFimPeriodo >= :now'
            ))->andWhere('acp.stRegistroAtivo = :stAtivo')
            ->setMaxResults(1)
            ->setParameters([
                'projeto' => $projeto->getCoSeqProjeto(),
                'now' => new \DateTime(),
                'stAtivo' => 'S',
            ])->getQuery()->getOneOrNullResult();
        
        if ($autorizaCadastroParticipante && $autorizaCadastroParticipante != $acp) {
            throw new AutorizacaoCadastroParticipanteVigenteExistsException($autorizaCadastroParticipante);
        }
    }
    
    /**
     * 
     * @param ParameterBag $pb
     * @return \Doctrine\ORM\Query
     */
    public function search(ParameterBag $pb)
    {
        $qb = $this->createQueryBuilder('acp');
        
        $qb->select('acp')
            ->innerJoin('acp.projeto', 'proj')
            ->innerJoin('proj.publicacao', 'pub')
            ->innerJoin('acp.folhaPagamento', 'fp')
            ->where('acp.stRegistroAtivo = :stAtivo')
            ->setParameter('stAtivo', 'S');
        
        if ($pb->get('publicacao')) {
            $qb->andWhere('proj.publicacao = :publicacao')
                ->setParameter('publicacao', $pb->get('publicacao'));
        }
        if ($pb->get('projeto')) {
            $qb->andWhere('proj.coSeqProjeto = :projeto')
                ->setParameter('projeto', $pb->get('projeto'));
        }
        if ($pb->get('folhaPagamento')) {
            $qb->andWhere('acp.folhaPagamento = :folhaPagamento')
                ->setParameter('folhaPagamento', $pb->get('folhaPagamento'));
        }
        
        $this->orderPagination($qb, $pb);
        
        return $qb->getQuery();
    }
}
