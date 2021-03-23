<?php

namespace AppBundle\CommandBus;

use Symfony\Component\Validator\Constraints as Assert;

class HomologarFolhaPagamentoCommand extends FolhaPagamentoCommand
{
    /**
     * @Assert\NotBlank()
     */
    private $coFolhaPagamento;
    
    /**
     * @Assert\NotBlank()
     */
    private $folhasProjetos;
    
    /**
     * @return integer
     */
    public function getCoFolhaPagamento()
    {
        return $this->coFolhaPagamento;
    }

    /**
     * @param integer $coFolhaPagamento
     * @return HomologarFolhaPagamentoCommand
     */
    public function setCoFolhaPagamento($coFolhaPagamento)
    {
        $this->coFolhaPagamento = $coFolhaPagamento;
        return $this;
    }
            
    /**
     * @return array
     */
    public function getFolhasProjetos()
    {
        return $this->folhasProjetos;
    }

    /**
     * @param array $folhasProjetos
     * @return HomologarFolhaPagamentoCommand
     */
    public function setFolhasProjetos($folhasProjetos)
    {
        $this->folhasProjetos = $folhasProjetos;
        return $this;
    }
}