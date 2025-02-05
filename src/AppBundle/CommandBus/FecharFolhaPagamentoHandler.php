<?php

namespace AppBundle\CommandBus;

use AppBundle\CommandBus\FecharFolhaPagamentoCommand;
use AppBundle\Repository\FolhaPagamentoRepository;
use AppBundle\Repository\SituacaoFolhaRepository;
use AppBundle\Entity\SituacaoFolha;
use AppBundle\Entity\TramitacaoSituacaoFolha;

class FecharFolhaPagamentoHandler
{
    /**
     * @var FolhaPagamentoRepository
     */
    private $folhaPagamentoRepository;
    
    /**
     * @var SituacaoFolhaRepository
     */
    private $situacaoFolhaRepository;
    
    /**
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
     * @param FecharFolhaPagamentoCommand $command
     */
    public function handle(FecharFolhaPagamentoCommand $command)
    {
        $situacaoFechada = $this->situacaoFolhaRepository->find(SituacaoFolha::FECHADA);
        
        $folha = $command->getFolhaPagamento();
        $folha->setSituacao($situacaoFechada);
        
        $tramitacaoSituacaoFolha = new TramitacaoSituacaoFolha($folha, $situacaoFechada, $command->getPessoaPerfilAtor());
        $folha->addTramitacaoSituacao($tramitacaoSituacaoFolha);
        
        $this->folhaPagamentoRepository->add($folha);
    }
}