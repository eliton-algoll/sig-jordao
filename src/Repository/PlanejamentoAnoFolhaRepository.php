<?php

namespace App\Repository;

use App\Entity\PlanejamentoAnoFolha;
use App\Repository\RepositoryAbstract;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\ParameterBag;
use App\Exception\PlanejamentoAberturaFolhaNotExistsException;

class PlanejamentoAnoFolhaRepository extends RepositoryAbstract
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, PlanejamentoAnoFolha::class);
    }
    
    /**
     * 
     * @param integer $id
     * @return \App\Entity\PlanejamentoAnoFolha
     * @throws PlanejamentoAberturaFolhaNotExistsException
     */
    public function getLastByPublicacao($id)
    {
        $qb = $this->createQueryBuilder('paf');
        
        $result = $qb
            ->select('paf')
            ->where('paf.publicacao = :coPublicacao')
            ->andWhere('paf.stRegistroAtivo = :stAtivo')
            ->setParameters([
                'coPublicacao' => $id,
                'stAtivo' => 'S',
            ])
            ->orderBy('paf.nuAno', 'DESC')
            ->setMaxResults(1)
            ->getQuery()
            ->getOneOrNullResult()
        ;
        
        if (!$result) {
            throw new PlanejamentoAberturaFolhaNotExistsException();
        }
        
        return $result;
    }
    
    /**
     * 
     * @param ParameterBag $pb
     * @return \Doctrine\ORM\Query
     */
    public function search(ParameterBag $pb)
    {
        $qb = $this->createQueryBuilder('paf');
        
        $qb->select('paf')
            ->where('paf.stRegistroAtivo = :stAtivo')
            ->setParameter('stAtivo', 'S')
        ;
        
        if ($pb->get('publicacao')) {
            $qb->andWhere('paf.publicacao = :publicacao')
                ->setParameter('publicacao', $pb->get('publicacao'));
        }
        if ($pb->get('nuAnoReferencia')) {
            $qb->andWhere('paf.nuAno = :nuAnoReferencia')
                ->setParameter('nuAnoReferencia', $pb->get('nuAnoReferencia'));
        }
        if (!$pb->has('sort')) {
            $qb->orderBy('paf.nuAno', 'DESC');
        }
        
        $this->orderPagination($qb, $pb);
        
        return $qb->getQuery();
    }
}
