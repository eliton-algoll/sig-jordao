<?php

namespace App\Query;

use Symfony\Component\HttpFoundation\ParameterBag;
use Knp\Component\Pager\Paginator;
use Knp\Component\Pager\Pagination\PaginationInterface;
use App\Repository\RetornoCriacaoContaRepository;
use App\Entity\RetornoCriacaoConta;

class RetornoCriacaoContaQuery
{
    /**
     *
     * @var Paginator 
     */
    private $paginator;
    
    /**
     *
     * @var RetornoCriacaoContaRepository 
     */
    private $retornoCriacaoContaRepository;
    
    /**
     * 
     * @param Paginator $paginator
     * @param RetornoCriacaoContaRepository $retornoCriacaoContaRepository
     */
    public function __construct(
        Paginator $paginator,
        RetornoCriacaoContaRepository $retornoCriacaoContaRepository
    ) {
        $this->paginator = $paginator;
        $this->retornoCriacaoContaRepository = $retornoCriacaoContaRepository;
    }

    /**
     * 
     * @param ParameterBag $pb
     * @return PaginationInterface
     */
    public function search(ParameterBag $pb)
    {
        return $this->paginator->paginate(
                $this->retornoCriacaoContaRepository->search($pb),
                $pb->getInt('page', 1),
                $pb->getInt('limit', 10)
            );
    }
    
    /**
     * 
     * @return RetornoCriacaoConta
     */
    public function getLast()
    {
        return $this->retornoCriacaoContaRepository->getLast();
    }
    
    /**
     * 
     * @param RetornoCriacaoConta $retornoCriacaoConta
     * @param ParameterBag $pb
     * @return array
     */
    public function report(RetornoCriacaoConta $retornoCriacaoConta, ParameterBag $pb)
    {
        return $this->retornoCriacaoContaRepository->report($retornoCriacaoConta, $pb);
    }
}
