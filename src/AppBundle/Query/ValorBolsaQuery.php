<?php

namespace AppBundle\Query;

use AppBundle\Repository\ValorBolsaProgramaRepository;
use Knp\Component\Pager\Paginator;
use Symfony\Component\HttpFoundation\ParameterBag;

final class ValorBolsaQuery
{
    /**
     *
     * @var Paginator 
     */
    private $paginator;
    
    /**
     *
     * @var ValorBolsaProgramaRepository 
     */
    private $valorBolsaProgramaRepository;    
    
    /**
     * 
     * @param Paginator $paginator
     * @param ValorBolsaProgramaRepository $valorBolsaProgramaRepository
     */
    public function __construct(Paginator $paginator, ValorBolsaProgramaRepository $valorBolsaProgramaRepository)
    {
        $this->paginator = $paginator;
        $this->valorBolsaProgramaRepository = $valorBolsaProgramaRepository;
    }

        
    /**
     * 
     * @param ParameterBag $pb
     * @return type
     */
    public function search(ParameterBag $pb)
    {
        return $this->paginator->paginate(
            $this->valorBolsaProgramaRepository->findByFilter($pb),
            $pb->getInt('page', 1),
            $pb->getInt('limit', 10)
        );
    }
}
