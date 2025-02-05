<?php

namespace AppBundle\Repository;

use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\QueryBuilder;
use Symfony\Component\HttpFoundation\ParameterBag;

abstract class RepositoryAbstract extends EntityRepository
{
    /**
     * @param mixed $entity
     */
    public function add($entity)
    {
        $this->getEntityManager()->persist($entity);
    }
    
    /**
     * 
     * @param mixed $entity
     */
    public function remove($entity)
    {
        $this->getEntityManager()->remove($entity);
    }

    public function flush($entity)
    {
        $this->getEntityManager()->flush($entity);
    }
    
    /**
     * Método que pagina consultas em SQL
     * 
     * @param string $sql
     * @param ParameterBag $pb
     * @return string
     */
    protected final function paginateSQL($sql, ParameterBag $pb)
    {
        if ($pb->getInt('page') && $pb->getInt('limit')) {            
            $begin = ($pb->getInt('page') * $pb->getInt('limit')) - $pb->getInt('limit');
            $end = ($pb->getInt('page') * $pb->getInt('limit'));
            
            $sql = "SELECT * FROM(SELECT X.*, ROWNUM RN FROM($sql) X WHERE ROWNUM <= $end) WHERE RN > $begin";
        }
        
        return $sql;
    }

    /**
     *
     * @param QueryBuilder $qb
     * @param array $orderBy
     */
    protected final function addOrderBy(QueryBuilder $qb, array $orderBy)
    {
        foreach ($orderBy as $field => $dir) {
            $qb->addOrderBy($field, $dir);
        }
    }

    /**
     * 
     * @param QueryBuilder $qb
     * @param ParameterBag $pb
     */
    protected final function orderPagination(QueryBuilder $qb, ParameterBag $pb)
    {
        if ($pb->get('sort') && $pb->get('direction')) {
            $qb->addOrderBy($pb->get('sort'), $pb->get('direction'));
        }
    }
    
    /**
     * 
     * @param string $sql
     * @param ParameterBag $pb     
     */
    protected final function orderPaginationSQL(&$sql, ParameterBag $pb, $default = null)
    {
        if ($pb->get('sort') && $pb->get('direction')) {
            $sql .= " ORDER BY " . addslashes(strip_tags($pb->get('sort')));
            $sql .= " " . addslashes(strip_tags($pb->get('direction')));
        } elseif ($default) {
            $sql .= " ORDER BY " . $default;
        }
    }
    
    /**
     * 
     * @param string $sql
     * @param array $criteria
     * @param string $criteriaGlue
     * @param string $criteriaName
     * @throws \InvalidArgumentException
     */
    protected final function setCriteriaToSQL(&$sql, array $criteria, $criteriaGlue = 'WHERE', $criteriaName = '@criteria@')
    {
        if (false === strstr($sql, $criteriaName)) {
            throw new \InvalidArgumentException('O nome ' . $criteriaName . ' não encontrado no SQL.');
        }
        
        $joinedCriteria = ($criteria) ? $criteriaGlue . ' ' . implode(' AND ', $criteria) : null;
        $joinedCriteria = str_pad($joinedCriteria, strlen($joinedCriteria) + 2, ' ', STR_PAD_BOTH);
        
        $sql = str_replace($criteriaName, $joinedCriteria, $sql);
    }
}