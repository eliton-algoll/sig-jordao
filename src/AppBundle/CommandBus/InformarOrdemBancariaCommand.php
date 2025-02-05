<?php

namespace AppBundle\CommandBus;

use Symfony\Component\Validator\Constraints as Assert;

class InformarOrdemBancariaCommand extends FolhaPagamentoCommand
{
    /**
     * @Assert\NotBlank()
     */
    private $folhaPagamento;
    
    /**
     * @Assert\NotBlank()
     * @Assert\Length(min = 12, max = 12)
     */
    private $nuOrdemBancaria;

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

    /**
     * @return string
     */
    public function getNuOrdemBancaria()
    {
        return $this->nuOrdemBancaria;
    }

    /**
     * @param string
     * @return \AppBundle\CommandBus\FecharFolhaPagamentoCommand
     */
    public function setNuOrdemBancaria($nuSipar)
    {
        $this->nuOrdemBancaria = $nuSipar;
        return $this;
    }
}