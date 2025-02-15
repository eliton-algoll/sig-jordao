<?php

namespace App\CommandBus;

use App\Entity\CampusInstituicao;
use App\Entity\Instituicao;
use App\Entity\Projeto;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\Validator\Constraints as Assert;
use App\Entity\Publicacao;
use App\Entity\PessoaJuridica;

class CadastrarCampusInstituicaoCommand
{

    /**
     * @var CampusInstituicao
     */
    private $campusInstituicao;

    /**
     * @var string
     * @Assert\NotBlank()
     * @Assert\Length(min = 0, max = 4000)
     */
    private $noCampus;

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
     * @var string
     * @Assert\NotBlank()
     */
    private $instituicao;


    /**
     *
     * @return CampusInstituicao
     */
    public function getCampusInstituicao()
    {
        return $this->campusInstituicao;
    }

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
    public function getNoCampus()
    {
        return $this->noCampus;
    }

    /**
     * @param string $noCampus
     * @return CadastrarCampusInstituicaoCommand
     */
    public function setNoCampus($noCampus)
    {
        $this->noCampus = $noCampus;
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
     * @return CadastrarCampusInstituicaoCommand
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
     * @return CadastrarCampusInstituicaoCommand
     */
    public function setUf($uf)
    {
        $this->uf = $uf;
        return $this;
    }

    /**
     * @param string $instituicao
     * @return CadastrarCampusInstituicaoCommand
     */
    public function setInstituicao($instituicao)
    {
        $this->instituicao = $instituicao;
        return $this;
    }

    /**
    * @param CampusInstituicao $campusInstituicao
    */
    public function setValuesByEntity(CampusInstituicao $campusInstituicao)
    {
        $this->uf                   = $campusInstituicao->getInstituicao()->getMunicipio()->getCoUfIbge();
        $this->municipio            = $campusInstituicao->getInstituicao()->getMunicipio()->getCoMunicipioIbge();
        $this->instituicao          = $campusInstituicao->getInstituicao()->getCoSeqInstituicao();
        $this->noCampus             = $campusInstituicao->getNoCampus();
        $this->campusInstituicao    = $campusInstituicao;
    }

}
