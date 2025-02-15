<?php

namespace App\Query;

use App\Repository\GrupoAtuacaoRepository;

class GrupoAtuacaoQuery
{
    /**
     * @var GrupoAtuacaoRepository
     */
    private $grupoAtuacaoRepository;
    
    /**
     * @param GrupoAtuacaoRepository repository
     */
    public function __construct($grupoAtuacaoRepository)
    {
        $this->grupoAtuacaoRepository = $grupoAtuacaoRepository;
    }
    
    /**
     * @param integer $coProjeto
     * @return \App\Entity\GrupoAtuacao[]
     */
    public function findByProjeto($coProjeto)
    {
        return $this->grupoAtuacaoRepository->findByProjeto($coProjeto);
    }

    /**
     * @param integer $coProjeto
     * @return \App\Entity\GrupoAtuacao[]
     */
    public function findGrupoByProjeto($coProjeto, $areaTematica = null)
    {
        return $this->grupoAtuacaoRepository->findGrupoByProjeto($coProjeto, $areaTematica);
    }
}