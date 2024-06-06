<?php

namespace AppBundle\CommandBus;

use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\Validator\Constraints as Assert;
use AppBundle\Entity\Publicacao;
use AppBundle\Entity\PessoaJuridica;

class CadastrarInstituicaoCommand
{
    /**
     * @var string
     * @Assert\NotBlank()
     * @Assert\Length(min = 14, max = 20)
     */
    private $nuCnpj;

    /**
     * @var string
     * @Assert\NotBlank()
     * @Assert\Length(min = 0, max = 4000)
     */
    private $noInstituicaoProjeto;

    /**
     * @var string
     * @Assert\NotBlank()
     */
    private $municipio;

    /**
     * @return string
     */
    public function getNuCnpj()
    {
        return $this->nuCnpj;
    }

    /**
     * @param string $nuCnpj
     * @return CadastrarInstituicaoCommand
     */
    public function setNuCnpj($nuCnpj)
    {
        $this->nuCnpj = $nuCnpj;
        return $this;
    }

    /**
     * @return string
     */
    public function getNoInstituicaoProjeto()
    {
        return $this->noInstituicaoProjeto;
    }

    /**
     * @param string $noInstituicaoProjeto
     * @return CadastrarInstituicaoCommand
     */
    public function setNoInstituicaoProjeto($noInstituicaoProjeto)
    {
        $this->noInstituicaoProjeto = $noInstituicaoProjeto;
        return $this;
    }

    /**
     * @return string
     */
    public function getMunicipio()
    {
        return $this->municipio;
    }

    /**
     * @param string $municipio
     * @return CadastrarInstituicaoCommand
     */
    public function setMunicipio($municipio)
    {
        $this->municipio = $municipio;
        return $this;
    }

}
