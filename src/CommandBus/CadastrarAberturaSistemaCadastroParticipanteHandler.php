<?php

namespace App\CommandBus;

use App\CommandBus\CadastrarAberturaSistemaCadastroParticipanteCommand;
use App\Entity\AutorizaCadastroParticipante;
use App\Repository\AutorizaCadastroParticipanteRepository;
use App\Repository\FolhaPagamentoRepository;

final class CadastrarAberturaSistemaCadastroParticipanteHandler
{
    /**
     *
     * @var AutorizaCadastroParticipanteRepository 
     */
    private $autorizaCadastroParticipanteRepository;
    
    /**
     *
     * @var FolhaPagamentoRepository 
     */
    private $folhaPagamentoRepository;
    
    /**
     * 
     * @param AutorizaCadastroParticipanteRepository $autorizaCadastroParticipanteRepository
     * @param FolhaPagamentoRepository $folhaPagamentoRepository
     */
    public function __construct(
        AutorizaCadastroParticipanteRepository $autorizaCadastroParticipanteRepository,
        FolhaPagamentoRepository $folhaPagamentoRepository
    ) {
        $this->autorizaCadastroParticipanteRepository = $autorizaCadastroParticipanteRepository;
        $this->folhaPagamentoRepository = $folhaPagamentoRepository;
    }

    /**
     * 
     * @param CadastrarAberturaSistemaCadastroParticipanteCommand $command
     */
    public function handle(CadastrarAberturaSistemaCadastroParticipanteCommand $command)
    {
        $autorizacaoCadastroParticipante = $command->getAutorizaCadastroParticipante();
        
        $this->autorizaCadastroParticipanteRepository
            ->checkExistisAutorizacaoVigenteOuFutura($command->getProjeto(), $autorizacaoCadastroParticipante);
        
        $folhaPagamento = $this->folhaPagamentoRepository->getFolhaAbertaByPublicacao($command->getPublicacao());
        
        if ($autorizacaoCadastroParticipante) {
            $autorizacaoCadastroParticipante->atualizaPeriodo($command->getDateTimeDtInicioPeriodo(), $command->getDateTimeDtFimPeriodo());
        } else {        
            $autorizacaoCadastroParticipante = new AutorizaCadastroParticipante(
                $command->getProjeto(),
                $folhaPagamento,
                $command->getDateTimeDtInicioPeriodo(),
                $command->getDateTimeDtFimPeriodo()
            );
        }
        
        $this->autorizaCadastroParticipanteRepository->add($autorizacaoCadastroParticipante);
    }
}
