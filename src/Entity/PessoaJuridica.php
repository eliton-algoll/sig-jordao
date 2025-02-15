<?php

namespace App\Entity;;

use Doctrine\ORM\Mapping as ORM;

/**
 * PessoaJuridica
 *
 * @ORM\Table(name="DBPESSOA.TB_PESSOA_JURIDICA")
 * @ORM\Entity(repositoryClass="App\Repository\PessoaJuridicaRepository")
 */
class PessoaJuridica extends AbstractEntity
{
    /**
     * @var string
     *
     * @ORM\Id
     * @ORM\Column(name="NU_CNPJ", type="string", nullable=false)
     * @ORM\GeneratedValue(strategy="NONE")
     */
    private $nuCnpj;    
    
    /**
     * @var string
     *
     * @ORM\Column(name="CO_OPCAO_SIMPLES", type="string", length=1, nullable=true)
     */
    private $coOpcaoSimples;

    /**
     * @var string
     *
     * @ORM\Column(name="CO_PAIS", type="string", length=3, nullable=true)
     */
    private $coPais;

    /**
     * @var string
     *
     * @ORM\Column(name="CO_PORTE_EMPRESA", type="string", length=2, nullable=true)
     */
    private $coPorteEmpresa;

    /**
     * @var string
     *
     * @ORM\Column(name="DS_EMAIL", type="string", length=115, nullable=true)
     */
    private $dsEmail;

