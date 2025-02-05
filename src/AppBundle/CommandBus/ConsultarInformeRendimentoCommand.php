<?php
/**
 * Created by PhpStorm.
 * User: pauloe.oliveira
 * Date: 22/03/17
 * Time: 11:17
 */

namespace AppBundle\CommandBus;

use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Context\ExecutionContextInterface;
use AppBundle\Entity\FolhaPagamento;

class ConsultarInformeRendimentoCommand
{
    /**
     * @var integer
     * @Assert\NotBlank( message = "É obrigatório o preenchimento do campo: - Programa" )
     */
    protected $publicacao;

    /**
     * @var integer
     * @Assert\NotBlank( message = "É obrigatório o preenchimento do campo: - Ano Base" )
     */
    protected $nuAnoBase;

    /**
     * @var string
     * @Assert\NotBlank( message = "É obrigatório o preenchimento do campo: - Data de Nascimento" )
     */
    protected $dtNascimento;

    /**
     * @var string
     * @Assert\NotBlank( message = "É obrigatório o preenchimento do campo: - CPF" )
     */
    protected $nuCpf;

    /**
     * @param string $publicacao
     * @return ConsultarInformeRendimentoCommand
     */
    public function setPublicacao($publicacao)
    {
        $this->publicacao = $publicacao;
        return $this;
    }

    /**
     * @param integer $nuAnoBase
     * @return ConsultarInformeRendimentoCommand
     */
    public function setNuAnoBase(FolhaPagamento $folhaPagamento)
    {
        $this->nuAnoBase = $folhaPagamento->getNuAno();
        return $this;
    }

    /**
     * @param \DateTime $dtNascimento
     * @return ConsultarInformeRendimentoCommand
     */
    public function setDtNascimento($dtNascimento)
    {
        $this->dtNascimento = $dtNascimento;
        return $this;
    }

    /**
     * @param string $nuCpf
     * @return ConsultarInformeRendimentoCommand
     */
    public function setNuCpf($nuCpf)
    {
        $this->nuCpf = $nuCpf;
        return $this;
    }

    /**
     * @return string
     */
    public function getPublicacao()
    {
        return $this->publicacao;
    }

    /**
     * @return integer
     */
    public function getNuAnoBase()
    {
        return $this->nuAnoBase;
    }

    /**
     * @return \DateTime
     */
    public function getDtNascimento()
    {
        return $this->dtNascimento;
    }

    /**
     * @return string
     */
    public function getNuCpf()
    {
        return preg_replace("/[^0-9]/", '', $this->nuCpf);
    }
}
