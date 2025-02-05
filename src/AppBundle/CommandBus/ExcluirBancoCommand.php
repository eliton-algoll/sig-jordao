<?php

namespace AppBundle\CommandBus;

use AppBundle\Entity\Banco;
use Symfony\Component\Validator\Constraints as Assert;

final class ExcluirBancoCommand
{
    /**
     *
     * @var Banco
     */
    private $banco;

    /**
     *
     * @param Banco $banco
     */
    public function __construct(Banco $banco)
    {
        $this->banco = $banco;
    }

    /**
     *
     * @return Banco
     */
    public function getBanco()
    {
        return $this->banco;
    }

}
