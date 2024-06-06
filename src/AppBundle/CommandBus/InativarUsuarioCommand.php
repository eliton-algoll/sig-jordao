<?php

namespace AppBundle\CommandBus;

use AppBundle\Entity\Usuario;
use Symfony\Component\Validator\Constraints as Assert;

final class InativarUsuarioCommand
{
    /**
     *
     * @var Usuario
     */
    private $usuario;

    /**
     *
     * @param Usuario $usuario
     */
    public function __construct(Usuario $usuario)
    {
        $this->usuario = $usuario;
    }

    /**
     *
     * @return Usuario
     */
    public function getUsuario()
    {
        return $this->usuario;
    }

}
