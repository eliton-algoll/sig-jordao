<?php

namespace AppBundle\Cpb;

use AppBundle\Cpb\RetornoPagamento\Trailer;
use AppBundle\Cpb\RetornoPagamento\Header;
use AppBundle\Cpb\RetornoPagamento\Detalhe;

final class ArquivoRetornoPagamento extends ArquivoRetornoAbstract
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
    public function getTotalPago()
    {
        return count(array_filter($this->getDetalhes(), function (Detalhe $detalhe) {
            return $detalhe->isPagamentoEfetuado();
        }));
    }
    
    /**
     * 
     * @return integer
     */
    public function getTotalNaoPago()
    {
        return $this->getTotal() - $this->getTotalPago();
    }
    
    /**
     * 
     * @return integer
     */
    public function getTotal()
    {
        return count($this->getDetalhes());
    }
}
