<?php

namespace App\Query;

use App\Repository\PlanejamentoAnoFolhaRepository;
use Knp\Component\Pager\Paginator;
use Symfony\Component\HttpFoundation\ParameterBag;

class PlanejamentoAberturaFolhaQuery
{
    /**
     *
     * @var Paginator 
     */
    private $paginator;
    
    /**
     *
     * @var PlanejamentoAnoFolhaRepository
     */
    private $planejamentoAnoFolhaRepository;
    
    /**
     * 
     * @param Paginator $paginator
     * @param PlanejamentoAnoFolhaRepository $planejamentoAnoFolhaRepository
     */
    public function __construct(
        Paginator $paginator,
        PlanejamentoAnoFolhaRepository $planejamentoAnoFolhaRepository
    ) {
        $this->paginator = $paginator;
        $this->planejamentoAnoFolhaRepository = $planejamentoAnoFolhaRepository;
    }

    /**
     * 
     * @param integer $id
     * @return \App\Entity\PlanejamentoAnoFolha
     */
    public function getLastPlanejamentoByPublicacao($id)
    {
        return $this->planejamentoAnoFolhaRepository->getLastByPublicacao($id);
    }
    
    /**
     * 
     * @param ParameterBag $pb
     * @return \Knp\Component\Pager\Pagination\PaginationInterface
     */
    public function search(ParameterBag $pb)
    {
        return $this->paginator->paginate(
            $this->planejamentoAnoFolhaRepository->search($pb),
            $pb->getInt('page', 1),
            $pb->getInt('limt', 10)
        );
    }
    
}
