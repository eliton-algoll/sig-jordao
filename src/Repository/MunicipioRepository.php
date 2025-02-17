<?php

namespace App\Repository;

use App\Entity\Uf;
use App\Entity\AgenciaBancaria;
use App\Entity\Municipio;
use App\Repository\RepositoryAbstract;
use Doctrine\Persistence\ManagerRegistry;

class MunicipioRepository extends RepositoryAbstract
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Municipio::class);
    }
    
    /**
     * @param Uf $uf
     * @param boolean $returnArrayResult
     * @return \App\Entity\Municipio[] | array
     */
    public function findByUf(Uf $uf, $returnArrayResult = true)
    {
        $qb = $this->createQueryBuilder('m');
        $qb
            ->andWhere("m.stRegistroAtivo = 'S'")
            ->andWhere('m.coUfIbge = :coUfIbge')
            ->setParameter('coUfIbge', $uf->getCoUfIbge())
            ->orderBy('m.noMunicipio', 'asc');
        
        if ($returnArrayResult) {
            return $qb->getQuery()->getArrayResult();
        }
        
        return $qb->getQuery()->getResult();
    }
}
