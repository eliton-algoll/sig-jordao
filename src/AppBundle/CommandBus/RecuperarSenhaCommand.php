<?php

namespace AppBundle\CommandBus;

use AppBundle\Validator\Constraints as SigpetConstraints;
use Symfony\Component\Validator\Constraints as Assert;

class RecuperarSenhaCommand
{
    /**
     *
     * @var string 
     * @SigpetConstraints\Cpfcnpj(
     *   aceitar="cpf",
     *   aceitar_formatado=false
     * )
     */
    private $cpf;
    
    /**
     *
     * @var string
     * 
     * @Assert\Email(
     *    message = "Email inválido."
     * )
     */
    private $email;
    
    /**
     * 
     * @return string
     */
    function getCpf()
    {
        return $this->cpf;
    }

    /**
     * 
     * @return string
     */
    function getEmail()
    {
        return $this->email;
    }

    /**
     * 
     * @param string $cpf
     */
    function setCpf($cpf)
    {
        $this->cpf = $cpf;
    }

    /**
     * 
     * @param string $email
     */
    function setEmail($email)
    {
        $this->email = $email;
    }
}
