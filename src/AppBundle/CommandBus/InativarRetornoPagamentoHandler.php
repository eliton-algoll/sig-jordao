<?php

namespace AppBundle\CommandBus;

use AppBundle\Repository\RetornoPagamentoRepository;
use AppBundle\Repository\FolhaPagamentoRepository;
use AppBundle\Repository\DetalheArquivoRetornoPagamentoRepository;
use AppBundle\Repository\AutorizacaoFolhaRepository;

final class InativarRetornoPagamentoHandler
{
    /**
     *
     * @var RetornoPagamentoRepository 
     */
    private $retornoPagamentoRepository;
    
    /**
     *
     * @var DetalheArquivoRetornoPagamentoRepository 
     */
    private $detalheArquivoRetornoPagamentoRepository;
    
    /**
     *
     * @var AutorizacaoFolhaRepository 
     */
    private $autorizacaoFolhaRepository;
    
    /**
     *
     * @var FolhaPagamentoRepository 
     */
    private $folhaPagamentoRepository;
    
    /**
     * 
     * @param RetornoPagamentoRepository $retornoPagamentoRepository
     * @param DetalheArquivoRetornoPagamentoRepository $detalheArquivoRetornoPagamentoRepository
     * @param AutorizacaoFolhaRepository $autorizacaoFolhaRepository
     * @param FolhaPagamentoRepository $folhaPagamentoRepository
     */
    public function __construct(
        RetornoPagamentoRepository $retornoPagamentoRepository,
        DetalheArquivoRetornoPagamentoRepository $detalheArquivoRetornoPagamentoRepository,
        AutorizacaoFolhaRepository $autorizacaoFolhaRepository,
        FolhaPagamentoRepository $folhaPagamentoRepository
    ) {
        $this->retornoPagamentoRepository = $retornoPagamentoRepository;
        $this->detalheArquivoRetornoPagamentoRepository = $detalheArquivoRetornoPagamentoRepository;
        $this->autorizacaoFolhaRepository = $autorizacaoFolhaRepository;
        $this->folhaPagamentoRepository = $folhaPagamentoRepository;
    }
    
    /**
     * 
     * @param InativarRetornoPagamentoCommand $command
     */
    public function handle(InativarRetornoPagamentoCommand $command)
    {
        $retornoPagamento = $command->getRetornoPagamento();
        $folhaPagamento = $retornoPagamento->getFolhaPagamento();
        
        if (!$retornoPagamento->isAtivo()) {
            return;
        }
        
        $detalhes = $this->detalheArquivoRetornoPagamentoRepository
            ->findBy(['retornoPagamento' => $retornoPagamento]);
        
        $cancelaRetorno = false;
        
        foreach ($detalhes as $detalhe) {
            $autorizacaoFolha = $detalhe->getAutorizacaoFolha();
            if ($autorizacaoFolha) {
                $autorizacaoFolha->removeDetalheArquivoRetornoPagamento();

                $this->autorizacaoFolhaRepository->add($autorizacaoFolha);
                $cancelaRetorno = true;
            }
        }
        
        $retornoPagamento->inativar();
        
        if (true === $cancelaRetorno) {
            $folhaPagamento->cancelaRetornoPagamento();
        }
        
        $this->retornoPagamentoRepository->add($retornoPagamento);
        $this->folhaPagamentoRepository->add($folhaPagamento);
    }
}
