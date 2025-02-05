<?php

namespace AppBundle\Repository;

use Symfony\Component\HttpFoundation\ParameterBag;
use Doctrine\ORM\Query;
use AppBundle\Entity\RetornoCriacaoConta;

class RetornoCriacaoContaRepository extends RepositoryAbstract
{
    /**
     * 
     * @param string $noArquivoOriginal
     * @return RetornoCriacaoConta
     */
    public function getByArquivoOriginal($noArquivoOriginal)
    {
        $qb = $this->createQueryBuilder('rcc');
        
        return $qb
            ->select('rcc')
            ->where('rcc.stRegistroAtivo = :stAtivo')
            ->andWhere('LOWER(rcc.noArquivoOriginal) = LOWER(:noArquivoOriginal)')
            ->setMaxResults(1)
            ->setParameters([
                'stAtivo' => 'S',
                'noArquivoOriginal' => $noArquivoOriginal,
            ])->getQuery()->getSingleResult();
    }
    
    /**
     * 
     * @return array
     */
    public function findUniqueRefs()
    {
        $sql = "SELECT MAX(CO_SEQ_RETORNO_CRIACAO_CONTA) AS CO_SEQ_RETORNO_CRIACAO_CONTA
                FROM DBPET.TB_RETORNO_CRIACAO_CONTA
                WHERE ST_REGISTRO_ATIVO = 'S'
                GROUP BY TO_CHAR(DT_INCLUSAO, 'YYYYMM')";
        
        return $this
            ->getEntityManager()
            ->getConnection()
            ->executeQuery($sql)
            ->fetchAll(\PDO::FETCH_COLUMN);
    }
    
    /**
     * 
     * @param ParameterBag $pb
     * @return Query
     */
    public function search(ParameterBag $pb)
    {
        $qb = $this->createQueryBuilder('rcc');
        
        $qb->select('rcc, pub, prog')
            ->innerJoin('rcc.publicacao', 'pub')
            ->innerJoin('pub.programa', 'prog')
            ->where('pub.stRegistroAtivo = :stAtivo')
            ->andWhere('rcc.stRegistroAtivo = :stAtivo')
            ->setParameter('stAtivo', 'S');
        
        if ($pb->get('mesAnoRetorno')) {
            
            $dtIni = \DateTime::createFromFormat('m/Y', $pb->get('mesAnoRetorno'));
            $dtFim = clone $dtIni;            
            
            $dtIni->setTime(0,0,0);
            $dtFim->setTime(23,59,59);
            $dtIni->modify('first day of this month');
            $dtFim->modify('last day of this month');
            
            $qb->andWhere('rcc.dtInclusao >= :dtIni')
                ->andWhere('rcc.dtInclusao <= :dtFim')
                ->setParameter('dtIni', $dtIni)
                ->setParameter('dtFim', $dtFim);
        }
        
        $query = $qb->getQuery();
        $query->setHydrationMode(Query::HYDRATE_ARRAY);
        
        return $query;
    }
    
    /**
     * 
     * @return RetornoCriacaoConta
     */
    public function getLast()
    {
        $qb = $this->createQueryBuilder('rcc');
        
        return $qb
            ->where('rcc.stRegistroAtivo = :stAtivo')
            ->setParameter('stAtivo', 'S')
            ->orderBy('rcc.dtInclusao', 'DESC')
            ->setMaxResults(1)
            ->getQuery()
            ->getSingleResult();
    }
    
    /**
     * 
     * @param RetornoCriacaoConta $retornoCriacaoConta
     * @param ParameterBag $pb
     * @return array
     */
    public function report(RetornoCriacaoConta $retornoCriacaoConta, ParameterBag $pb)
    {
        $qb = $this->createQueryBuilder('rcc');
        
        $qb->select('rcc, pub, prog, crcc, drcc, rrcc')
            ->innerJoin('rcc.publicacao', 'pub')
            ->innerJoin('pub.programa', 'prog')
            ->innerJoin('rcc.cabecalhoRetornoCriacaoConta', 'crcc')
            ->innerJoin('rcc.rodapeRetornoCriacaoConta', 'rrcc')
            ->where('rcc.stRegistroAtivo = :stAtivo')
            ->andWhere('rcc.coSeqRetornoCriacaoConta = :retornoCriacaoConta')
            ->setParameter('stAtivo', 'S')
            ->setParameter('retornoCriacaoConta', $retornoCriacaoConta)
            ->orderBy('drcc.noBeneficiario');
        
        if ($pb->get('stCadastro')) {
            $qb->leftJoin('rcc.detalheRetornoCriacaoConta', 'drcc', 'WITH', 'drcc.nuSituacaoCadastro IN(:stCadastro)')            
                ->setParameter('stCadastro', (array) $pb->get('stCadastro'));
        } else {
            $qb->innerJoin('rcc.detalheRetornoCriacaoConta', 'drcc');
        }
        
        return $qb->getQuery()->getOneOrNullResult(Query::HYDRATE_ARRAY);
    }
}
