<?php

namespace AppBundle\CommandBus;

use AppBundle\Entity\EnvioFormularioAvaliacaoAtividade;

final class InativarEnvioFormularioAvaliacaoAtividadeCommand
{
    /**
     *
     * @var EnvioFormularioAvaliacaoAtividade 
     */
    private $envioFormularioAvaliacaoAtividade;
    
    /**
     * 
     * @param EnvioFormularioAvaliacaoAtividade $envioFormularioAvaliacaoAtividade
     */
    public function __construct(EnvioFormularioAvaliacaoAtividade $envioFormularioAvaliacaoAtividade)
    {
        $this->envioFormularioAvaliacaoAtividade = $envioFormularioAvaliacaoAtividade;
    }

    /**
     * 
     * @return EnvioFormularioAvaliacaoAtividade
     */
    public function getEnvioFormularioAvaliacaoAtividade()
    {
        return $this->envioFormularioAvaliacaoAtividade;
    }
}
