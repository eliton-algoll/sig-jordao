<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * CboOcupacao
 *
 * @ORM\Table(name="DBGERAL.TB_CBO_OCUPACAO")
 * @ORM\Entity(repositoryClass="App\Repository\CboOcupacaoRepository")
 */
class CboOcupacao extends AbstractEntity
{
    use \App\Traits\DeleteLogicoTrait;
    
    /**
     * @var string
     *
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="SEQUENCE")
     * @ORM\Column(name="CO_CBO_OCUPACAO", type="string", length=6, unique=true)
     * @ORM\SequenceGenerator(sequenceName="DBPETINFOSD.SQ_DADOPESSOAL_COSEQPROFIS", allocationSize=1, initialValue=1)
     */
    private $coCboOcupacao;

    /**
     * @var string
     *
     * @ORM\Column(name="CO_CBO_FAMILIA", type="string", length=4)
     */
    private $coCboFamilia;

    /**
     * @var string
     *
     * @ORM\Column(name="CO_CBO_SUBGRUPO", type="string", length=3, nullable=true)
     */
    private $coCboSubgrupo;

    /**
     * @var string
     *
     * @ORM\Column(name="CO_CBO_SUBGRUPO_PRINCIPAL", type="string", length=2, nullable=true)
     */
    private $coCboSubgrupoPrincipal;

    /**
     * @var string
     *
     * @ORM\Column(name="CO_CBO_GRUPO", type="string", length=1, nullable=true)
     */
    private $coCboGrupo;

    /**
     * @var string
     *
     * @ORM\Column(name="DS_CBO_OCUPACAO", type="string", length=200, nullable=true)
     */
    private $dsCboOcupacao;

    /**
     * @var string
     *
     * @ORM\Column(name="CO_OCUPACAO_CBO94", type="string", length=5, nullable=true)
     */
    private $coOcupacaoCbo94;

    /**
     * @var string
     *
     * @ORM\Column(name="ST_CADSUS", type="string", length=1, nullable=true)
     */
    private $stCadsus;


    /**
     * Get coCboOcupacao
     *
     * @return string
     */
    public function getCoCboOcupacao()
    {
        return $this->coCboOcupacao;
    }

    /**
     * Get coCboFamilia
     *
     * @return string
     */
    public function getCoCboFamilia()
    {
        return $this->coCboFamilia;
    }

    /**
     * Get coCboSubgrupo
     *
     * @return string
     */
    public function getCoCboSubgrupo()
    {
        return $this->coCboSubgrupo;
    }

    /**
     * Get coCboSubgrupoPrincipal
     *
     * @return string
     */
    public function getCoCboSubgrupoPrincipal()
    {
        return $this->coCboSubgrupoPrincipal;
    }

    /**
     * Get coCboGrupo
     *
     * @return string
     */
    public function getCoCboGrupo()
    {
        return $this->coCboGrupo;
    }

    /**
     * Get dsCboOcupacao
     *
     * @return string
     */
    public function getDsCboOcupacao()
    {
        return $this->dsCboOcupacao;
    }

    /**
     * Get coOcupacaoCbo94
     *
     * @return string
     */
    public function getCoOcupacaoCbo94()
    {
        return $this->coOcupacaoCbo94;
    }

    /**
     * Get stCadsus
     *
     * @return string
     */
    public function getStCadsus()
    {
        return $this->stCadsus;
    }
}