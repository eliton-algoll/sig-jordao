<?php

namespace App\Query;

use App\Repository\TipoAreaTematicaRepository;
use Knp\Component\Pager\Paginator;
use Symfony\Component\HttpFoundation\ParameterBag;

class TipoAreaTematicaQuery
{
    /**
     * @var TipoAreaTematicaRepository
     */
    private $repository;

    /**
     *
     * @var Paginator
     */
    private $paginator;

    public function __construct($repository, $paginator)
    {
        $this->repository = $repository;
        $this->paginator  = $paginator;
    }

    /**
     * @param ParameterBag $params
     * @return \Knp\Component\Pager\Pagination\PaginationInterface
     */
    public function search(ParameterBag $params)
    {
        return $this->paginator->paginate(
            $this->repository->findByFilter($params),
            $params->getInt('page', 1),
            $params->getInt('limit', 10)
        );
    }

}