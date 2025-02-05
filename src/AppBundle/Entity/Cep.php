<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="DBGERAL.TB_CEP")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\CepRepository")
 */
class Cep extends AbstractEntity
{
    /**
     * @ORM\Id
     * @ORM\Column(name="CO_SEQ_CEP", type="integer", nullable=false)
     */
    private $coSeqCep;
    
    /**
     * @var string
     * 
     * @ORM\Column(name="NU_CEP", type="string", length=8, nullable=false)
     */
    private $nuCep;

    /**
     * @var string
     * 
     * @ORM\Column(name="SG_UF", type="string", length=2, nullable=false)
     */
    private $sgUf;

    /**
     * @var Municipio
     * 
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Municipio")
     * @ORM\JoinColumn(name="CO_MUNICIPIO_IBGE", referencedColumnName="CO_MUNICIPIO_IBGE")
     */
    private $municipio;

    /**
     * @var string
     * 
     * @ORM\Column(name="TP_LOGRADOURO", type="string", length=26)
     */
    private $tpLogradouro;

    /**
     * @var string
     * 
     * @ORM\Column(name="NO_LOGRADOURO", type="string", length=250)
     */
    private $noLogradouro;

    /**
     * @var string
     * 
     * @ORM\Column(name="DS_COMPLEMENTO", type="string", length=36)
     */
    private $dsComplemento;

    /**
     * @var string
     * 
     * @ORM\Column(name="ST_REGISTRO_ATIVO", type="string", length=1)
     */
    private $stRegistroAtivo;

    /**
     * @return integer
     */
    public function getCoSeqCep()
    {
        return $this->coSeqCep;
    }

    /**
     * @return string
     */
    public function getNuCep()
    {
        return $this->nuCep;
    }
    
    /**
     * @return string
     */
    public function getSgUf()
    {
        return $this->sgUf;
    }

    /**
     * @return Municipio
     */
    public function getMunicipio()
    {
        return $this->municipio;
    }

    /**
     * @return string
     */
    public function getTpLogradouro()
    {
        return $this->tpLogradouro;
    }

    /**
     * @return string
     */
    public function getNoLogradouro()
    {
        return $this->noLogradouro;
    }

    /**
     * @return string
     */
    public function getDsComplemento()
    {
        return $this->dsComplemento;
    }

    /**
     * @return string
     */
    public function getStRegistroAtivo()
    {
        return $this->stRegistroAtivo;
    }
}