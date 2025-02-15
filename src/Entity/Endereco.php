<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Entity\Pessoa;

/**
 * Endereco
 *
 * @ORM\Table(name="DBPET.TB_ENDERECO")
 * @ORM\Entity(repositoryClass="App\Repository\EnderecoRepository")
 */
class Endereco extends AbstractEntity
{
    use \App\Traits\DeleteLogicoTrait;
    use \App\Traits\DataInclusaoTrait;
    
    /**
     * @var int
     *
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="SEQUENCE")
     * @ORM\Column(name="CO_SEQ_ENDERECO", type="integer", unique=true)
     * @ORM\SequenceGenerator(sequenceName="DBPET.SQ_ENDERECO_COSEQENDERECO", allocationSize=1, initialValue=1)
     */
    private $coSeqEndereco;
    
    /**
     * @var Pessoa
     * 
     * @ORM\ManyToOne(targetEntity="App\Entity\Pessoa")
     * @ORM\JoinColumn(name="NU_CPF_CNPJ_PESSOA", referencedColumnName="NU_CPF_CNPJ_PESSOA")
     */
    private $pessoa;

    /**
     * @var Cep
     *
     * @ORM\ManyToOne(targetEntity="App\Entity\Cep")
     * @ORM\JoinColumn(name="CO_CEP", referencedColumnName="CO_SEQ_CEP")
     */
    private $cep;

    /**
     * @var Municipio
     *
     * @ORM\ManyToOne(targetEntity="App\Entity\Municipio")
     * @ORM\JoinColumn(name="CO_MUNICIPIO_IBGE", referencedColumnName="CO_MUNICIPIO_IBGE")
     */
    private $municipio;

    /**
     * @var string
     *
     * @ORM\Column(name="NO_LOGRADOURO", type="string", length=50)
     */
    private $noLogradouro;

    /**
     * @var string
     *
     * @ORM\Column(name="DS_COMPLEMENTO", type="string", length=160, nullable=true)
     */
    private $dsComplemento;

    /**
     * @var string
     *
     * @ORM\Column(name="NO_BAIRRO", type="string", length=30)
     */
    private $noBairro;

    /**
     * @var string
     *
     * @ORM\Column(name="NU_LOGRADOURO", type="string", length=7)
     */
    private $nuLogradouro;

    /**
     * @param Pessoa $pessoa
     * @param Cep $cep
     * @param Municipio $municipio
     * @param string $noLogradouro
     * @param string $dsComplemento
     * @param string $noBairro
     * @param string $nuLogradouro
     */
    public function __construct(
        Pessoa $pessoa, 
        Cep $cep, 
        Municipio $municipio, 
        $noLogradouro, 
        $dsComplemento, 
        $noBairro, 
        $nuLogradouro
    ) {
        $this->pessoa           = $pessoa;
        $this->cep              = $cep;
        $this->municipio        = $municipio;
        $this->noLogradouro     = $noLogradouro;
        $this->dsComplemento    = $dsComplemento;
        $this->noBairro         = $noBairro;
        $this->nuLogradouro     = $nuLogradouro;
        $this->stRegistroAtivo  = 'S';
        $this->dtInclusao       = new \DateTime();
    }
    
    /**
     * Get coSeqEndereco
     *
     * @return int
     */
    public function getCoSeqEndereco()
    {
        return $this->coSeqEndereco;
    }

    /**
     * Get pessoa
     *
     * @return string
     */
    public function getPessoa()
    {
        return $this->pessoa;
    }

    /**
     * Get cep
     *
     * @return Cep
     */
    public function getCep()
    {
        return $this->cep;
    }
    
    /**
     * Get municipio
     *
     * @return Municipio
     */
    public function getMunicipio()
    {
        return $this->municipio;
    }

    /**
     * Get noLogradouro
     *
     * @return string
     */
    public function getNoLogradouro()
    {
        return $this->noLogradouro;
    }

    /**
     * Get dsComplemento
     *
     * @return string
     */
    public function getDsComplemento()
    {
        return $this->dsComplemento;
    }

    /**
     * Get noBairro
     *
     * @return string
     */
    public function getNoBairro()
    {
        return $this->noBairro;
    }

    /**
     * Get nuLogradouro
     *
     * @return string
     */
    public function getNuLogradouro()
    {
        return $this->nuLogradouro;
    }

    /**
     * @param Cep $cep
     * @return Endereco
     */
    public function setCep(Cep $cep)
    {
        $this->cep = $cep;
        return $this;
    }
    
    /**
     * @param Municipio $municipio
     * @return Endereco
     */
    public function setMunicipio(Municipio $municipio)
    {
        $this->municipio = $municipio;
        return $this;
    }

    /**
     * @param string $noLogradouro
     * @return Endereco
     */
    public function setNoLogradouro($noLogradouro)
    {
        $this->noLogradouro = $noLogradouro;
        return $this;
    }

    /**
     * @param string $dsComplemento
     * @return Endereco
     */
    public function setDsComplemento($dsComplemento)
    {
        $this->dsComplemento = $dsComplemento;
        return $this;
    }

    /**
     * @param string $noBairro
     * @return Endereco
     */
    public function setNoBairro($noBairro)
    {
        $this->noBairro = $noBairro;
        return $this;
    }

    /**
     * @param string $nuLogradouro
     * @return Endereco
     */
    public function setNuLogradouro($nuLogradouro)
    {
        $this->nuLogradouro = $nuLogradouro;
        return $this;
    }
}