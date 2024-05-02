<?php

namespace AppBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use AppBundle\Entity\ProjetoPessoa;
use AppBundle\Entity\GrupoAtuacao;

/**
 * ProjetoPessoaGrupoAtuacao
 *
 * @ORM\Table(name="DBPET.RL_PROJETOPESSOA_GRUPOATUACAO")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\ProjetoPessoaGrupoAtuacaoRepository")
 */
class ProjetoPessoaGrupoAtuacao extends AbstractEntity
{
    use \AppBundle\Traits\DeleteLogicoTrait;
    use \AppBundle\Traits\DataInclusaoTrait;
    
    /**
     * @var int
     *
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="SEQUENCE")
     * @ORM\Column(name="CO_SEQ_PROJPES_GRUPOATUAC", type="integer", unique=true)
     * @ORM\SequenceGenerator(sequenceName="DBPET.SQ_PRJPESGRPAT_COSEQPRJPESGPAT", allocationSize=1, initialValue=1)
     */
    private $coSeqProjpesGrupoatuac;

    /**
     * @var ProjetoPessoa
     * 
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\ProjetoPessoa", inversedBy="projetoPessoaGrupoAtuacao")
     * @ORM\JoinColumn(name="CO_PROJETO_PESSOA", referencedColumnName="CO_SEQ_PROJETO_PESSOA")
     */
    private $projetoPessoa;

    /**
     * @var GrupoAtuacao
     *
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\GrupoAtuacao", inversedBy="projetoPessoaGrupoAtuacao")
     * @ORM\JoinColumn(name="CO_GRUPO_ATUACAO", referencedColumnName="CO_SEQ_GRUPO_ATUACAO")
     */
    private $grupoAtuacao;

    /**
     * @var ArrayCollection<ProjetoPessoaGrupoAtuacaoAreaTematica>
     *
     * @ORM\OneToMany(targetEntity="AppBundle\Entity\ProjetoPessoaGrupoAtuacaoAreaTematica", mappedBy="projetoPessoaGrupoAtuacao", cascade={"persist"})
     */
    private $projetosPessoasGrupoAtuacaoAreasTematicas;

    /**
     * @param \AppBundle\Entity\ProjetoPessoa $projetoPessoa
     * @param \AppBundle\Entity\GrupoAtuacao $grupoAtuacao
     * @return ProjetoPessoaGrupoAtuacao
     * @throws \Exception
     */
    public static function create(
        ProjetoPessoa $projetoPessoa,
        GrupoAtuacao $grupoAtuacao
    ) {
        return new self($projetoPessoa, $grupoAtuacao);
    }

    /**
     * ProjetoPessoaGrupoAtuacao constructor.
     * @param \AppBundle\Entity\ProjetoPessoa $projetoPessoa
     * @param \AppBundle\Entity\GrupoAtuacao $grupoAtuacao
     * @throws \Exception
     */
    public function __construct(
        ProjetoPessoa $projetoPessoa,
        GrupoAtuacao $grupoAtuacao
    ) {
        $this->projetoPessoa = $projetoPessoa;
        $this->grupoAtuacao  = $grupoAtuacao;
        $this->stRegistroAtivo = 'S';
        $this->dtInclusao = new \DateTime();
        $this->projetosPessoasGrupoAtuacaoAreasTematicas = new ArrayCollection();
    }

    /**
     * Get getCoSeqProjetoPessoaGrupoAtuacao
     *
     * @return int
     */
    public function getCoSeqProjpesGrupoatuac()
    {
        return $this->coSeqProjpesGrupoatuac;
    }

    /**
     * Get coProjetoPessoa
     *
     * @return ProjetoPessoa
     */
    public function getProjetoPessoa()
    {
        return $this->projetoPessoa;
    }

    /**
     * Get grupoAtuacao
     *
     * @return GrupoAtuacao
     */
    public function getGrupoAtuacao()
    {
        return $this->grupoAtuacao;
    }

    /**
     * @return ArrayCollection<ProjetoPessoaGrupoAtuacaoAreaTematica>
     */
    public function getProjetosPessoasGrupoAtuacaoAreasTematicas()
    {
        return $this->projetosPessoasGrupoAtuacaoAreasTematicas;
    }

    /**
     * @return ArrayCollection<ProjetoPessoaGrupoAtuacaoAreaTematica>
     */
    public function getProjetosPessoasGrupoAtuacaoAreasTematicasAtivos()
    {
        return $this->projetosPessoasGrupoAtuacaoAreasTematicas->filter(
            function (ProjetoPessoaGrupoAtuacaoAreaTematica $projetoPessoaGrupoAtuacaoAreaTematica) {
                return $projetoPessoaGrupoAtuacaoAreaTematica->isAtivo();
            }
        );
    }

    /**
     * @param AreaTematica $areaTematica
     */
    public function addProjetoPessoaGrupoAtuacaoAreaTematica(AreaTematica $areaTematica)
    {
        if ($projetoPessoaGrupoAtuacaoAreaTematica = $this->getProjetoPessoaGrupoAtuacaoAreaTematicaByAreaTematica($areaTematica)) {
            $projetoPessoaGrupoAtuacaoAreaTematica->ativar();
        } else {
            $this->projetosPessoasGrupoAtuacaoAreasTematicas->add(
                new ProjetoPessoaGrupoAtuacaoAreaTematica($this, $areaTematica)
            );
        }
    }

    public function inativarAllProjetoPessoaGrupoAtuacaoAreaTematica()
    {
        foreach ($this->projetosPessoasGrupoAtuacaoAreasTematicas as $projetosPessoasGrupoAtuacaoAreasTematica) {
            $projetosPessoasGrupoAtuacaoAreasTematica->inativar();
        }
    }

    /**
     * @param AreaTematica $areaTematica
     * @return ProjetoPessoaGrupoAtuacaoAreaTematica|null
     */
    public function getProjetoPessoaGrupoAtuacaoAreaTematicaByAreaTematica(AreaTematica $areaTematica)
    {
        return $this->projetosPessoasGrupoAtuacaoAreasTematicas->filter(
            function (ProjetoPessoaGrupoAtuacaoAreaTematica $projetoPessoaGrupoAtuacaoAreaTematica) use ($areaTematica) {
                return $projetoPessoaGrupoAtuacaoAreaTematica->getAreaTematica() == $areaTematica;
            }
        )->first();
    }

    /**
     * @return string
     */
    public function getJoinedAreasTematicas()
    {
        $aresTematicas = $this->getProjetosPessoasGrupoAtuacaoAreasTematicasAtivos()->map(
            function (ProjetoPessoaGrupoAtuacaoAreaTematica $projetoPessoaGrupoAtuacaoAreaTematica) {
                return $projetoPessoaGrupoAtuacaoAreaTematica->getAreaTematica()->getTipoAreaTematica()->getDsTipoAreaTematica();
            }
        )->toArray();

        return implode(', ', $aresTematicas);
    }

    public function getDescricaoCursoGraducao()
    {
        $cursos = $this->getProjetoPessoa()->getCursoGraduacaoEstudanteOuPreceptor();
        if( $cursos  ) {
            return  $cursos->getDsCursoGraduacao();
        }

        $dadoAcademico = $this->getProjetoPessoa()->getDadoAcademicoAtivo();
        if( $dadoAcademico ) {
            return $dadoAcademico->getCategoriaProfissional()->getDsCategoriaProfissional();
        }

        return '';

    }
}