    /**
     * @var string
     *
     * @ORM\Column(name="DS_SITUACAO_CNPJ", type="string", length=500, nullable=true)
     */
    private $dsSituacaoCnpj;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="DT_ABERTURA", type="datetime", nullable=true)
     */
    private $dtAbertura;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="DT_EXCLUSAO_OPCAO_SIMPLES", type="datetime", nullable=true)
     */
    private $dtExclusaoOpcaoSimples;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="DT_OPCAO_SIMPLES", type="datetime", nullable=true)
     */
    private $dtOpcaoSimples;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="DT_SITUACAO_CNPJ", type="datetime", nullable=true)
     */
    private $dtSituacaoCnpj;

    /**
     * @var string
     *
     * @ORM\Column(name="NO_CIDADE_EXTERIOR", type="string", length=60, nullable=true)
     */
    private $noCidadeExterior;

    /**
     * @var string
     *
     * @ORM\Column(name="NO_FANTASIA", type="string", length=250, nullable=true)
     */
    private $noFantasia;

    /**
     * @var string
     *
     * @ORM\Column(name="NO_PAIS", type="string", length=70, nullable=true)
     */
    private $noPais;

    /**
     * @var integer
     *
     * @ORM\Column(name="NU_CAPITAL_SOCIAL", type="integer", nullable=true)
     */
    private $nuCapitalSocial;

    /**
     * @var string
     *
     * @ORM\Column(name="NU_CNPJ_SUCEDIDA", type="string", length=14, nullable=true)
     */
    private $nuCnpjSucedida;

    /**
     * @var string
     *
     * @ORM\Column(name="NU_CNPJ_SUCESSORA", type="string", length=14, nullable=true)
     */
    private $nuCnpjSucessora;

    /**
     * @var string
     *
     * @ORM\Column(name="NU_CPF_RESPONSAVEL", type="string", length=11, nullable=true)
     */
    private $nuCpfResponsavel;

    /**
     * @var string
     *
     * @ORM\Column(name="NU_DDD", type="string", length=4, nullable=true)
     */
    private $nuDdd;

    /**
     * @var string
     *
     * @ORM\Column(name="NU_DDD_FAX", type="string", length=4, nullable=true)
     */
    private $nuDddFax;

    /**
     * @var string
     *
     * @ORM\Column(name="NU_FAX", type="string", length=8, nullable=true)
     */
    private $nuFax;

    /**
     * @var string
     *
     * @ORM\Column(name="NU_TELEFONE", type="string", length=8, nullable=true)
     */
    private $nuTelefone;

    /**
     * @var string
     *
     * @ORM\Column(name="ST_OPCAO_SIMPLES", type="string", length=1, nullable=true)
     */
    private $stOpcaoSimples;

    /**
     * @var string
     *
     * @ORM\Column(name="ST_REGISTRO_ATIVO", type="string", length=1, nullable=true)
     */
    private $stRegistroAtivo;

    /**
     * @var string
     *
     * @ORM\Column(name="TP_LOGRADOURO", type="string", length=30, nullable=true)
     */
    private $tpLogradouro;

    /**
     * @var boolean
     *
     * @ORM\Column(name="TP_MATRIZ_FILIAL", type="boolean", nullable=true)
     */
    private $tpMatrizFilial;

    /**
     * @var boolean
     *
     * @ORM\Column(name="TP_SITUACAO_CNPJ", type="boolean", nullable=true)
     */
    private $tpSituacaoCnpj;

    /**
     * @var string
     *
     * @ORM\Column(name="CO_NATUREZA_JURIDICA", type="string")
     */
    private $coNaturezaJuridica;
    
    /**
     * @var Pessoa
     * 
     * @ORM\OneToOne(targetEntity="App\Entity\Pessoa")
     * @ORM\JoinColumn(name="NU_CNPJ", referencedColumnName="NU_CPF_CNPJ_PESSOA")
     */
    private $pessoa;
    
    /**
     * Set coOpcaoSimples
     *
     * @param string $coOpcaoSimples
     * @return PessoaJuridica
     */
    public function setCoOpcaoSimples($coOpcaoSimples)
    {
        $this->coOpcaoSimples = $coOpcaoSimples;
    
        return $this;
    }

    /**
     * Get coOpcaoSimples
     *
     * @return string 
     */
    public function getCoOpcaoSimples()
    {
        return $this->coOpcaoSimples;
    }

    /**
     * Set coPais
     *
     * @param string $coPais
     * @return PessoaJuridica
     */
    public function setCoPais($coPais)
    {
        $this->coPais = $coPais;
    
        return $this;
    }

    /**
     * Get coPais
     *
     * @return string 
     */
    public function getCoPais()
    {
        return $this->coPais;
    }

    /**
     * Set coPorteEmpresa
     *
     * @param string $coPorteEmpresa
     * @return PessoaJuridica
     */
    public function setCoPorteEmpresa($coPorteEmpresa)
    {
        $this->coPorteEmpresa = $coPorteEmpresa;
    
        return $this;
    }

    /**
     * Get coPorteEmpresa
     *
     * @return string 
     */
    public function getCoPorteEmpresa()
    {
        return $this->coPorteEmpresa;
    }

    /**
     * Set dsEmail
     *
     * @param string $dsEmail
     * @return PessoaJuridica
     */
    public function setDsEmail($dsEmail)
    {
        $this->dsEmail = $dsEmail;
    
        return $this;
    }

    /**
     * Get dsEmail
     *
     * @return string 
     */
    public function getDsEmail()
    {
        return $this->dsEmail;
    }

    /**
     * Set dsSituacaoCnpj
     *
     * @param string $dsSituacaoCnpj
     * @return PessoaJuridica
     */
    public function setDsSituacaoCnpj($dsSituacaoCnpj)
    {
        $this->dsSituacaoCnpj = $dsSituacaoCnpj;
    
        return $this;
    }

    /**
     * Get dsSituacaoCnpj
     *
     * @return string 
     */
    public function getDsSituacaoCnpj()
    {
        return $this->dsSituacaoCnpj;
    }

    /**
     * Set dtAbertura
     *
     * @param \DateTime $dtAbertura
     * @return PessoaJuridica
     */
    public function setDtAbertura($dtAbertura)
    {
        $this->dtAbertura = $dtAbertura;
    
        return $this;
    }

    /**
     * Get dtAbertura
     *
     * @return \DateTime 
     */
    public function getDtAbertura()
    {
        return $this->dtAbertura;
    }

    /**
     * Set dtExclusaoOpcaoSimples
     *
     * @param \DateTime $dtExclusaoOpcaoSimples
     * @return PessoaJuridica
     */
    public function setDtExclusaoOpcaoSimples($dtExclusaoOpcaoSimples)
    {
        $this->dtExclusaoOpcaoSimples = $dtExclusaoOpcaoSimples;
    
        return $this;
    }

    /**
     * Get dtExclusaoOpcaoSimples
     *
     * @return \DateTime 
     */
    public function getDtExclusaoOpcaoSimples()
    {
        return $this->dtExclusaoOpcaoSimples;
    }

    /**
     * Set dtOpcaoSimples
     *
     * @param \DateTime $dtOpcaoSimples
     * @return PessoaJuridica
     */
    public function setDtOpcaoSimples($dtOpcaoSimples)
    {
        $this->dtOpcaoSimples = $dtOpcaoSimples;
    
        return $this;
    }

    /**
     * Get dtOpcaoSimples
     *
     * @return \DateTime 
     */
    public function getDtOpcaoSimples()
    {
        return $this->dtOpcaoSimples;
    }

    /**
     * Set dtSituacaoCnpj
     *
     * @param \DateTime $dtSituacaoCnpj
     * @return PessoaJuridica
     */
    public function setDtSituacaoCnpj($dtSituacaoCnpj)
    {
        $this->dtSituacaoCnpj = $dtSituacaoCnpj;
    
        return $this;
    }

    /**
     * Get dtSituacaoCnpj
     *
     * @return \DateTime 
     */
    public function getDtSituacaoCnpj()
    {
        return $this->dtSituacaoCnpj;
    }

    /**
     * Set noCidadeExterior
     *
     * @param string $noCidadeExterior
     * @return PessoaJuridica
     */
    public function setNoCidadeExterior($noCidadeExterior)
    {
        $this->noCidadeExterior = $noCidadeExterior;
    
        return $this;
    }

    /**
     * Get noCidadeExterior
     *
     * @return string 
     */
    public function getNoCidadeExterior()
    {
        return $this->noCidadeExterior;
    }

    /**
     * Set noFantasia
     *
     * @param string $noFantasia
     * @return PessoaJuridica
     */
    public function setNoFantasia($noFantasia)
    {
        $this->noFantasia = $noFantasia;
    
        return $this;
    }

    /**
     * Get noFantasia
     *
     * @return string 
     */
    public function getNoFantasia()
    {
        return $this->noFantasia;
    }

    /**
     * Set noPais
     *
     * @param string $noPais
     * @return PessoaJuridica
     */
    public function setNoPais($noPais)
    {
        $this->noPais = $noPais;
    
        return $this;
    }

    /**
     * Get noPais
     *
     * @return string 
     */
    public function getNoPais()
    {
        return $this->noPais;
    }

    /**
     * Set nuCapitalSocial
     *
     * @param integer $nuCapitalSocial
     * @return PessoaJuridica
     */
    public function setNuCapitalSocial($nuCapitalSocial)
    {
        $this->nuCapitalSocial = $nuCapitalSocial;
    
        return $this;
    }

    /**
     * Get nuCapitalSocial
     *
     * @return integer 
     */
    public function getNuCapitalSocial()
    {
        return $this->nuCapitalSocial;
    }

    /**
     * Set nuCnpjSucedida
     *
     * @param string $nuCnpjSucedida
     * @return PessoaJuridica
     */
    public function setNuCnpjSucedida($nuCnpjSucedida)
    {
        $this->nuCnpjSucedida = $nuCnpjSucedida;
    
        return $this;
    }

    /**
     * Get nuCnpjSucedida
     *
     * @return string 
     */
    public function getNuCnpjSucedida()
    {
        return $this->nuCnpjSucedida;
    }

    /**
     * Set nuCnpjSucessora
     *
     * @param string $nuCnpjSucessora
     * @return PessoaJuridica
     */
    public function setNuCnpjSucessora($nuCnpjSucessora)
    {
        $this->nuCnpjSucessora = $nuCnpjSucessora;
    
        return $this;
    }

    /**
     * Get nuCnpjSucessora
     *
     * @return string 
     */
    public function getNuCnpjSucessora()
    {
        return $this->nuCnpjSucessora;
    }

    /**
     * Set nuCpfResponsavel
     *
     * @param string $nuCpfResponsavel
     * @return PessoaJuridica
     */
    public function setNuCpfResponsavel($nuCpfResponsavel)
    {
        $this->nuCpfResponsavel = $nuCpfResponsavel;
    
        return $this;
    }

    /**
     * Get nuCpfResponsavel
     *
     * @return string 
     */
    public function getNuCpfResponsavel()
    {
        return $this->nuCpfResponsavel;
    }

    /**
     * Set nuDdd
     *
     * @param string $nuDdd
     * @return PessoaJuridica
     */
    public function setNuDdd($nuDdd)
    {
        $this->nuDdd = $nuDdd;
    
        return $this;
    }

    /**
     * Get nuDdd
     *
     * @return string 
     */
    public function getNuDdd()
    {
        return $this->nuDdd;
    }

    /**
     * Set nuDddFax
     *
     * @param string $nuDddFax
     * @return PessoaJuridica
     */
    public function setNuDddFax($nuDddFax)
    {
        $this->nuDddFax = $nuDddFax;
    
        return $this;
    }

    /**
     * Get nuDddFax
     *
     * @return string 
     */
    public function getNuDddFax()
    {
        return $this->nuDddFax;
    }

    /**
     * Set nuFax
     *
     * @param string $nuFax
     * @return PessoaJuridica
     */
    public function setNuFax($nuFax)
    {
        $this->nuFax = $nuFax;
    
        return $this;
    }

    /**
     * Get nuFax
     *
     * @return string 
     */
    public function getNuFax()
    {
        return $this->nuFax;
    }

    /**
     * Set nuTelefone
     *
     * @param string $nuTelefone
     * @return PessoaJuridica
     */
    public function setNuTelefone($nuTelefone)
    {
        $this->nuTelefone = $nuTelefone;
    
        return $this;
    }

    /**
     * Get nuTelefone
     *
     * @return string 
     */
    public function getNuTelefone()
    {
        return $this->nuTelefone;
    }

    /**
     * Set stOpcaoSimples
     *
     * @param string $stOpcaoSimples
     * @return PessoaJuridica
     */
    public function setStOpcaoSimples($stOpcaoSimples)
    {
        $this->stOpcaoSimples = $stOpcaoSimples;
    
        return $this;
    }

    /**
     * Get stOpcaoSimples
     *
     * @return string 
     */
    public function getStOpcaoSimples()
    {
        return $this->stOpcaoSimples;
    }

    /**
     * Set stRegistroAtivo
     *
     * @param string $stRegistroAtivo
     * @return PessoaJuridica
     */
    public function setStRegistroAtivo($stRegistroAtivo)
    {
        $this->stRegistroAtivo = $stRegistroAtivo;
    
        return $this;
    }

    /**
     * Get stRegistroAtivo
     *
     * @return string 
     */
    public function getStRegistroAtivo()
    {
        return $this->stRegistroAtivo;
    }

    /**
     * Set tpLogradouro
     *
     * @param string $tpLogradouro
     * @return PessoaJuridica
     */
    public function setTpLogradouro($tpLogradouro)
    {
        $this->tpLogradouro = $tpLogradouro;
    
        return $this;
    }

    /**
     * Get tpLogradouro
     *
     * @return string 
     */
    public function getTpLogradouro()
    {
        return $this->tpLogradouro;
    }

    /**
     * Set tpMatrizFilial
     *
     * @param boolean $tpMatrizFilial
     * @return PessoaJuridica
     */
    public function setTpMatrizFilial($tpMatrizFilial)
    {
        $this->tpMatrizFilial = $tpMatrizFilial;
    
        return $this;
    }

    /**
     * Get tpMatrizFilial
     *
     * @return boolean 
     */
    public function getTpMatrizFilial()
    {
        return $this->tpMatrizFilial;
    }

    /**
     * Set tpSituacaoCnpj
     *
     * @param boolean $tpSituacaoCnpj
     * @return PessoaJuridica
     */
    public function setTpSituacaoCnpj($tpSituacaoCnpj)
    {
        $this->tpSituacaoCnpj = $tpSituacaoCnpj;
    
        return $this;
    }

    /**
     * Get tpSituacaoCnpj
     *
     * @return boolean 
     */
    public function getTpSituacaoCnpj()
    {
        return $this->tpSituacaoCnpj;
    }

    /**
     * Get nuCnpj
     *
     * @return string
     */
    public function getNuCnpj()
    {
        return $this->nuCnpj;
    }

    /**
     * Set NaturezaJuridica
     *
     * @param string $coNaturezaJuridica
     * @return PessoaJuridica
     */
    public function setCoNaturezaJuridica($coNaturezaJuridica)
    {
        $this->coNaturezaJuridica = $coNaturezaJuridica;
        return $this;
    }

    /**
     * Get NaturezaJuridica
     *
     * @return string
     */
    public function getCoNaturezaJuridica()
    {
        return $this->coNaturezaJuridica;
    }
    
    /**
     * @return Pessoa
     */
    public function getPessoa()
    {
        return $this->pessoa;
    }
}