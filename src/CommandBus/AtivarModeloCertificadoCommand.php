<?php

namespace App\CommandBus;

use App\Entity\ModeloCertificado;
use Symfony\Component\Validator\Constraints as Assert;

class AtivarModeloCertificadoCommand
{

    /**
     * @var $modeloCertificado
     *
     * @Assert\NotBlank
     */
    protected $modeloCertificado;

    /**
     * AtivarModeloCertificadoCommand constructor.
     * @param ModeloCertificado $modeloCertificado
     */
    public function __construct(ModeloCertificado $modeloCertificado)
    {
        $this->modeloCertificado = $modeloCertificado;
    }

    /**
     * @return mixed
     */
    public function getModeloCertificado()
    {
        return $this->modeloCertificado;
    }

    /**
     * @param mixed $modeloCertificado
     */
    public function setModeloCertificado($modeloCertificado)
    {
        $this->modeloCertificado = $modeloCertificado;
    }

}