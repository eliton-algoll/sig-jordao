<?php

namespace AppBundle\CommandBus;

use AppBundle\Entity\FolhaPagamento;

final class FinalizaFolhaPagamentoCommand
{
    /**
     *
     * @var FolhaPagamento 
     */
    private $folhaPagamento;
    
    /**
     * 
     * @param FolhaPagamento $folhaPagamento
     */
    public function __construct(FolhaPagamento $folhaPagamento)
    {
        $this->folhaPagamento = $folhaPagamento;
    }

    /**
     * 
     * @return FolhaPagamento
     */
    public function getFolhaPagamento()
    {
        return $this->folhaPagamento;
    }
}
