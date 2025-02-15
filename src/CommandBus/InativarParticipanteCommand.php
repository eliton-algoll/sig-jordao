<?php

namespace App\CommandBus;

use Symfony\Component\Validator\Constraints as Assert;

class InativarParticipanteCommand
{

    /**
     * @var integer
     * @Assert\NotBlank
     */
    private $projetoPessoa;
    
    /**
     * @return projetoPessoa
     */
    public function getProjetoPessoa()
    {
        return $this->projetoPessoa;
    }

    /**
     * @param ProjetoPessoa $projetoPessoa
     * @return \App\CommandBus\InativarParticipanteCommand
     */
    public function setProjetoPessoa($projetoPessoa)
    {
        $this->projetoPessoa = $projetoPessoa;
        return $this;
    }

}
