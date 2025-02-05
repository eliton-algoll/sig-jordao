<?php

namespace AppBundle\CommandBus;

use AppBundle\Repository\GrupoAtuacaoRepository;

final class ConfirmarGrupoTutorialHandler
{
    /**
     * @var GrupoAtuacaoRepository
     */
    private $grupoAtuacaoRepository;

    /**
     * ConfirmarGrupoTutorialHandler constructor.
     * @param GrupoAtuacaoRepository $grupoAtuacaoRepository
     */
    public function __construct(GrupoAtuacaoRepository $grupoAtuacaoRepository)
    {
        $this->grupoAtuacaoRepository = $grupoAtuacaoRepository;
    }

    /**
     * @param ConfirmarGrupoTutorialCommand $command
     */
    public function handle(ConfirmarGrupoTutorialCommand $command)
    {
        $grupoAtuacao = $command->getGrupoAtuacao();
        $grupoAtuacao->confirmar();

        $this->grupoAtuacaoRepository->add($grupoAtuacao);
    }
}