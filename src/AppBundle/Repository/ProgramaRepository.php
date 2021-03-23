<?php

namespace AppBundle\Repository;

use Symfony\Component\HttpFoundation\ParameterBag;

class ProgramaRepository extends RepositoryAbstract
{
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
