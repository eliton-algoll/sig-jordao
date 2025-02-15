<?php

namespace App\Exception;

use App\Entity\AutorizaCadastroParticipante;

final class AutorizacaoCadastroParticipanteVigenteExistsException extends \Exception
{
    /**
     * 
     * @param AutorizaCadastroParticipante $autorizaCadastroParticipante
     */
    public function __construct(AutorizaCadastroParticipante $autorizaCadastroParticipante)
    {
        $message = 'Para o programa / publicação e projeto selecionados, existe um período de abertura cadastrado %s a %s. Não é permitido uma nova abertura. Você poderá editar o período existente.';
        
        parent::__construct(sprintf($message,
            $autorizaCadastroParticipante->getDtInicioPeriodo()->format('d/m/Y'),
            $autorizaCadastroParticipante->getDtFimPeriodo()->format('d/m/Y'))
        );
    }
}
