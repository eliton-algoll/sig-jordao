<?php

namespace AppBundle\CommandBus;

use AppBundle\Repository\SituacaoFolhaRepository;
use AppBundle\Repository\FolhaPagamentoRepository;
use AppBundle\Entity\SituacaoFolha;

final class CancelarFolhaSuplementarHandler
{
    /**
     *
     * @var FolhaPagamentoRepository 
     */
    private $folhaPagamentoRepository;
    
    /**
     *
     * @var SituacaoFolhaRepository 
     */
    private $situacaoFolhaRepository;
    
    /**
     * 
     * @param FolhaPagamentoRepository $folhaPagamentoRepository
     * @param SituacaoFolhaRepository $situacaoFolhaRepository
     */
    public function __construct(
        FolhaPagamentoRepository $folhaPagamentoRepository,
        SituacaoFolhaRepository $situacaoFolhaRepository
    ) {
        $this->folhaPagamentoRepository = $folhaPagamentoRepository;
        $this->situacaoFolhaRepository = $situacaoFolhaRepository;
    }

    /**
     * 
     * @param CancelarFolhaSuplementarCommand $command
     */
    public function handle(CancelarFolhaSuplementarCommand $command)
    {
        if ($command->getFolhaPagamento()->isReadyToCancel()) {
            $situacaoFolha = $this->situacaoFolhaRepository->find(SituacaoFolha::CANCELADA);
            
            $folhaPagamento = $command->getFolhaPagamento();
            $folhaPagamento->setSituacao($situacaoFolha);
            $folhaPagamento->setDsJustificativaCancelamento($command->getDsJustificativa());
            
            $this->folhaPagamentoRepository->add($folhaPagamento);
        }
    }
}
