<?php

namespace App\CommandBus;

use App\CommandBus\InativarPlanejamentoAberturaFolhaPagamentoCommand;
use App\Repository\PlanejamentoAnoFolhaRepository;

final class InativarPlanejamentoAberturaFolhaPagamentoHandler
{
    /**
     *
     * @var PlanejamentoAnoFolhaRepository
     */
    private $planajemantoAnoFolhaRepository;
    
    /**
     * 
     * @param PlanejamentoAnoFolhaRepository $planajemantoAnoFolhaRepository
     */
    public function __construct(PlanejamentoAnoFolhaRepository $planajemantoAnoFolhaRepository)
    {
        $this->planajemantoAnoFolhaRepository = $planajemantoAnoFolhaRepository;
    }

    /**
     * 
     * @param InativarPlanejamentoAberturaFolhaPagamentoCommand $command
     */
    public function handle(InativarPlanejamentoAberturaFolhaPagamentoCommand $command)
    {
        $planejamentoAnoFolha = $command->getPlanejamentoAnoFolha();
        
        if (!$planejamentoAnoFolha->isNuAnoFuturo() || $planejamentoAnoFolha->isInativo()) {
            throw new \Exception();
        }        
        
        $planejamentoAnoFolha->inativar();
        
        $this->planajemantoAnoFolhaRepository->add($planejamentoAnoFolha);
    }
}
