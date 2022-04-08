<?php

namespace AppBundle\CommandBus;

use AppBundle\Entity\Banco;
use Symfony\Component\Validator\Constraints as Assert;

final class CadastrarBancoCommand
{
    /**
     *
     * @var Banco
     */
    private $banco;

    /**
     * @var string
     *
     * @Assert\NotBlank()
     */
    private $coBanco;

    /**
     * @var string
     *
     * @Assert\NotBlank()
     */
    private $noBanco;

//    /**
//     * @var string
//     *
//     * @Assert\NotBlank()
//     */
//    private $sgBanco;

//    /**
//     * @var string
//     *
//     * @Assert\NotBlank()
//     */
//    private $stConvenioFns;

    /**
     * @var string
     *
     * @Assert\NotBlank()
     */
    private $stRegistroAtivo;

//    /**
//     * @var string
//     */
//    private $dsSite;

    /**
     * CadastrarBancoCommand constructor.
     * @param Banco $banco
     */
    public function __construct(Banco $banco = null)
    {
        if ($banco !== null) {
            $this->setValuesFromEntity($banco);
        }
    }

    /**
     *
     * @return Banco
     */
    public function getBanco()
    {
        return $this->banco;
    }

    /**
     *
     * @param Banco $banco
     */
    public function setBanco(Banco $banco)
    {
        $this->banco = $banco;
    }

    /**
     * @param int $coBanco
     */
    public function setCoBanco($coBanco)
    {
        $this->coBanco = $coBanco;
    }

    /**
     * @return int
     */
    public function getCoBanco()
    {
        return $this->coBanco;
    }

//    /**
//     * @param string $dsSite
//     */
//    public function setDsSite($dsSite)
//    {
//        $this->dsSite = $dsSite;
//    }
//
//    /**
//     * @return string
//     */
//    public function getDsSite()
//    {
//        return $this->dsSite;
//    }

    /**
     * @param string $noBanco
     */
    public function setNoBanco($noBanco)
    {
        $this->noBanco = $noBanco;
    }

    /**
     * @return string
     */
    public function getNoBanco()
    {
        return $this->noBanco;
    }

//    /**
//     * @param string $sgBanco
//     */
//    public function setSgBanco($sgBanco)
//    {
//        $this->sgBanco = $sgBanco;
//    }
//
//    /**
//     * @return string
//     */
//    public function getSgBanco()
//    {
//        return $this->sgBanco;
//    }

//    /**
//     * @param string $stConvenioFns
//     */
//    public function setStConvenioFns($stConvenioFns)
//    {
//        $this->stConvenioFns = $stConvenioFns;
//    }
//
//    /**
//     * @return string
//     */
//    public function getStConvenioFns()
//    {
//        return $this->stConvenioFns;
//    }

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
     * @param Banco $banco
     */
    private function setValuesFromEntity(Banco $banco)
    {
        $this->setCoBanco($banco->getCoBanco());
        $this->setNoBanco($banco->getNoBanco());
        $this->setStRegistroAtivo($banco->getStRegistroAtivo());
    }

}
