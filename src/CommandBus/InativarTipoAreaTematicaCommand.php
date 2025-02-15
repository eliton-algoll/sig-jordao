<?php

namespace App\CommandBus;

use App\Entity\TipoAreaTematica;
use App\Entity\Usuario;
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
