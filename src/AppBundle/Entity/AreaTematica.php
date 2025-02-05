<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * AreaTematica
 *
 * @ORM\Table(name="DBPET.TB_AREA_TEMATICA")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\AreaTematicaRepository")
 */
class AreaTematica extends AbstractEntity
{
    use \AppBundle\Traits\DeleteLogicoTrait;
    use \AppBundle\Traits\DataInclusaoTrait;
    
    /**
     * @var int
     *
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="SEQUENCE")
     * @ORM\Column(name="CO_SEQ_AREA_TEMATICA", type="integer")
     * @ORM\SequenceGenerator(sequenceName="DBPET.SQ_AREATEMAT_COSEQAREATEMATICA", allocationSize=1, initialValue=1)
     */
    private $coSeqAreaTematica;

    /**
     * @var TipoAreaTematica
     *
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\TipoAreaTematica")
     * @ORM\JoinColumn(name="CO_TIPO_AREA_TEMATICA", referencedColumnName="CO_SEQ_TIPO_AREA_TEMATICA")
     */
    private $tipoAreaTematica;

    /**
     * @var Projeto
     *
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Projeto", inversedBy="areasTematicas")
     * @ORM\JoinColumn(name="CO_PROJETO", referencedColumnName="CO_SEQ_PROJETO")
     */
    private $projeto;
    
    /**
     * @var AreaTematicaGrupoAtuacao
     * 
     * @ORM\OneToMany(targetEntity="AppBundle\Entity\AreaTematicaGrupoAtuacao", mappedBy="areaTematica")
     */
    private $areasTematicasGruposAtuacao;

    /**
     * @param TipoAreaTematica $tipoAreaTematica
     * @param Projeto $projeto
     */
    public function __construct(TipoAreaTematica $tipoAreaTematica, Projeto $projeto)
    {
        $this->tipoAreaTematica = $tipoAreaTematica;
        $this->projeto = $projeto;
        $this->dtInclusao = new \DateTime();
        $this->stRegistroAtivo = 'S';
        $this->areasTematicasGruposAtuacao = new ArrayCollection();
    }
    
    /**
     * Get coSeqAreaTematica
     *
     * @return int
     */
    public function getCoSeqAreaTematica()
    {
        return $this->coSeqAreaTematica;
    }

    /**
     * Get tipoAreaTematica
     *
     * @return TipoAreaTematica
     */
    public function getTipoAreaTematica()
    {
        return $this->tipoAreaTematica;
    }

    /**
     * Get projeto
     *
     * @return Projeto
     */
    public function getProjeto()
    {
        return $this->projeto;
    }
    
    /**
     * Get areasTematicasGruposAtuacao
     * 
     * @return AreaTematicaGrupoAtuacao
     */
    public function getAreasTematicasGruposAtuacao()
    {
        return $this->areasTematicasGruposAtuacao;
    }
    
    /**
     * Get areasTematicasGruposAtuacao
     * 
     * @return AreaTematicaGrupoAtuacao
     */
    public function getAreasTematicasGruposAtuacaoAtivas()
    {
        return $this->areasTematicasGruposAtuacao->filter(function ($item) {
            return $this->isAtivo() && $item->isAtivo() && $item->getGrupoAtuacao()->isAtivo();
        });
    }
}