<?php

namespace App\CommandBus;

use App\Entity\Programa;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Context\ExecutionContextInterface;

class CadastrarProgramaCommand
{
    /**
     * @var string
     * @Assert\NotBlank()
     * @Assert\Length(min = 5, max = 30)
     */
    protected $dsPrograma;

    /**
     * @var integer
     *
     * @Assert\NotBlank()
     */
    protected $tpPrograma;
    
    /**
     * @var integer
     * @Assert\NotBlank()
     * @Assert\Choice(choices = {1, 2})
     */
    protected $tpAreaTematica;    

    /**
     * @var integer
     * @Assert\NotBlank()
     * @Assert\Range(
     *     min = 1,
     *     max = 999
     * )
     * @Assert\Type(
     *     type="integer"
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
     * @Assert\NotBlank()
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
     * @return string
     */
    public function getDsPrograma()
    {
        return $this->dsPrograma;
    }

    /**
     * @param string $dsPrograma
     * @return CadastrarProgramaCommand
     */
    public function setDsPrograma($dsPrograma)
    {
        $this->dsPrograma = $dsPrograma;
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
     * @return CadastrarProgramaCommand
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
     * @return CadastrarProgramaCommand
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
     * @return CadastrarProgramaCommand
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
     * @return CadastrarProgramaCommand
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
     * @return CadastrarProgramaCommand
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
     * @return CadastrarProgramaCommand
     */
    public function setTpPublicacao($tpPublicacao)
    {
        $this->tpPublicacao = $tpPublicacao;
        return $this;
    }
    
    /**
     * @return integer
     */
    public function getTpAreaTematica()
    {
        return $this->tpAreaTematica;
    }

    /**
     * @param integer $tpAreaTematica
     * @return CadastrarProgramaCommand
     */
    public function setTpAreaTematica($tpAreaTematica)
    {
        $this->tpAreaTematica = $tpAreaTematica;
        return $this;
    }

    /**
     * @return int
     */
    public function getTpPrograma()
    {
        return $this->tpPrograma;
    }

    /**
     * @param int $tpPrograma
     */
    public function setTpPrograma($tpPrograma)
    {
        $this->tpPrograma = $tpPrograma;
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

    /**
     * @param ExecutionContextInterface $context
     *
     * @Assert\Callback()
     */
    public function validateTpPrograma(ExecutionContextInterface $context)
    {
        if (!in_array($this->tpPrograma, Programa::getTpProgramas())) {
            $context->buildViolation('Tipo de Programa inválido.')
                ->addViolation();
        }
    }
}