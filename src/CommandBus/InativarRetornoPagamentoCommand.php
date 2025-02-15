<?php

namespace App\CommandBus;

use App\Entity\RetornoPagamento;

final class InativarRetornoPagamentoCommand
{
    /**
     *
     * @var RetornoPagamento 
     */
    private $retornoPagamento;
    
    /**
     * 
     * @param RetornoPagamento $retornoPagamento
     */
    public function __construct(RetornoPagamento $retornoPagamento)
    {
        $this->retornoPagamento = $retornoPagamento;
    }

    /**
     * 
     * @return RetornoPagamento
     */
    public function getRetornoPagamento()
    {
        return $this->retornoPagamento;
    }
}
