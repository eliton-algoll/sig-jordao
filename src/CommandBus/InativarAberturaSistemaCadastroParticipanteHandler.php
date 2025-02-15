<?php

namespace App\CommandBus;

use App\CommandBus\InativarAberturaSistemaCadastroParticipanteCommand;
use App\Repository\AutorizaCadastroParticipanteRepository;

final class InativarAberturaSistemaCadastroParticipanteHandler
{
    /**
     *
     * @var AutorizaCadastroParticipanteRepository 
     */
    private $autorizaCadastroParticipanteRepository;
    
    /**
     * 
     * @param AutorizaCadastroParticipanteRepository $autorizaCadastroParticipanteRepository
     */
    public function __construct(AutorizaCadastroParticipanteRepository $autorizaCadastroParticipanteRepository)
    {
        $this->autorizaCadastroParticipanteRepository = $autorizaCadastroParticipanteRepository;
    }

    /**
     * 
     * @param InativarAberturaSistemaCadastroParticipanteCommand $command
     */
    public function handle(InativarAberturaSistemaCadastroParticipanteCommand $command)
    {
        $autorizaCadastroParticiapnte = $command->getAutorizaCadastroParticipante();
        
        if ($autorizaCadastroParticiapnte->getDtInicioPeriodo() <= new \DateTime()) {
            throw new \Exception();
        }        
        
        $autorizaCadastroParticiapnte->inativar();

        $this->autorizaCadastroParticipanteRepository->add($autorizaCadastroParticiapnte);
    }
}
