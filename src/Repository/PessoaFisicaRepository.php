<?php

namespace App\Repository;

class PessoaFisicaRepository extends RepositoryAbstract
{
    public function getByCpf($cpf)
    {
        $qb = $this->createQueryBuilder('pf');
        
        $buildQuery = $qb->select('pf, p, s, m')
            ->join('pf.pessoa', 'p')
            ->leftjoin('pf.sexo', 's')
            ->leftjoin('p.coMunicipioIbge', 'm')
            ->where('pf.nuCpf = :cpf')
            ->setParameter('cpf', $cpf);
        
        $query = $buildQuery->getQuery();
        
        return $query->getSingleResult(\Doctrine\ORM\Query::HYDRATE_ARRAY);    
    }
    
    /**
     * 
     * @param integer|App\Entity\Publicacao $publicacao
     * @param integer|App\Entity\Perfil $perfil
     * @return App\Entity\PessoaFisica[]
     */
    public function listByPublicacaoAndPerfil($publicacao, $perfil)
    {
        return $this->createQueryBuilder('pf')
            ->innerJoin('pf.pessoasPerfis', 'pp')
            ->innerJoin('pp.projetosPessoas', 'projp')
            ->innerJoin('projp.projeto', 'proj')
            ->innerJoin('pf.pessoa', 'p')
            ->where('projp.stRegistroAtivo = :stAtivo')
            ->andWhere('proj.publicacao = :publicacao')
            ->andWhere('pp.perfil = :perfil')
            ->orderBy('p.noPessoa')
            ->setParameters([
                'stAtivo' => 'S',
                'publicacao' => $publicacao,
                'perfil' => $perfil,
            ])->getQuery()->getResult();
    }
}
