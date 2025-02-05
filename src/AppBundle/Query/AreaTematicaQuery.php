<?php

namespace AppBundle\Query;

use AppBundle\Entity\Projeto;
use AppBundle\Repository\AreaTematicaRepository;

class AreaTematicaQuery
{
    /**
     * @var AreaTematicaRepository
     */
    private $repository;

    public function __construct($repository)
    {
        $this->repository = $repository;
    }

    public function listTipoAreaTematicaByProjeto($projeto)
    {
        $arrayResult = array();
        if($projeto) {
            $arrayResult = $this->repository->findTipoAreaTematicaByProjeto($projeto);
            $this->groupResults($arrayResult);
        }
        return $arrayResult;
    }
    
    private function groupResults(&$arrayResult)
    {
        $list = array();
        foreach($arrayResult as $key => $result) {
            $arrayResult[$key]['dsTipoAreaTematica'] = $result['coSeqGrupoAtuacao'] . ' - ' . $result['dsTipoAreaTematica'];
            $search_key = array_search($result['coSeqGrupoAtuacao'], $list);
            if($search_key !== false) {
                $arrayResult[$search_key]['dsTipoAreaTematica'] .= ', ' . $result['dsTipoAreaTematica'];
                unset($arrayResult[$key]);
            }
            $list[] = $result['coSeqGrupoAtuacao'];
        }
    }
}