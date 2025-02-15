<?php

namespace App\Query;

use Symfony\Component\HttpFoundation\ParameterBag;
use App\Repository\ProgramaRepository;
use Knp\Component\Pager\Paginator;

class ProgramaQuery
{
    /**
     * @var ProgramaRepository
     */
    protected $repository;

    /**
     * @var Paginator
     */
    protected $paginator;

    /**
     * @param ProgramaRepository $repository
     * @param Paginator $paginator
     */
    public function __construct($repository, $paginator)
    {
        $this->repository = $repository;
        $this->paginator = $paginator;
    }

    /**
     * @param $id
     * @return object|null
     */
    public function searchById($id)
    {
        return $this->repository->find($id);
    }

    /**
     * @param ParameterBag $params
     * @return \Knp\Component\Pager\Pagination\PaginationInterface
     */
    public function search(ParameterBag $params)
    {
        return $this->paginator->paginate(
            $this->repository->search($params),
            $params->getInt('page', 1),
            $params->getInt('limit', 10)
        );
    }
}