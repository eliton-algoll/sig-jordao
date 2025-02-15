<?php

namespace App\CommandBus;

use Symfony\Component\Validator\Constraints as Assert;

class AtualizarPublicacaoCommand
{
    /**
     * @var integer
     * @Assert\NotBlank()
     */
    protected $coSeqPublicacao;

    /**
     * @var integer
     * @Assert\NotBlank()
     * @Assert\Type(
     *     type="integer"
     * )
     * @Assert\Range(
     *     min = 1,
     *     max = 999
     * )
     */
    protected $nuPublicacao;

    /**
     * @var \DateTime
     * @Assert\NotBlank()
     * @Assert\Date()
     */
    protected $dtPublicacao;

    /**
     * @var \DateTime
     * @Assert\NotBlank()
     * @Assert\Date()
     * @Assert\Expression(
     *      "this.getDtInicioVigencia() < this.getDtFimVigencia()",
     *      message="A data de início da vigência não pode ser posterior a data de término da vigência."
     * )
     */
    protected $dtInicioVigencia;

    /**
     * @var \DateTime
     * @Assert\NotBlank()
     * @Assert\Date()
     */
    protected $dtFimVigencia;

    /**
     * @var string
     * @Assert\Length(
     *      min = 0,
     *      max = 1950
     * )
     */
    protected $dsPublicacao;

    /**
     * @var string
     * @Assert\NotBlank()
     * @Assert\Choice(choices = {"E", "P", "M"})
     */
    protected $tpPublicacao;
    
    /**
     * @var integer
     * @Assert\NotBlank()
     * @Assert\Type(
     *     type="integer"
     * )
     * @Assert\Range(
     *     min = 1,
     *     max = 99999
     * )
     */
    protected $qtdMinimaBolsistasGrupo;
    
    /**
     * @var integer
     * @Assert\NotBlank()
     * @Assert\Type(
     *     type="integer"
     * )
     * @Assert\Range(
     *     min = 1,
     *     max = 99999
     * )
     */
    protected $qtdMaximaBolsistasGrupo;

    /**
     * @return integer
     */
    public function getCoSeqPublicacao()
    {
        return $this->coSeqPublicacao;
    }

    /**
     * @param integer $coSeqPublicacao
     * @return AtualizarPublicacaoCommand
     */
    public function setCoSeqPublicacao($coSeqPublicacao)
    {
        $this->coSeqPublicacao = $coSeqPublicacao;
        return $this;
    }
        
    /**
     * @return int
     */
    public function getNuPublicacao()
    {
        return $this->nuPublicacao;
    }

    /**
     * @param int $nuPublicacao
     * @return AtualizarProgramaCommand
     */
    public function setNuPublicacao($nuPublicacao)
    {
        $this->nuPublicacao = $nuPublicacao;
        return $this;
    }

    /**
     * @return \DateTime
     */
    public function getDtPublicacao()
    {
        return $this->dtPublicacao;
    }

    /**
     * @param \DateTime $dtPublicacao
     * @return AtualizarProgramaCommand
     */
    public function setDtPublicacao($dtPublicacao)
    {
        $this->dtPublicacao = $dtPublicacao;
        return $this;
    }

    /**
     * @return \DateTime
     */
    public function getDtInicioVigencia()
    {
        return $this->dtInicioVigencia;
    }

    /**
     * @param \DateTime $dtInicioVigencia
     * @return AtualizarProgramaCommand
     */
    public function setDtInicioVigencia($dtInicioVigencia)
    {
        $this->dtInicioVigencia = $dtInicioVigencia;
        return $this;
    }

    /**
     * @return \DateTime
     */
    public function getDtFimVigencia()
    {
        return $this->dtFimVigencia;
    }

    /**
     * @param \DateTime $dtFimVigencia
     * @return AtualizarProgramaCommand
     */
    public function setDtFimVigencia($dtFimVigencia)
    {
        $this->dtFimVigencia = $dtFimVigencia;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getDsPublicacao()
    {
        return $this->dsPublicacao;
    }

    /**
     * @param mixed $dsPublicacao
     * @return AtualizarProgramaCommand
     */
    public function setDsPublicacao($dsPublicacao)
    {
        $this->dsPublicacao = $dsPublicacao;
        return $this;
    }

    /**
     * @return string
     */
    public function getTpPublicacao()
    {
        return $this->tpPublicacao;
    }

    /**
     * @param string $tpPublicacao
     * @return AtualizarProgramaCommand
     */
    public function setTpPublicacao($tpPublicacao)
    {
        $this->tpPublicacao = $tpPublicacao;
        return $this;
    }
    
    /**
     * @return integer
     */
    public function getQtdMinimaBolsistasGrupo()
    {
        return $this->qtdMinimaBolsistasGrupo;
    }

    /**
     * @return integer
     */
    public function getQtdMaximaBolsistasGrupo()
    {
        return $this->qtdMaximaBolsistasGrupo;
    }

    /**
     * @param integer $qtdMinimaBolsistasGrupo
     * @return CadastrarProgramaCommand
     */
    public function setQtdMinimaBolsistasGrupo($qtdMinimaBolsistasGrupo)
    {
        $this->qtdMinimaBolsistasGrupo = $qtdMinimaBolsistasGrupo;
        return $this;
    }

    /**
     * @param integer $qtdMaximaBolsistasGrupo
     * @return CadastrarProgramaCommand
     */
    public function setQtdMaximaBolsistasGrupo($qtdMaximaBolsistasGrupo)
    {
        $this->qtdMaximaBolsistasGrupo = $qtdMaximaBolsistasGrupo;
        return $this;
    }   
}