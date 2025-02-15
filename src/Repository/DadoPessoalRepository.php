<?php

namespace App\Repository;

use App\Entity\Publicacao;
use App\Entity\DadoPessoal;
use App\Entity\AgenciaBancaria;
use App\Repository\RepositoryAbstract;
use Doctrine\Persistence\ManagerRegistry;

class DadoPessoalRepository extends RepositoryAbstract
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, AgenciaBancaria::class);
    }
    
    /**
     *      
     * @param string $cpf
     * @return DadoPessoal
     */
    public function getByCpfAndPublicacao($cpf, Publicacao $publicacao)
    {
        $qb = $this->createQueryBuilder('dp');
        
        return $qb->select('dp')
            ->innerJoin('dp.pessoaFisica', 'pf')
            ->innerJoin('pf.pessoasPerfis', 'pperf')
            ->innerJoin('pperf.projetosPessoas', 'pp')
            ->innerJoin('pp.projeto', 'proj')
            ->where('pf.nuCpf = :nuCpf')
            ->andWhere('proj.publicacao = :publicacao')
            ->setParameter('nuCpf', $cpf)
            ->setParameter('publicacao', $publicacao)
            ->setMaxResults(1)
            ->getQuery()
            ->getSingleResult();
    }    
}
