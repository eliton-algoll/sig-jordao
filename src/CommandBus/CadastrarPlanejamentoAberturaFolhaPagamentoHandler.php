<?php

namespace App\CommandBus;

use App\CommandBus\CadastrarPlanejamentoAberturaFolhaPagamentoCommand;
use App\Entity\PlanejamentoAnoFolha;
use App\Repository\PlanejamentoAnoFolhaRepository;
use App\Repository\PublicacaoRepository;

final class CadastrarPlanejamentoAberturaFolhaPagamentoHandler
{
    /**
     *
     * @var PlanejamentoAnoFolhaRepository 
     */
    private $planejamentoAnoFolhaRepository;
    
    /**
     *
     * @var PublicacaoRepository
     */
    private $publicacaoRepository;
    
    /**
     * 
     * @param PlanejamentoAnoFolhaRepository $planejamentoAnoFolhaRepository
     * @param PublicacaoRepository $publicacaoRepository
     */
    public function __construct(
        PlanejamentoAnoFolhaRepository $planejamentoAnoFolhaRepository,
        PublicacaoRepository $publicacaoRepository
    ) {
        $this->planejamentoAnoFolhaRepository = $planejamentoAnoFolhaRepository;
        $this->publicacaoRepository = $publicacaoRepository;
    }

    /**
     * 
     * @param CadastrarPlanejamentoAberturaFolhaPagamentoCommand $command
     */
    public function handle(CadastrarPlanejamentoAberturaFolhaPagamentoCommand $command)
    {
        $publicacao = $this->publicacaoRepository->find($command->getCoPublicacao());
        
        if (!$publicacao) {
            throw new \Exception();
        }
        
        $planejamentoAnoFolha = ($command->getPlanejamentoAnoFolha()) ? 
            $command->getPlanejamentoAnoFolha() : new PlanejamentoAnoFolha($publicacao, $command->getNuAnoReferencia());
        
        foreach ($command->getMesesReferencia() as $mes => $data) {
            if (!isset($data['nuDia']) || !$data['nuDia']) continue;
            
            $planejamentoMesFolha = $planejamentoAnoFolha->getPlanejamentoMesFolhaByNuMes($mes);
            if ($planejamentoMesFolha) {
                $planejamentoMesFolha->setNuDiaAbertura($data['nuDia']);
            } else {
                $planejamentoAnoFolha->addPlanejamentoMesFolha($mes, $data['nuDia']);
            }            
        }
        
        $this->planejamentoAnoFolhaRepository->add($planejamentoAnoFolha);
    }
}
