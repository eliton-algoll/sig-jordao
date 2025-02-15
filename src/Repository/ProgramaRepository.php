<?php

namespace App\Repository;

use App\Entity\AgenciaBancaria;
use App\Repository\RepositoryAbstract;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\ParameterBag;

class ProgramaRepository extends RepositoryAbstract
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, AgenciaBancaria::class);
    }
    
    /**
     * @param ParameterBag $params
     * @return \Doctrine\ORM\QueryBuilder
     */
    public function search(ParameterBag $params)
    {
        $qb = $this->createQueryBuilder('p');

        if ($params->get('dsPrograma')) {
            $qb
                ->andWhere('upper(p.dsPrograma) like :dsPrograma')
                ->setParameter('dsPrograma', '%' . mb_strtoupper($params->get('dsPrograma')) . '%');
        }
        
        $qb->orderBy('p.dsPrograma', 'asc');

        return $qb;
    }
}
