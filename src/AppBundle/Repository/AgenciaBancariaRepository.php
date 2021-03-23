<?php

namespace AppBundle\Repository;

class AgenciaBancariaRepository extends RepositoryAbstract
{
    /**
     * 
     * @param string $banco
     * @param string $agencia
     * @return AppBundle\Entity\AgenciaBancaria|null
     */
    public function findOneByAgenciaSemDv($banco, $agencia)
    {
        $qb = $this->createQueryBuilder('ab');
        
        return $qb
            ->select('ab')
            ->where('ab.coAgenciaBancaria LIKE :agencia')
            ->andWhere('ab.coBanco = :banco')
            ->andWhere('ab.stRegistroAtivo = :stAtivo')
            ->setMaxResults(1)            
            ->setParameters([
                'agencia' => substr($agencia, 0, 5) . '%',
                'banco' => $banco,
                'stAtivo' => 'S',
            ])->getQuery()->getOneOrNullResult();
    }
}
