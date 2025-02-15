<?php

namespace App\CommandBus;

use App\Entity\Municipio;
use App\Entity\ProjetoPessoa;
use App\Entity\Uf;
use Symfony\Component\Validator\Constraints as Assert;

final class SalvarEmissaoCertificadoCommand
{
    /**
     *
     * @var integer
     * 
     * @Assert\NotBlank()
     * @Assert\Regex(
     *      pattern = "/[0-9]/",
     *      message = "Apenas números."
     * )
     * @Assert\Length(
     *      max = 4,
     *      maxMessage = "A carga horária não deve exceder 9999."
     * )
     */
    private $qtCargaHoraria;
    
    /**
     *
     * @var Uf
     * 
     * @Assert\NotBlank() 
     */
    private $uf;
    
    /**
     *
     * @var Municipio
     * 
     * @Assert\NotBlank()
     */
    private $municipio;
    
    /**
     *
     * @var boolean     
     */
    private $stFinalizacaoContrato;
    
    /**
     *
     * @var array
     * 
     * @Assert\NotBlank()     
     */
    private $participantes;
    
    /**
     *
     * @var ProjetoPessoa
     */
    private $projetoPessoaResponsavel;
    
    /**
     *
     * @var array
     */
    private $projetoPessoaParticipantes;
    
    /**
     * 
     * @param ProjetoPessoa $projetoPessoaResponsavel
     */
    public function __construct(ProjetoPessoa $projetoPessoaResponsavel)
    {
        $this->projetoPessoaResponsavel = $projetoPessoaResponsavel;
    }

    /**
     * 
     * @return integer
     */
    public function getQtCargaHoraria()
    {
        return $this->qtCargaHoraria;
    }
    
    /**
     * 
     * @return Uf
     */
    public function getUf()
    {
        return $this->uf;
    }
    
    /**
     * 
     * @return Municipio
     */
    public function getMunicipio()
    {
        return $this->municipio;
    }
    
    /**
     * 
     * @return boolean
     */
    public function getStFinalizacaoContrato()
    {
        return $this->stFinalizacaoContrato;
    }
    
    /**
     * 
     * @return array
     */
    public function getParticipantes()
    {
        return $this->participantes;
    }
    
    /**
     * 
     * @return ProjetoPessoa
     */
    public function getProjetoPessoaResponsavel()
    {
        return $this->projetoPessoaResponsavel;
    }
        
    /**
     * 
     * @return array<\App\Entity\ProjetoPessoa>
     */
    public function getProjetoPessoaParticipantes()
    {
        return $this->projetoPessoaParticipantes;
    }
    
    /**
     * 
     * @param integer $qtCargaHoraria
     */
    public function setQtCargaHoraria($qtCargaHoraria)
    {
        $this->qtCargaHoraria = $qtCargaHoraria;
    }
    
    /**
     * 
     * @param Uf $uf
     */
    public function setUf(Uf $uf)
    {
        $this->uf = $uf;
    }

    /**
     * 
     * @param Municipio $municipio
     */
    public function setMunicipio(Municipio $municipio)
    {
        $this->municipio = $municipio;
    }
    
    /**
     * 
     * @param boolean $stFinalizacaoContrato
     */
    public function setStFinalizacaoContrato($stFinalizacaoContrato)
    {
        $this->stFinalizacaoContrato = $stFinalizacaoContrato;
    }
    
    /**
     * 
     * @param string $participantes
     */
    public function setParticipantes($participantes)
    {
        $this->participantes = explode(',', $participantes);
    }
    
    /**
     * 
     * @param \App\Entity\ProjetoPessoa $projetoPessoaParticipante
     */
    public function addProjetoPessoaParticipantes(ProjetoPessoa $projetoPessoaParticipante)
    {
        $this->projetoPessoaParticipantes[] = $projetoPessoaParticipante;
    }
}
