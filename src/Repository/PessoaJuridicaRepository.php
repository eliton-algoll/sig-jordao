<?php

namespace App\Repository;

use App\Entity\Municipio;
use App\Entity\AgenciaBancaria;
use App\Entity\PessoaJuridica;
use App\Repository\RepositoryAbstract;
use Doctrine\Persistence\ManagerRegistry;

class PessoaJuridicaRepository extends RepositoryAbstract
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, PessoaJuridica::class);
    }
    
    /**
     * @param Municipio $municipio
     * @return \App\Entity\PessoaJuridica[] | array
     */
    public function findSecretariasSaudeByMunicipio(Municipio $municipio)
    {
        $qb = $this->createQueryBuilder('pj');
        $qb
            ->select('pj.nuCnpj, p.noPessoa')
            ->join('pj.pessoa', 'p')
            ->andWhere("pj.stRegistroAtivo = 'S'")
            ->andWhere('p.coMunicipioIbge = :municipio')
            ->setParameter('municipio', $municipio->getCoMunicipioIbge())
            ->andWhere('pj.coNaturezaJuridica in (:naturezasJuridicas)')
            ->setParameter('naturezasJuridicas', array(1031, 1201, 1023))
            ->orderBy('p.noPessoa', 'asc');
        
        return $qb->getQuery()->getArrayResult();
    }
    
    /**
     * @param integer $coMunicipioIbge
     * @return \App\Entity\PessoaJuridica[] | array
     */
    public function findSecretariasSaudeByCoMunicipioIbge($coMunicipioIbge)
    {
        $qb = $this->createQueryBuilder('pj');
        $qb
            ->select('pj, p')
            ->join('pj.pessoa', 'p')
            ->andWhere("pj.stRegistroAtivo = 'S'")
            ->andWhere('p.coMunicipioIbge = :municipio')
            ->setParameter('municipio', $coMunicipioIbge)
            ->andWhere('pj.coNaturezaJuridica in (:naturezasJuridicas)')
            ->setParameter('naturezasJuridicas', array(1031, 1201, 1023))
            ->orderBy('p.noPessoa', 'asc');
        
        return $qb;
    }
    
    /**
     * @param strint $cnpj
     * @return array
     */
    public function findSecretariaSaudeByCnpj($cnpj)
    {
        $qb = $this->createQueryBuilder('pj');
        $qb
            ->select('pj.nuCnpj, p.noPessoa, p.noMunicipio, p.sgUf')
            ->join('pj.pessoa', 'p')
            ->andWhere('pj.nuCnpj = :cnpj')
            ->setParameter('cnpj', $cnpj)
            ->andWhere("pj.stRegistroAtivo = 'S'")
            ->setMaxResults(1);
        
        return $qb->getQuery()->getOneOrNullResult();
    }
}
