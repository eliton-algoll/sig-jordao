<?php

namespace App\CommandBus;

use App\Entity\FormularioAvaliacaoAtividade;

final class InativarFormularioAvaliacaoAtividadesCommand
{
    /**
     *
     * @var FormularioAvaliacaoAtividade 
     */
    private $formularioAvaliacaoAtividade;
    
    /**
     *
     * @var boolean
     */
    private $softDelete;
    
    /**
     * 
     * @param FormularioAvaliacaoAtividade $formularioAvaliacaoAtividade
     * @param boolean $softDelete
     */
    public function __construct(FormularioAvaliacaoAtividade $formularioAvaliacaoAtividade, $softDelete = true)
    {
        $this->formularioAvaliacaoAtividade = $formularioAvaliacaoAtividade;
        $this->softDelete = $softDelete;
    }

    /**
     * 
     * @return FormularioAvaliacaoAtividade
     */
    public function getFormularioAvaliacaoAtividade()
    {
        return $this->formularioAvaliacaoAtividade;
    }

    /**
     * 
     * @return boolean
     */
    public function isSoftDelete()
    {
        return $this->softDelete;
    }
}
