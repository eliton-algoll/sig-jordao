<?php

namespace AppBundle\CommandBus;

use AppBundle\Entity\TipoAreaTematica;
use AppBundle\Entity\Usuario;
use Symfony\Component\Validator\Constraints as Assert;

final class InativarTipoAreaTematicaCommand
{
    /**
     *
     * @var TipoAreaTematica
     */
    private $tipoAreaTematica;

    /**
     *
     * @param TipoAreaTematica $tipoAreaTematica
     */
    public function __construct(TipoAreaTematica $tipoAreaTematica)
    {
        $this->tipoAreaTematica = $tipoAreaTematica;
    }

    /**
     *
     * @return TipoAreaTematica
     */
    public function getTipoAreaTematica()
    {
        return $this->tipoAreaTematica;
    }

}
