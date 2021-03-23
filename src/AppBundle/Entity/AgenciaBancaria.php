<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * AgenciaBancaria
 *
 * @ORM\Table(name="DBGERAL.TB_AGENCIA_BANCARIA")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\AgenciaBancariaRepository")
 */
class AgenciaBancaria extends AbstractEntity
{

    /**
     * @var string
     *
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="NONE")
     * @ORM\Column(name="CO_AGENCIA_BANCARIA", type="string", nullable=false)
     */
    private $coAgenciaBancaria;

    /**
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Banco")
     * @ORM\JoinColumn(name="CO_BANCO", referencedColumnName="CO_BANCO")
     */
    private $coBanco;

    /**
     * @var string
     * @ORM\Column(name="NO_AGENCIA", type="string")
     */
    private $noAgencia;

    /**
     * @var string
     * @ORM\Column(name="DS_ENDERECO", type="string")
     */
    private $dsEndereco;

    /**
     * @var string
     * @ORM\Column(name="NO_BAIRRO", type="string")
     */
    private $noBairro;

    /**
     * @var string
     * @ORM\Column(name="NU_CEP", type="string")
     */
    private $nuCep;

    /**
     * @var string
     * @ORM\Column(name="NU_DDD", type="string")
     */
    private $nuDdd;

    /**
     * @var string
     * @ORM\Column(name="NU_TELEFONE", type="string")
     */
    private $nuTelefone;

    /**
     * @var string
     * @ORM\Column(name="ST_REGISTRO_ATIVO", type="string")
     */
    private $stRegistroAtivo;

    /**
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Municipio")
     * @ORM\JoinColumn(name="CO_MUNICIPIO_IBGE", referencedColumnName="CO_MUNICIPIO_IBGE")
     */
    private $coMunicipioIbge;

    /**
     * @var string
     * @ORM\Column(name="TP_AGENCIA", type="string")
     */
    private $tpAgencia;

    /**
     * @var string
     * @ORM\Column(name="DS_PERFIL_AGENCIA", type="string")
     */
    private $dsPerfilAgencia;

    /**
     * @var datetime
     * @ORM\Column(name="DT_CRIACAO", type="datetime")
     */
    private $dtCriacao;
    
    /**
     * @param int $coAgenciaBancaria
     */
    public function setCoAgenciaBancaria($coAgenciaBancaria)
    {
        $this->coAgenciaBancaria = $coAgenciaBancaria;
    }

    /**
     * @return int
     */
    public function getCoAgenciaBancaria()
    {
        return $this->coAgenciaBancaria;
    }

    /**
     * @param mixed $coBanco
     */
    public function setCoBanco($coBanco)
    {
        $this->coBanco = $coBanco;
    }

    /**
     * @return mixed
     */
    public function getCoBanco()
    {
        return $this->coBanco;
    }

    /**
     * @param mixed $coMunicipioIbge
     */
    public function setCoMunicipioIbge($coMunicipioIbge)
    {
        $this->coMunicipioIbge = $coMunicipioIbge;
    }

    /**
     * @return mixed
     */
    public function getCoMunicipioIbge()
    {
        return $this->coMunicipioIbge;
    }

    /**
     * @param string $dsEndereco
     */
    public function setDsEndereco($dsEndereco)
    {
        $this->dsEndereco = $dsEndereco;
    }

    /**
     * @return string
     */
    public function getDsEndereco()
    {
        return $this->dsEndereco;
    }

    /**
     * @param string $dsPerfilAgencia
     */
    public function setDsPerfilAgencia($dsPerfilAgencia)
    {
        $this->dsPerfilAgencia = $dsPerfilAgencia;
    }

    /**
     * @return string
     */
    public function getDsPerfilAgencia()
    {
        return $this->dsPerfilAgencia;
    }

    /**
     * @param \Datasus\Core\BaseBundle\Entity\datetime $dtCriacao
     */
    public function setDtCriacao(\Datetime $dtCriacao = null)
    {
        $this->dtCriacao = $dtCriacao;
    }

    /**
     * @return \Datasus\Core\BaseBundle\Entity\datetime
     */
    public function getDtCriacao()
    {
        return $this->dtCriacao;
    }

    /**
     * @param string $noAgencia
     */
    public function setNoAgencia($noAgencia)
    {
        $this->noAgencia = $noAgencia;
    }

    /**
     * @return string
     */
    public function getNoAgencia()
    {
        return $this->noAgencia;
    }

    /**
     * @param string $noBairro
     */
    public function setNoBairro($noBairro)
    {
        $this->noBairro = $noBairro;
    }

    /**
     * @return string
     */
    public function getNoBairro()
    {
        return $this->noBairro;
    }

    /**
     * @param string $nuCep
     */
    public function setNuCep($nuCep)
    {
        $this->nuCep = $nuCep;
    }

    /**
     * @return string
     */
    public function getNuCep()
    {
        return $this->nuCep;
    }

    /**
     * @param string $nuDdd
     */
    public function setNuDdd($nuDdd)
    {
        $this->nuDdd = $nuDdd;
    }

    /**
     * @return string
     */
    public function getNuDdd()
    {
        return $this->nuDdd;
    }

    /**
     * @param string $nuTelefone
     */
    public function setNuTelefone($nuTelefone)
    {
        $this->nuTelefone = $nuTelefone;
    }

    /**
     * @return string
     */
    public function getNuTelefone()
    {
        return $this->nuTelefone;
    }

    /**
     * @param string $stRegistroAtivo
     */
    public function setStRegistroAtivo($stRegistroAtivo)
    {
        $this->stRegistroAtivo = $stRegistroAtivo;
    }

    /**
     * @return string
     */
    public function getStRegistroAtivo()
    {
        return $this->stRegistroAtivo;
    }

    /**
     * @param string $tpAgencia
     */
    public function setTpAgencia($tpAgencia)
    {
        $this->tpAgencia = $tpAgencia;
    }

    /**
     * @return string
     */
    public function getTpAgencia()
    {
        return $this->tpAgencia;
    }

}
