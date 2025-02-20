<?php

namespace App\Entity;

use App\Validator\Constraints\DateTime;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * DadoPessoal
 *
 * @ORM\Table(name="DBPETINFOSD.TB_DADO_PESSOAL")
 * @ORM\Entity(repositoryClass="App\Repository\DadoPessoalRepository")
 */
class DadoPessoal extends AbstractEntity
{
    use \App\Traits\DataInclusaoTrait;
    
    /**
     * @var int
     * 
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="SEQUENCE")
     * @ORM\Column(name="CO_SEQ_DADO_PESSOAL", type="integer", unique=true)
     * @ORM\SequenceGenerator(sequenceName="DBPETINFOSD.SQ_DADOPESSOAL_COSEQDADOPESSOA", allocationSize=1, initialValue=1)
     */
    private $coSeqDadoPessoal;

    /**
     * @var PessoaFisica
     *
     * @ORM\OneToOne(targetEntity="App\Entity\PessoaFisica")
     * @ORM\JoinColumn(name="NU_CPF", referencedColumnName="NU_CPF")
     */
    private $pessoaFisica;

    /**
     * @var string
     *
     * @ORM\Column(name="CO_AGENCIA", type="string", nullable=false)
     */
    private $agencia;

    /**
     * @var Banco
     *
     * @ORM\ManyToOne(targetEntity="App\Entity\Banco")
     * @ORM\JoinColumn(name="CO_BANCO", referencedColumnName="CO_BANCO", nullable=false)
     */
    private $banco;

    /**
     * @var string
     *
     * @ORM\Column(name="CO_CONTA", type="string", nullable=true)
     */
    private $conta;

    /**
     * @param PessoaFisica $pessoaFisica
     * @param $agencia
     * @param $banco
     */
    public function __construct(
        PessoaFisica $pessoaFisica,
        $banco = null,
        $agencia = null,
        $conta = null
    ) {
        $this->pessoaFisica     = $pessoaFisica;
        $this->banco            = $banco;
        $this->agencia          = $agencia;
        $this->conta            = $conta;
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

    public function getDtNascimento() {
        $this->getPessoaFisica()->getDtNascimento();
    }

    /**
     * Get conta
     *
     * @return string
     */
    public function getConta()
    {
        return $this->conta;
    }

    /**
     * Get agencia
     *
     * @return string
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
     * @param string $agencia
     * @return DadoPessoal
     */
    public function setAgencia($agencia)
    {
        $this->agencia = $agencia;
        return $this;
    }

    /**
     * @param $banco
     * @return DadoPessoal
     */
    public function setBanco($banco)
    {
        $this->banco = $banco;
        return $this;
    }

    /**
     * @param string $conta
     * @return DadoPessoal
     */
    public function setConta($conta)
    {
        $this->conta = $conta;
        return $this;
    }

}
