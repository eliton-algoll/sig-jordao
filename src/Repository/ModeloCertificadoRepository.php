<?php

namespace App\Repository;

use App\Entity\ModeloCertificado;
use Doctrine\DBAL\Types\Type;
use Doctrine\ORM\UnexpectedResultException;
use Symfony\Component\HttpFoundation\ParameterBag;

class ModeloCertificadoRepository extends RepositoryAbstract
{

    /**
     * @param array $params
     * @param array $orderBy
     * @return ModeloCertificado[]
     */
    public function search(array $params = [], $orderBy = [])
    {
        return $this->buildSearchQuery($params, $orderBy)->getResult();
    }

    /**
     * @param array $params
     * @param array $orderBy
     * @return \Doctrine\ORM\Query
     */
    public function buildSearchQuery(array $params = [], array $orderBy = [])
    {
        $qb = $this->createQueryBuilder('mc')
            ->select('mc, pr')
            ->innerJoin('mc.programa', 'pr');

        if ($orderBy) {
            $this->addOrderBy($qb, $orderBy);
        } else {
            $qb->orderBy('mc.coSeqModeloCertificado', 'DESC');
        }

        if (isset($params['nome']) && $params['nome'] !== '') {
            $qb->andWhere('upper(mc.noModeloCertificado) like upper(:noModeloCertificado)')
                ->setParameter('noModeloCertificado', '%' . $params['nome'] . '%');
        }

        if (isset($params['programa']) && $params['programa'] !== '') {
            $qb->andWhere('mc.programa = :programa')
                ->setParameter('programa', $params['programa'], Type::INTEGER);
        }

        if (isset($params['tipo']) && $params['tipo'] !== '') {
            $qb->andWhere('mc.tpDocumento = :tpDocumento')
                ->setParameter('tpDocumento', $params['tipo']);
        }

        if (isset($params['ativo']) && $params['ativo'] !== '') {
            $qb->andWhere('mc.stRegistroAtivo = :stRegistroAtivo')
                ->setParameter('stRegistroAtivo', $params['ativo']);
        }

        return $qb->getQuery();
    }

    /**
     * @param ModeloCertificado $modeloCertificado
     * @return bool
     */
    public function verificaNomeDuplicado(ModeloCertificado $modeloCertificado)
    {
        $qb = $this->createQueryBuilder('mc')
            ->select('COUNT(1)')
            ->where('UPPER(mc.noModeloCertificado) = UPPER(:noModeloCertificado)')
            ->setParameter('noModeloCertificado', $modeloCertificado->getNoModeloCertificado());

        if ($modeloCertificado->getCoSeqModeloCertificado()) {
            $qb->andWhere('mc.coSeqModeloCertificado <> :coSeqModeloCertificado')
                ->setParameter('coSeqModeloCertificado', $modeloCertificado->getCoSeqModeloCertificado());
        }

        try {
            return (bool) $qb->getQuery()->getSingleScalarResult();
        } catch (UnexpectedResultException $e) {
            return false;
        }
    }

    /**
     * @param ModeloCertificado $modeloCertificado
     * @return int
     */
    public function inativaOutrosModelos(ModeloCertificado $modeloCertificado) {
        $qb = $this->createQueryBuilder('mc')
            ->update()
            ->set('mc.stRegistroAtivo', ':inativo')
            ->where('mc.coSeqModeloCertificado <> :coSeqModeloCertificado')
            ->andWhere('mc.programa = :programa')
            ->andWhere('mc.tpDocumento = :tpDocumento')
            ->andWhere('mc.stRegistroAtivo = :ativo')
            ->setParameter('coSeqModeloCertificado', $modeloCertificado->getCoSeqModeloCertificado())
            ->setParameter('programa', $modeloCertificado->getPrograma())
            ->setParameter('tpDocumento', $modeloCertificado->getTpDocumento())
            ->setParameter('inativo', 'N')
            ->setParameter('ativo', 'S');

        return $qb->getQuery()->execute();
    }
}