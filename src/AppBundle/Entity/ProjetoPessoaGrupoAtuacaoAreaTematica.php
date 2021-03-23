<?php

namespace AppBundle\Entity;

use AppBundle\Traits\DataInclusaoTrait;
use AppBundle\Traits\DeleteLogicoTrait;
use Doctrine\ORM\Mapping as ORM;

/**
 * Class ProjetoPessoaGrupoAtuacaoAreaTematica
 * @package AppBundle\Entity
 *
 * @ORM\Entity
 * @ORM\Table(name="DBPET.RL_PROJPES_GRPATUAC_AREATEM")
 */
class ProjetoPessoaGrupoAtuacaoAreaTematica
{
    use DataInclusaoTrait;
    use DeleteLogicoTrait;

    /**
     * @var int
     *
     * @ORM\Id
     * @ORM\Column(name="CO_SEQ_PROJPES_GRPATUAC_ARTEM", type="integer")
     * @ORM\GeneratedValue(strategy="SEQUENCE")
     * @ORM\SequenceGenerator(sequenceName="DBPET.SQ_PJPESGRPATAT_COSEQPJPESGPAT", initialValue=1, allocationSize=1)
     */
    private $coSeqProjpesGrpatuacArtem;

    /**
     * @var ProjetoPessoaGrupoAtuacao
     *
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\ProjetoPessoaGrupoAtuacao")
     * @ORM\JoinColumn(name="CO_PROJPES_GRUPOATUAC", referencedColumnName="CO_SEQ_PROJPES_GRUPOATUAC", nullable=false)
     */
    private $projetoPessoaGrupoAtuacao;

    /**
     * @var AreaTematica
     *
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\AreaTematica")
     * @ORM\JoinColumn(name="CO_AREA_TEMATICA", referencedColumnName="CO_SEQ_AREA_TEMATICA", nullable=false)
     */
    private $areaTematica;

    /**
     * ProjetoPessoaGrupoAtuacaoAreaTematica constructor.
     * @param ProjetoPessoaGrupoAtuacao $projetoPessoaGrupoAtuacao
     * @param AreaTematica $areaTematica
     */
    public function __construct(ProjetoPessoaGrupoAtuacao $projetoPessoaGrupoAtuacao, AreaTematica $areaTematica)
    {
        $this->projetoPessoaGrupoAtuacao = $projetoPessoaGrupoAtuacao;
        $this->areaTematica = $areaTematica;
        $this->stRegistroAtivo = 'S';
        $this->dtInclusao = new \DateTime();
    }

    /**
     * @return int
     */
    public function getCoSeqProjpesGrpatuacArtem()
    {
        return $this->coSeqProjpesGrpatuacArtem;
    }

    /**
     * @return ProjetoPessoaGrupoAtuacao
     */
    public function getProjetoPessoaGrupoAtuacao()
    {
        return $this->projetoPessoaGrupoAtuacao;
    }

    /**
     * @return AreaTematica
     */
    public function getAreaTematica()
    {
        return $this->areaTematica;
    }
}