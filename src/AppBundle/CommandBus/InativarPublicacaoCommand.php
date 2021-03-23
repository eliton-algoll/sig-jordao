<?php

namespace AppBundle\CommandBus;

use Symfony\Component\Validator\Constraints as Assert;

class InativarPublicacaoCommand
{
    /**
     * @var integer
     * @Assert\NotBlank()
     */
    protected $coSeqPublicacao;
    
    /**
     * @return integer
     */
    public function getCoSeqPublicacao()
    {
        return $this->coSeqPublicacao;
    }

    /**
     * @param integer $coSeqPublicacao
     * @return InativarPublicacaoCommand
     */
    public function setCoSeqPublicacao($coSeqPublicacao)
    {
        $this->coSeqPublicacao = $coSeqPublicacao;
        return $this;
    }
}