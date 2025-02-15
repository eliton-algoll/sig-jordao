<?php

namespace App\Query;

use Symfony\Component\HttpFoundation\ParameterBag;
use Knp\Component\Pager\Paginator;
use App\Repository\VwFolhaPagamentoRepository;

class VwFolhaPagamentoQuery
{
    /**
     * @var Paginator
     */
    private $paginator;
    
    /**
     * @var VwFolhaPagamentoRepository
     */
    private $vwFolhaPagamentoRepository;
    
    public function __construct(
        Paginator $paginator, 
        VwFolhaPagamentoRepository $vwFolhaPagamentoRepository)
    {
        $this->paginator = $paginator;
        $this->vwFolhaPagamentoRepository = $vwFolhaPagamentoRepository;
    }
    
    public function searchRelatorioPagamento(ParameterBag $params) {
        return $this->vwFolhaPagamentoRepository->searchRelatorioPagamento($params);
    }
}
