<?php

namespace App\Query;

use App\Entity\Municipio;
use App\Entity\Instituicao;
use App\Repository\InstituicaoRepository;
use App\Repository\CampusInstituicaoRepository;
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
     * @param ParameterBag $params
     * @return \Knp\Component\Pager\Pagination\PaginationInterface
     */
    public function searchInst(ParameterBag $params)
    {
        return $this->paginator->paginate(
            $this->instituicaoRepository->findByFilter($params),
            $params->getInt('page', 1),
            $params->getInt('limit', 10)
        );
    }

    /**
     * @param ParameterBag $params
     * @return \Knp\Component\Pager\Pagination\PaginationInterface
     */
    public function searchCampus(ParameterBag $params)
    {
        return $this->paginator->paginate(
            $this->campusRepository->findByFilter($params),
            $params->getInt('page', 1),
            $params->getInt('limit', 10)
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
