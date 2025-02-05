<?php

namespace AppBundle\CommandBus;

use AppBundle\Entity\Usuario;
use AppBundle\Validator\Constraints as SigpetConstraints;
use Symfony\Component\Validator\Constraints as Assert;

class CadastrarAdministradorCommand
{
    /**
     * @var string
     *
     * @Assert\NotBlank()
     */
    private $nuCpf;

    /**
     * @var string
     *
     * @Assert\NotBlank()
     */
    private $dsLogin;

    /**
     * @var string
     *
     * @Assert\NotBlank()
     */
    private $email;

    
    /**
     * @return string
     */
    public function getNuCpf()
    {
        return $this->nuCpf;
    }

    /**
     * @return string
     */
    public function getDsLogin()
    {
        return $this->dsLogin;
    }

    /**
     * @return string
     */
    public function getEmail()
    {
        return $this->email;
    }


    /**
     * @return string
     */
    public function getStRegistroAtivo()
    {
        return $this->stRegistroAtivo;
    }

    /**
     * @param string $stRegistroAtivo
     * @return CadastrarAdministradorCommand
     */
    public function setStRegistroAtivo($stRegistroAtivo)
    {
        $this->stRegistroAtivo = $stRegistroAtivo;
        return $this;
    }



    /**
     * @param string $nuCpf
     * @return CadastrarAdministradorCommand
     */
    public function setNuCpf($nuCpf)
    {
        $this->nuCpf = $nuCpf;
        return $this;
    }

    /**
     * @param string $dsLogin
     * @return CadastrarAdministradorCommand
     */
    public function setDsLogin($dsLogin)
    {
        $this->dsLogin = $dsLogin;
        return $this;
    }

    /**
     * @param string $email
     * @return CadastrarAdministradorCommand
     */
    public function setEmail($email)
    {
        $this->email = $email;
        return $this;
    }




}