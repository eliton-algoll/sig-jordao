<?php

namespace AppBundle\CommandBus;

use AppBundle\Entity\ValorBolsaPrograma;

final class InativarValorBolsaCommand
{
    /**
     *
     * @var ValorBolsaPrograma 
     */
    private $valorBolsaPrograma;
    
    /**
     * 
     * @param ValorBolsaPrograma $valorBolsaPrograma
     */
    public function __construct(ValorBolsaPrograma $valorBolsaPrograma)
    {
        $this->valorBolsaPrograma = $valorBolsaPrograma;
    }

    /**
     * 
     * @return ValorBolsaPrograma
     */
    public function getValorBolsaPrograma()
    {
        return $this->valorBolsaPrograma;
    }
}
