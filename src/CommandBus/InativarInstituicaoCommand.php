<?php

namespace App\CommandBus;

use App\Entity\Instituicao;
use Symfony\Component\Validator\Constraints as Assert;

final class InativarInstituicaoCommand
{
    /**
     *
     * @var Instituicao
     */
    private $instituicao;

    /**
     *
     * @param Instituicao $instituicao
     */
    public function __construct(Instituicao $instituicao)
    {
        $this->instituicao = $instituicao;
    }

    /**
     *
     * @return Instituicao
     */
    public function getInstituicao()
    {
        return $this->instituicao;
    }

}
