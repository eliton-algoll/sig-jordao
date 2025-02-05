<?php

namespace AppBundle\Query;

use Knp\Component\Pager\Paginator;
use AppBundle\Repository\VwGrupoatuacaoQtprofissionalRepository;

class VwGrupoatuacaoQtprofissionalQuery
{
    /**
     * @var Paginator
     */
    private $paginator;
    
    /**
     * @var VwGrupoatuacaoQtprofissionalRepository
     */
    private $vwGrupoatuacaoQtprofissionalRepository;

    public function __construct($paginator, $vwGrupoatuacaoQtprofissionalRepository)
    {
        $this->paginator = $paginator;
        $this->vwGrupoatuacaoQtprofissionalRepository = $vwGrupoatuacaoQtprofissionalRepository;
    }
    
    public function quantidadeDePerfisPorGrupoDeAtuacao($coProjeto)
    {
        return $this->vwGrupoatuacaoQtprofissionalRepository->quantidadeDePerfisPorGrupoDeAtuacao($coProjeto);
    }
    
}
