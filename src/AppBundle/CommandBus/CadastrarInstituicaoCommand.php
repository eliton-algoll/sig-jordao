<?php

namespace AppBundle\CommandBus;

use AppBundle\Entity\Instituicao;
use AppBundle\Entity\Projeto;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\Validator\Constraints as Assert;
use AppBundle\Entity\Publicacao;
use AppBundle\Entity\PessoaJuridica;

class CadastrarInstituicaoCommand
{

    /**
     * @var Instituicao
     */
    private $instituicao;

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
    private $uf;

    /**
     * @var string
     * @Assert\NotBlank()
     */
    private $municipio;


    /**
     *
     * @return Instituicao
     */
    public function getInstituicao()
    {
        return $this->instituicao;
    }

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

    /**
     * @return string
     */
    public function getUf()
    {
        return $this->uf;
    }

    /**
     * @param string $uf
     * @return CadastrarInstituicaoCommand
     */
    public function setUf($uf)
    {
        $this->uf = $uf;
        return $this;
    }

    /**
    * @param Instituicao $instituicao
    */
    public function setValuesByEntity(Instituicao $instituicao)
    {
        $this->uf                   = $instituicao->getMunicipio()->getCoUfIbge();
        $this->municipio            = $instituicao->getMunicipio()->getCoMunicipioIbge();
        $this->nuCnpj               = $instituicao->getPessoaJuridica()->getNuCnpj();
        $this->noInstituicaoProjeto = $instituicao->getNoInstituicaoProjeto();
        $this->instituicao          = $instituicao;
    }

}
