<?php

namespace AppBundle\Query;

use AppBundle\Entity\Municipio;
use AppBundle\Entity\Instituicao;
use AppBundle\Repository\InstituicaoRepository;
use AppBundle\Repository\CampusInstituicaoRepository;

class InstituicaoQuery
{
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
        InstituicaoRepository $instituicaoRepository, 
        CampusInstituicaoRepository $campusRepository
    ) {
        $this->instituicaoRepository = $instituicaoRepository;
        $this->campusRepository = $campusRepository;
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
