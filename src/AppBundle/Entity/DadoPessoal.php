<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * DadoPessoal
 *
 * @ORM\Table(name="DBPET.TB_DADO_PESSOAL")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\DadoPessoalRepository")
 */
class DadoPessoal extends AbstractEntity
{
    use \AppBundle\Traits\DataInclusaoTrait;
    
    /**
     * @var int
     * 
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="SEQUENCE")
     * @ORM\Column(name="CO_SEQ_DADO_PESSOAL", type="integer", unique=true)
     * @ORM\SequenceGenerator(sequenceName="DBPET.SQ_DADOPESSOAL_COSEQDADOPESSOA", allocationSize=1, initialValue=1)
     */
    private $coSeqDadoPessoal;

    /**
     * @var PessoaFisica
     *
     * @ORM\OneToOne(targetEntity="AppBundle\Entity\PessoaFisica")
     * @ORM\JoinColumn(name="NU_CPF", referencedColumnName="NU_CPF")
     */
    private $pessoaFisica;

    /**
     * @var AgenciaBancaria
     *
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\AgenciaBancaria")
     * @ORM\JoinColumn(name="CO_AGENCIA", referencedColumnName="CO_AGENCIA_BANCARIA")
     */
    private $agencia;

    /**
     * @var Banco
     *
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Banco")
     * @ORM\JoinColumn(name="CO_BANCO", referencedColumnName="CO_BANCO")
     */
    private $banco;

    /**
     * @param PessoaFisica $pessoaFisica
     * @param AgenciaBancaria $agencia
     * @param Banco $banco
     */
    public function __construct(
        PessoaFisica $pessoaFisica, 
        AgenciaBancaria $agencia = null, 
        Banco $banco = null
    ) {
        $this->pessoaFisica     = $pessoaFisica;
        $this->agencia          = $agencia;
        $this->banco            = $banco;
        $this->stRegistroAtivo  = 'S';
        $this->dtInclusao       = new \DateTime();
    }

    /**
     * Get coSeqDadoPessoal
     *
     * @return int
     */
    public function getCoSeqDadoPessoal()
    {
        return $this->coSeqDadoPessoal;
    }

    /**
     * Get PessoaFisica
     *
     * @return PessoaFisica
     */
    public function getPessoaFisica()
    {
        return $this->pessoaFisica;
    }

    /**
     * Get agencia
     *
     * @return AgenciaBancaria
     */
    public function getAgencia()
    {
        return $this->agencia;
    }

    /**
     * Get banco
     *
     * @return string
     */
    public function getBanco()
    {
        return $this->banco;
    }

    /**
     * @param AgenciaBancaria $agencia
     * @return DadoPessoal
     */
    public function setAgencia(AgenciaBancaria $agencia)
    {
        $this->agencia = $agencia;
        return $this;
    }

    /**
     * @param Banco $banco
     * @return DadoPessoal
     */
    public function setBanco(Banco $banco)
    {
        $this->banco = $banco;
        return $this;
    }
}