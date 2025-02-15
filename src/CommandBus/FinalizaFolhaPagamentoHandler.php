<?php

namespace App\CommandBus;

use App\Repository\SituacaoFolhaRepository;
use App\Repository\FolhaPagamentoRepository;
use App\Repository\AutorizacaoFolhaRepository;

final class FinalizaFolhaPagamentoHandler
{
    /**
     *
     * @var FolhaPagamentoRepository 
     */
    private $folhaPagamentoReposiory;
    
    /**
     *
     * @var AutorizacaoFolhaRepository 
     */
    private $autorizacaoFolhaRepository;
    
    /**
     *
     * @var SituacaoFolhaRepository 
     */
    private $situacaoFolhaRepository;
    
    /**
     * 
     * @param FolhaPagamentoRepository $folhaPagamentoReposiory
     * @param AutorizacaoFolhaRepository $autorizacaoFolhaRepository
     * @param SituacaoFolhaRepository $situacaoFolhaRepository
     */
    public function __construct(
        FolhaPagamentoRepository $folhaPagamentoReposiory,
        AutorizacaoFolhaRepository $autorizacaoFolhaRepository,
        SituacaoFolhaRepository $situacaoFolhaRepository
    ) {
        $this->folhaPagamentoReposiory = $folhaPagamentoReposiory;
        $this->autorizacaoFolhaRepository = $autorizacaoFolhaRepository;
        $this->situacaoFolhaRepository = $situacaoFolhaRepository;
    }
    
    /**
     * 
     * @param FinalizaFolhaPagamentoCommand $command
     */
    public function handle(FinalizaFolhaPagamentoCommand $command)
    {
        $folhaPagamento = $command->getFolhaPagamento();
        $naoRetornados = $this->autorizacaoFolhaRepository->findNaoRetornados($folhaPagamento);
        
        if (0 === count($naoRetornados)) {
            $folhaPagamento->retornaPagamento();
            $this->folhaPagamentoReposiory->add($folhaPagamento);
        }
    }
}
