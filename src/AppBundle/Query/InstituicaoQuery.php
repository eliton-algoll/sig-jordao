<?php

namespace AppBundle\Query;

use AppBundle\Entity\Municipio;
use AppBundle\Entity\Instituicao;
use AppBundle\Repository\InstituicaoRepository;
use AppBundle\Repository\CampusInstituicaoRepository;
use Knp\Component\Pager\Pagination\PaginationInterface;
use Knp\Component\Pager\Paginator;
use Symfony\Component\HttpFoundation\ParameterBag;

class InstituicaoQuery
{

    /**
     *
     * @var Paginator
     */
    private $paginator;

    /**
     * @var InstituicaoRepository
     */
    private $instituicaoRepository;
    
    /**
     * @var CampusInstituicaoRepository
     */
    private $campusRepository;
    
    /**
     * @param InstituicaoRepository $instituicaoRepository
     * @param CampusInstituicaoRepository $campusRepository
     */
    public function __construct(
        Paginator $paginator,
        InstituicaoRepository $instituicaoRepository, 
        CampusInstituicaoRepository $campusRepository
    ) {
        $this->paginator = $paginator;
        $this->instituicaoRepository = $instituicaoRepository;
        $this->campusRepository = $campusRepository;
    }

    /**
     *
     * @param ParameterBag $pb
     * @return PaginationInterface
     */
    public function searchInst(ParameterBag $pb)
    {
        return $this->paginator->paginate(
            $this->instituicaoRepository->findByFilter($pb),
            $pb->getInt('page', 1),
            $pb->getInt('limit', 10)
        );
    }
    
    /**
     * @param Municipio $municipio
     * @return array
     */
    public function listInstituicoesByMunicipio(Municipio $municipio)
    {
        return $this->instituicaoRepository->findByMunicipio($municipio, true);
    }
    
    /**
     * @param Instituicao $instituicao
     * @return array
     */
    public function listCampusByInstituicao(Instituicao $instituicao)
    {
        return $this->campusRepository->findByInstituicao($instituicao, true);
    }
}
