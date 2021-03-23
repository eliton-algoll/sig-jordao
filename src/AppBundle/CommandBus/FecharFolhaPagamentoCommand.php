<?php

namespace AppBundle\CommandBus;

use Symfony\Component\Validator\Constraints as Assert;

class FecharFolhaPagamentoCommand extends FolhaPagamentoCommand
{
    /**
     * @Assert\NotBlank()
     */
    private $folhaPagamento;

    /**
     * @return \AppBundle\Entity\FolhaPagamento
     */
    public function getFolhaPagamento()
    {
        return $this->folhaPagamento;
    }

    /**
     * @param \AppBundle\Entity\FolhaPagamento $folhaPagamento
     * @return \AppBundle\CommandBus\FecharFolhaPagamentoCommand
     */
    public function setFolhaPagamento($folhaPagamento)
    {
        $this->folhaPagamento = $folhaPagamento;
        return $this;
    }
}