<?php

namespace AppBundle\Repository;

use AppBundle\Entity\Publicacao;
use AppBundle\Entity\DadoPessoal;

class DadoPessoalRepository extends RepositoryAbstract
{
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
