<?php

namespace AppBundle\CommandBus;

use Symfony\Component\Validator\Constraints as Assert;

class CadastrarGrupoAtuacaoCommand
{
    /**
     * @var array
     * @Assert\NotBlank()
     */
    private $areasTematicas;

    /**
     * @return array
     */
    public function getAreasTematicas()
    {
        return $this->areasTematicas;
    }

    /**
     * @param array $areasTematicas
     * @return CadastrarGrupoCommand
     */
    public function setAreasTematicas($areasTematicas)
    {
        $this->areasTematicas = $areasTematicas;
        return $this;
    }
}