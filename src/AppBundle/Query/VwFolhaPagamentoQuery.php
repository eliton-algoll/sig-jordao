<?php

namespace AppBundle\Query;

use Symfony\Component\HttpFoundation\ParameterBag;
use Knp\Component\Pager\Paginator;
use AppBundle\Repository\VwFolhaPagamentoRepository;

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
