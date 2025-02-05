<?php

namespace AppBundle\CommandBus;

use Symfony\Component\Validator\Constraints as Assert;

class FolhaPagamentoCommand
{
    /**
     * @var integer
     * @Assert\NotBlank()
     */
    protected $pessoaPerfilAtor;
    
    /**
     * @return integer
     */
    public function getPessoaPerfilAtor()
    {
        return $this->pessoaPerfilAtor;
    }
    
    /**
     * @param integer $pessoaPerfilAtor
     * @return FolhaPagamentoCommand
     */
    public function setPessoaPerfilAtor($pessoaPerfilAtor)
    {
        $this->pessoaPerfilAtor = $pessoaPerfilAtor;
        return $this;
    }
}
