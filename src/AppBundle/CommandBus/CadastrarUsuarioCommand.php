<?php

namespace AppBundle\CommandBus;

use AppBundle\Entity\ProjetoPessoa;
use AppBundle\Validator\Constraints as SigpetConstraints;
use Symfony\Component\Validator\Constraints as Assert;

class CadastrarUsuarioCommand
{
    /**
     *
     * @var string 
     * @SigpetConstraints\Cpfcnpj(
     *   aceitar="cpf",
     *   aceitar_formatado=false
     * )
     */
    private $nuCpf;

    /**
     * @var ProjetoPessoa
     */
    private $projetoPessoa;
    
    /**
     * @return string
     */
    public function getNuCpf()
    {
        return $this->nuCpf;
    }

    /**
     * @param string $nuCpf
     * @return CadastrarUsuarioCommand
     */
    public function setNuCpf($nuCpf)
    {
        $this->nuCpf = $nuCpf;
        return $this;
    }

    /**
     * @return ProjetoPessoa
     */
    public function getProjetoPessoa()
    {
        return $this->projetoPessoa;
    }

    /**
     * @param ProjetoPessoa $projetoPessoa
     */
    public function setProjetoPessoa(ProjetoPessoa $projetoPessoa)
    {
        $this->projetoPessoa = $projetoPessoa;
    }
}