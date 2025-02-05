<?php

namespace AppBundle\CommandBus;

use Symfony\Component\Validator\Constraints as Assert;

class EnviarFolhaPagamentoParaFundoCommand extends FolhaPagamentoCommand
{
    /**
     * @Assert\NotBlank()
     */
    private $folhaPagamento;
    
    /**
     * @Assert\NotBlank()
     * @Assert\Length(min = 20, max = 20)
     */
    private $nuSipar;

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
    public function getNuSipar()
    {
        return $this->nuSipar;
    }

    /**
     * @param string
     * @return \AppBundle\CommandBus\FecharFolhaPagamentoCommand
     */
    public function setNuSipar($nuSipar)
    {
        $this->nuSipar = $nuSipar;
        return $this;
    }
}
