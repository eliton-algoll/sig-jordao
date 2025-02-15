<?php

namespace App\CommandBus;

use App\CommandBus\InativarValorBolsaCommand;
use App\Repository\FolhaPagamentoRepository;
use App\Repository\ValorBolsaProgramaRepository;

final class InativarValorBolsaHandler
{
    /**
     *
     * @var ValorBolsaProgramaRepository 
     */
    private $valorBolsaProgramaRepository;
    
    /**
     *
     * @var FolhaPagamentoRepository 
     */
    private $folhaPagamentoRepository;
    
    /**
     * 
     * @param ValorBolsaProgramaRepository $valorBolsaProgramaRepository
     * @param FolhaPagamentoRepository $folhaPagamentoRepository
     */
    public function __construct(
        ValorBolsaProgramaRepository $valorBolsaProgramaRepository,
        FolhaPagamentoRepository $folhaPagamentoRepository
    ) {
        $this->valorBolsaProgramaRepository = $valorBolsaProgramaRepository;
        $this->folhaPagamentoRepository = $folhaPagamentoRepository;
    }
    
    /**
     * 
     * @param InativarValorBolsaCommand $command
     */
    public function handle(InativarValorBolsaCommand $command)
    {
        $valorBolsaPrograma = $command->getValorBolsaPrograma();
        
        $this->folhaPagamentoRepository->checkNuMesNuAnoHasInFolha(
            $valorBolsaPrograma->getNuMesVigencia(),
            $valorBolsaPrograma->getNuAnoVigencia());
        
        if ($valorBolsaPrograma->isVigente() && $valorBolsaPrograma->isAtivo()) {
            $valorBolsaProgramaAnterior = $this->valorBolsaProgramaRepository
                ->getLastValorBolsaVigenteByPublicacaoAndPerfil(
                    $valorBolsaPrograma->getPublicacao(), $valorBolsaPrograma->getPerfil());            
            $valorBolsaProgramaAnterior->entrarEmVigencia();
            $valorBolsaPrograma->inativar();
            $this->valorBolsaProgramaRepository->add($valorBolsaProgramaAnterior);
            $this->valorBolsaProgramaRepository->add($valorBolsaPrograma);
        } elseif ($valorBolsaPrograma->isAtivo()) {
            $valorBolsaPrograma->inativar();
            $this->valorBolsaProgramaRepository->add($valorBolsaPrograma);
        }
    }
}
