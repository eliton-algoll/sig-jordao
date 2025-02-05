<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Banco
 *
 * @ORM\Table(name="DBPET.TB_BANCO")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\BancoRepository")
 */
class Banco extends AbstractEntity
{
    CONST BB = '001';

    /**
     * @var string
     *
     * @ORM\Column(name="CO_BANCO", type="string", nullable=false)
     * @ORM\Id
     */
    private $coBanco;

    /**
     * @var string
     *
     * @ORM\Column(name="NO_BANCO", type="string")
     */
    private $noBanco;

    /**
     * @var string
     *
     * @ORM\Column(name="SG_BANCO", type="string")
     */
    private $sgBanco;

    /**
     * @var string
     *
     * @ORM\Column(name="ST_CONVENIO_FNS", type="string")
     */
    private $stConvenioFns;

    /**
     * @var string
     *
     * @ORM\Column(name="ST_REGISTRO_ATIVO", type="string")
     */
    private $stRegistroAtivo;

    /**
     * @var string
     *
     * @ORM\Column(name="DS_SITE", type="string")
     */
    private $dsSite;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="DT_REMOCAO", type="datetime", nullable=true)
     * @Assert\Type("\DateTime")
     */
    private $dtRemocao;

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

    /**
     * @param string $dsSite
     */
    public function setDsSite($dsSite)
    {
        $this->dsSite = $dsSite;
    }

    /**
     * @return string
     */
    public function getDsSite()
    {
        return $this->dsSite;
    }

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

    /**
     * @param string $sgBanco
     */
    public function setSgBanco($sgBanco)
    {
        $this->sgBanco = $sgBanco;
    }

    /**
     * @return string
     */
    public function getSgBanco()
    {
        return $this->sgBanco;
    }

    /**
     * @param string $stConvenioFns
     */
    public function setStConvenioFns($stConvenioFns)
    {
        $this->stConvenioFns = $stConvenioFns;
    }

    /**
     * @return string
     */
    public function getStConvenioFns()
    {
        return $this->stConvenioFns;
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
     * @param \DateTime $dtRemocao
     * @return $this
     */
    public function setDtRemocao(\DateTime $dtRemocao)
    {
        $this->dtRemocao = $dtRemocao;
        return $this;
    }

    /**
     * @return \DateTime
     */
    public function getDtRemocao()
    {
        return $this->dtRemocao;
    }

}
