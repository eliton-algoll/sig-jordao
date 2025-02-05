<?php

namespace AppBundle\CommandBus;

use AppBundle\Entity\AutorizaCadastroParticipante;

final class InativarAberturaSistemaCadastroParticipanteCommand
{
    /**
     *
     * @var AutorizaCadastroParticipante 
     */
    private $autorizaCadastroParticipante;
    
    /**
     * 
     * @param AutorizaCadastroParticipante $autorizaCadastroParticipante
     */
    public function __construct(AutorizaCadastroParticipante $autorizaCadastroParticipante)
    {
        $this->autorizaCadastroParticipante = $autorizaCadastroParticipante;
    }

    /**
     * 
     * @return AutorizaCadastroParticipante
     */
    public function getAutorizaCadastroParticipante()
    {
        return $this->autorizaCadastroParticipante;
    }
}
