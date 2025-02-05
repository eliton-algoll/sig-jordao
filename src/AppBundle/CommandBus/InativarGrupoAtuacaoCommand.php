<?php

namespace AppBundle\CommandBus;

use Symfony\Component\Validator\Constraints as Assert;

class InativarGrupoAtuacaoCommand
{
    /**
     * @var integer
     * @Assert\NotBlank()
     */
    protected $coSeqGrupoAtuacao;
    
    /**
     * @return integer
     */
    public function getCoSeqGrupoAtuacao()
    {
        return $this->coSeqGrupoAtuacao;
    }

    /*
     * @param integer $coSeqGrupoAtuacao
     * @return InativarGrupoAtuacaoCommand
     */
    public function setCoSeqGrupoAtuacao($coSeqGrupoAtuacao)
    {
        $this->coSeqGrupoAtuacao = $coSeqGrupoAtuacao;
        return $this;
    }
}