<?php

namespace App\CommandBus;

use App\Entity\TramitacaoFormulario;

final class InativarTramitacaoFormularioCommand
{
    /**
     *
     * @var TramitacaoFormulario
     */
    private $tramitacaoFormulario;
    
    /**
     * 
     * @param TramitacaoFormulario $tramitacaoFormulario
     */
    public function __construct(TramitacaoFormulario $tramitacaoFormulario)
    {
        $this->tramitacaoFormulario = $tramitacaoFormulario;
    }

    /**
     * 
     * @return TramitacaoFormulario
     */
    public function getTramitacaoFormulario()
    {
        return $this->tramitacaoFormulario;
    }
}
