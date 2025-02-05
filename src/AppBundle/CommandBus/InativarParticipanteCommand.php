<?php

namespace AppBundle\CommandBus;

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
     * @return \AppBundle\CommandBus\InativarParticipanteCommand
     */
    public function setProjetoPessoa($projetoPessoa)
    {
        $this->projetoPessoa = $projetoPessoa;
        return $this;
    }

}
