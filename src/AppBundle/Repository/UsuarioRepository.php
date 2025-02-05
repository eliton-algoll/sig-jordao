<?php

namespace AppBundle\Repository;

use Symfony\Component\HttpFoundation\ParameterBag;

class UsuarioRepository extends RepositoryAbstract
{
    /**
     *
     * @param ParameterBag $pb
     * @return Doctrine\ORM\Query
     */
    public function findByFilter(ParameterBag $pb)
    {
        $qb = $this->createQueryBuilder('u');
        $qb->select('u')
        ->join('u.pessoaFisica', 'pf')
        ->join('pf.pessoasPerfis', 'pp')
        ->join('pf.pessoa', 'p');

        $qb->andWhere("pp.perfil = 1 ");
        $qb->andWhere("pp.stRegistroAtivo = 'S'");

        if (!is_null($pb->get('nuCpf'))) {
            $cpf = preg_replace("/[^0-9]/", "", $pb->get('nuCpf'));
            $qb->andWhere('pf.nuCpf LIKE :nuCpf');
            $qb->setParameter('nuCpf', $cpf . '%', \PDO::PARAM_STR);
        }

        if (!is_null($pb->get('noNome'))) {
            $qb->andWhere('p.noPessoa LIKE :noNome');
            $qb->setParameter('noNome', '%' . $pb->get('noNome') . '%', \PDO::PARAM_STR);
        }

        if (!is_null($pb->get('dsLogin'))) {
            $qb->andWhere('u.dsLogin LIKE :dsLogin');
            $qb->setParameter('dsLogin', '%' . $pb->get('dsLogin') . '%', \PDO::PARAM_STR);
        }

        if (!is_null($pb->get('stRegistroAtivo'))) {
            $qb->andWhere('u.stRegistroAtivo = :stAtivo');
            $qb->setParameter('stAtivo', $pb->get('stRegistroAtivo'), \PDO::PARAM_STR);
        }

        $qb->orderBy('u.stRegistroAtivo', 'DESC');
        $qb->orderBy('u.dsLogin');

        $this->orderPagination($qb, $pb);

        return $qb->getQuery();
    }
}
