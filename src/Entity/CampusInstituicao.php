<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * CampusInstituicao
 *
 * @ORM\Table(name="DBPETINFOSD.TB_CAMPUS_INSTITUICAO")
 * @ORM\Entity(repositoryClass="App\Repository\CampusInstituicaoRepository")
 */
class CampusInstituicao extends AbstractEntity
{
    use \App\Traits\DeleteLogicoTrait;
    use \App\Traits\DataInclusaoTrait;    
    
    /**
     * @var int
     *
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="SEQUENCE")
     * @ORM\Column(name="CO_SEQ_CAMPUS_INSTITUICAO", type="integer")
     * @ORM\SequenceGenerator(sequenceName="DBPETINFOSD.SQ_CAMPUSINST_COSEQCAMPUSINSTI", allocationSize=1, initialValue=1)
     */
    private $coSeqCampusInstituicao;

    /**
     * @var Instituicao
     *
     * @ORM\ManyToOne(targetEntity="App\Entity\Instituicao")
     * @ORM\JoinColumn(name="CO_INSTITUICAO", referencedColumnName="CO_SEQ_INSTITUICAO")
     */
    private $instituicao;

    /**
     * @var string
     *
     * @ORM\Column(name="NO_CAMPUS", type="string", length=100)
     */
    private $noCampus;

    /**
     * @var Municipio
     *
     * @ORM\ManyToOne(targetEntity="App\Entity\Municipio")
     * @ORM\JoinColumn(name="CO_MUNICIPIO_IBGE", referencedColumnName="CO_MUNICIPIO_IBGE")
     */
    private $municipio;

    /**
     * @param Instituicao $instituicao
     * @param string $noCampus
     * @param Municipio $municipio
     */
    public function __construct(Instituicao $instituicao, $noCampus, Municipio $municipio)
    {
        $this->instituicao = $instituicao;
        $this->noCampus = $noCampus;
        $this->municipio = $municipio;
        $this->stRegistroAtivo = 'S';
        $this->dtInclusao = new \DateTime();
    }
    
    /**
     * Get coSeqCampusInstituicao
     *
     * @return int
     */
    public function getCoSeqCampusInstituicao()
    {
        return $this->coSeqCampusInstituicao;
    }

    /**
     * Get instituicao
     *
     * @return Instituicao
     */
    public function getInstituicao()
    {
        return $this->instituicao;
    }

    /**
     * Get noCampus
     *
     * @return string
     */
    public function getNoCampus()
    {
        return $this->noCampus;
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
     * @return string
     */
    public function getStRegistroAtivo()
    {
        return $this->stRegistroAtivo;
    }

    /**
     * @param string $stRegistroAtivo
     */
    public function setStRegistroAtivo($stRegistroAtivo)
    {
        $this->stRegistroAtivo = $stRegistroAtivo;
    }

    /**
     * @param Municipio $municipio
     */
    public function setMunicipio($municipio)
    {
        $this->municipio = $municipio;
    }

    /**
     * @param Instituicao $instituicao
     */
    public function setInstituicao($instituicao)
    {
        $this->instituicao = $instituicao;
    }

    /**
     * @param string $noCampus
     */
    public function setNoCampus($noCampus)
    {
        $this->noCampus = $noCampus;
    }
}

