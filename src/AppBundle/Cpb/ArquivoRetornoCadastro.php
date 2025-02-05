<?php

namespace AppBundle\Cpb;

use AppBundle\Cpb\RetornoCadastro\Trailer;
use AppBundle\Cpb\RetornoCadastro\Header;
use AppBundle\Cpb\RetornoCadastro\Detalhe;

final class ArquivoRetornoCadastro extends ArquivoRetornoAbstract
{
    /**
     * 
     * @return string
     */
    protected function getHeaderClass()
    {
        return Header::class;
    }
    
    /**
     * 
     * @return string
     */
    protected function getDetalheClass()
    {
        return Detalhe::class;
    }
    
    /**
     * 
     * @return string
     */
    protected function getTrailerClass()
    {
        return Trailer::class;
    }
    
    /**
     * 
     * @return integer
     */
    public function getTotal()
    {
        return count($this->getDetalhes());
    }
    
    /**
     * 
     * @return integer
     */
    public function getTotalContaCriada()
    {
        return count(array_filter($this->getDetalhes(), function ($detalhe) {
            return $detalhe->isContaCriada();
        }));
    }
    
    /**
     * 
     * @return integer
     */
    public function getTotalContaNaoCriada()
    {
        return $this->getTotal() - $this->getTotalContaCriada();
    }
}
