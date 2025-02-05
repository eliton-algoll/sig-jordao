<?php

namespace AppBundle\CommandBus;

use AppBundle\CommandBus\InativarAberturaSistemaCadastroParticipanteCommand;
use AppBundle\Repository\AutorizaCadastroParticipanteRepository;

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
