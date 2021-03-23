<?php

namespace AppBundle\CommandBus;

use AppBundle\Repository\AutorizacaoFolhaRepository;
use AppBundle\Exception\FolhaSuplementarException;

final class InativarAutorizacoesFolhaSuplementarHandler
{
    /**
     *
     * @var AutorizacaoFolhaRepository 
     */
    private $autorizacaoFolhaRepository;
    
    /**
     * 
     * @param AutorizacaoFolhaRepository $autorizacaoFolhaRepository
     */
    public function __construct(AutorizacaoFolhaRepository $autorizacaoFolhaRepository)
    {
        $this->autorizacaoFolhaRepository = $autorizacaoFolhaRepository;
    }

    /**
     * 
     * @param InativarAutorizacoesFolhaSuplementarCommand $command
     */
    public function handle(InativarAutorizacoesFolhaSuplementarCommand $command)
    {
        $this->checkFolhaIsGonnaBeEmpty($command);
        
        foreach ($command->getAutorizacoes() as $autorizacaoId) {
            $autorizacaoFolha = $this->autorizacaoFolhaRepository->find($autorizacaoId);
            
            if ($autorizacaoFolha->getProjetoFolhaPagamento()->getFolhaPagamento() != $command->getFolhaPagamento()) {
                continue;
            }
            
            $autorizacaoFolha->inativar();
            $this->autorizacaoFolhaRepository->add($autorizacaoFolha);
        }
    }
    
    /**
     * 
     * @param InativarAutorizacoesFolhaSuplementarCommand $command
     * @throws FolhaSuplementarException
     */
    private function checkFolhaIsGonnaBeEmpty(InativarAutorizacoesFolhaSuplementarCommand $command)
    {
        $autorizacoesAtivas = $this->autorizacaoFolhaRepository->findByFolha($command->getFolhaPagamento());        
        
        if (count($autorizacoesAtivas) <= $command->getTotalAutorizacoes()) {
            
            $showError = true;
            
            foreach ($autorizacoesAtivas as $autorizacao) {
                if (!in_array($autorizacao->getCoSeqAutorizacaoFolha(), $command->getAutorizacoes())) {
                    $showError = false;
                }
            }
            
            if (true === $showError) {
                throw FolhaSuplementarException::onRemoveAllParticipantes();
            }
        }        
    }
}
