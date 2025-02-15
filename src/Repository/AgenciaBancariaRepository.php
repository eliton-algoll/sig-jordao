<?php

namespace App\Repository;

use App\Entity\AgenciaBancaria;
use Doctrine\Persistence\ManagerRegistry;
class AgenciaBancariaRepository extends RepositoryAbstract
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, AgenciaBancaria::class);
    }

    /**
     * 
     * @param string $banco
     * @param string $agencia
     * @return App\Entity\AgenciaBancaria|null
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
