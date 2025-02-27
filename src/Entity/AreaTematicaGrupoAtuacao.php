<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * AreaTematicaGrupoAtuacao
 *
 * @ORM\Table(name="DBPETINFOSD.RL_AREATEMATICA_GRUPOATUACAO")
 * @ORM\Entity(repositoryClass="App\Repository\AreaTematicaGrupoAtuacaoRepository")
 */
class AreaTematicaGrupoAtuacao extends AbstractEntity
{
    use \App\Traits\DeleteLogicoTrait;
    use \App\Traits\DataInclusaoTrait;
    
    /**
     * @var int
     *
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="SEQUENCE")
     * @ORM\Column(name="CO_SEQ_AREATEM_GRUPOATUACAO", type="integer", unique=true)
     * @ORM\SequenceGenerator(sequenceName="DBPETINFOSD.SQ_AREATEMAT_COSEQAREATEMATICA", allocationSize=1, initialValue=1)
     */
    private $coSeqAreatemGrupoatuacao;

    /**
     * @var GrupoAtuacao
     *
     * @ORM\ManyToOne(targetEntity="App\Entity\GrupoAtuacao", inversedBy="areasTematicasGruposAtuacao")
     * @ORM\JoinColumn(name="CO_GRUPO_ATUACAO", referencedColumnName="CO_SEQ_GRUPO_ATUACAO")
     */
    private $grupoAtuacao;

    /**
     * @var AreaTematica
     *
     * @ORM\ManyToOne(targetEntity="App\Entity\AreaTematica", inversedBy="areasTematicasGruposAtuacao")
     * @ORM\JoinColumn(name="CO_AREA_TEMATICA", referencedColumnName="CO_SEQ_AREA_TEMATICA")
     */
    private $areaTematica;

    /**
     * @param GrupoAtuacao $grupoAtuacao
     * @param AreaTematica $areaTematica
     */
    public function __construct($grupoAtuacao, $areaTematica)
    {
        $this->grupoAtuacao = $grupoAtuacao;
        $this->areaTematica = $areaTematica;
        $this->stRegistroAtivo = 'S';
        $this->dtInclusao = new \DateTime();
    }
    
    /**
     * Get coSeqAreatemGrupoatuacao
     *
     * @return int
     */
    public function getCoSeqAreatemGrupoatuacao()
    {
        return $this->coSeqAreatemGrupoatuacao;
    }

    /**
     * Get coGrupoAtuacao
     *
     * @return int
     */
    public function getGrupoAtuacao()
    {
        return $this->grupoAtuacao;
    }

    /**
     * Get coAreaTematica
     *
     * @return int
     */
    public function getAreaTematica()
    {
        return $this->areaTematica;
    }


}

