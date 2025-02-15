<?php

namespace App\CommandBus;

use App\Repository\GrupoAtuacaoRepository;

class InativarGrupoAtuacaoHandler
{
    /**
     * @var GrupoAtuacaoRepository
     */
    private $grupoAtuacaoRepository;
    
    /**
     * @param GrupoAtuacaoRepository $grupoAtuacaoRepository
     */
    public function __construct(GrupoAtuacaoRepository $grupoAtuacaoRepository)
    {
        $this->grupoAtuacaoRepository = $grupoAtuacaoRepository;
    }

    /**
     * @param InativarGrupoAtuacaoCommand $command
     */
    public function handle(InativarGrupoAtuacaoCommand $command)
    {
        $grupo = $this->grupoAtuacaoRepository->find($command->getCoSeqGrupoAtuacao());
        $grupo->inativar();
        
        $this->grupoAtuacaoRepository->add($grupo);
    }
}