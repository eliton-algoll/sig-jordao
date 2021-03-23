<?php

namespace AppBundle\CommandBus;

use AppBundle\Entity\FolhaPagamento;

final class InativarAutorizacoesFolhaSuplementarCommand
{
    /**
     *
     * @var FolhaPagamento 
     */
    private $folhaPagamento;
    
    /**
     *
     * @var array
     */
    private $autorizacoes;
    
    /**
     * 
     * @param FolhaPagamento $folhaPagamento
     * @param array $autorizacoes
     */
    public function __construct(FolhaPagamento $folhaPagamento, array $autorizacoes)
    {
        $this->folhaPagamento = $folhaPagamento;
        $this->autorizacoes = $autorizacoes;
    }
    
    /**
     * 
     * @return FolhaPagamento
     */
    public function getFolhaPagamento()
    {
        return $this->folhaPagamento;
    }

    /**
     * 
     * @return array
     */
    public function getAutorizacoes()
    {
        return $this->autorizacoes;
    }
    
    /**
     * 
     * @return integer
     */
    public function getTotalAutorizacoes()
    {
        return count($this->autorizacoes);
    }
}
