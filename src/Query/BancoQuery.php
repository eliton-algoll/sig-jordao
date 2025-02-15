<?php

namespace App\Query;

use App\Repository\BancoRepository;
use Knp\Component\Pager\Pagination\PaginationInterface;
use Knp\Component\Pager\Paginator;
use Symfony\Component\HttpFoundation\ParameterBag;

final class BancoQuery
{
    /**
     *
     * @var Paginator 
     */
    private $paginator;
    
    /**
     *
     * @var BancoRepository
     */
    private $bancoRepository;
    
    /**
     * 
     * @param Paginator $paginator
     * @param BancoRepository $bancoRepository
     */
    public function __construct(Paginator $paginator, BancoRepository $bancoRepository)
    {
        $this->paginator = $paginator;
        $this->bancoRepository = $bancoRepository;
    }

    /**
     * 
     * @param ParameterBag $pb
     * @return PaginationInterface
     */
    public function search(ParameterBag $pb)
    {
        return $this->paginator->paginate(
            $this->bancoRepository->findByFilter($pb),
            $pb->getInt('page', 1),
            $pb->getInt('limit', 10)
        );
    }

}
