<?php

namespace App\CommandBus;

use Symfony\Component\Validator\Constraints as Assert;

class FecharFolhaPagamentoCommand extends FolhaPagamentoCommand
{
    /**
     * @Assert\NotBlank()
     */
    private $folhaPagamento;

    /**
     * @return \App\Entity\FolhaPagamento
     */
    public function getFolhaPagamento()
    {
        return $this->folhaPagamento;
    }

    /**
     * @param \App\Entity\FolhaPagamento $folhaPagamento
     * @return \App\CommandBus\FecharFolhaPagamentoCommand
     */
    public function setFolhaPagamento($folhaPagamento)
    {
        $this->folhaPagamento = $folhaPagamento;
        return $this;
    }
}