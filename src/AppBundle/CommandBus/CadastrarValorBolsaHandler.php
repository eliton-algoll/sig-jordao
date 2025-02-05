<?php

namespace AppBundle\CommandBus;

use AppBundle\CommandBus\CadastrarValorBolsaCommand;
use AppBundle\Entity\ValorBolsaPrograma;
use AppBundle\Repository\FolhaPagamentoRepository;
use AppBundle\Repository\ValorBolsaProgramaRepository;

final class CadastrarValorBolsaHandler
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
     * @param CadastrarValorBolsaCommand $command
     */
    public function handle(CadastrarValorBolsaCommand $command)
    {
        $this->folhaPagamentoRepository
            ->checkNuMesNuAnoHasInFolha(
                $command->getNuMesInicioVigencia(),
                $command->getNuAnoInicioVigencia());
        
        $this->valorBolsaProgramaRepository
            ->checkExists(
                $command->getPublicacao(),
                $command->getTipoParticipante(),
                $command->getNuMesInicioVigencia(),
                $command->getNuAnoInicioVigencia(),
                $command->getValorBolsaPrograma());
        
        if ($command->getValorBolsaPrograma()) {
            $valorBolsaPrograma = $command->getValorBolsaPrograma();
            $valorBolsaPrograma->setPublicacao($command->getPublicacao());
            $valorBolsaPrograma->setPerfil($command->getTipoParticipante());
            $valorBolsaPrograma->setVlBolsa($command->getFloatVlBolsa());
            $valorBolsaPrograma->setStPeriodoVigencia($command->isVigente());
            $valorBolsaPrograma->setNuAnoVigencia($command->getNuAnoInicioVigencia());
            $valorBolsaPrograma->setNuMesVigencia($command->getNuMesInicioVigencia());
        } else {        
            $valorBolsaPrograma = new ValorBolsaPrograma(
                $command->getPublicacao(),
                $command->getTipoParticipante(),
                $command->getFloatVlBolsa(),
                $command->getNuMesInicioVigencia(),
                $command->getNuAnoInicioVigencia(),
                $command->isVigente()
            );
        }
        
        $this->valorBolsaProgramaRepository->add($valorBolsaPrograma);
        $this->inativaValorBolsaProgramaAnteriores($command);
    }
    
    /**
     * 
     * @param CadastrarValorBolsaCommand $command
     */
    private function inativaValorBolsaProgramaAnteriores(CadastrarValorBolsaCommand $command)
    {
        $valoresBolsaPrograma = $this->valorBolsaProgramaRepository
            ->findByPublicacaoAndPerfil(
                $command->getPublicacao(),
                $command->getTipoParticipante()
            );
        
        foreach ($valoresBolsaPrograma as $valorBolsaPrograma) {
            if ($command->isVigente() && $valorBolsaPrograma->isInicioVigenciaMenorDtAtual()) {
                $valorBolsaPrograma->descontinuar();
                $this->valorBolsaProgramaRepository->add($valorBolsaPrograma);
            }
        }
    }
}
