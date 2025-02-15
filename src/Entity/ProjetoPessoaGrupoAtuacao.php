<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use App\Entity\ProjetoPessoa;
use App\Entity\GrupoAtuacao;

/**
 * ProjetoPessoaGrupoAtuacao
 *
 * @ORM\Table(name="DBPET.RL_PROJETOPESSOA_GRUPOATUACAO")
 * @ORM\Entity(repositoryClass="App\Repository\ProjetoPessoaGrupoAtuacaoRepository")
 */
class ProjetoPessoaGrupoAtuacao extends AbstractEntity
{
    use \App\Traits\DeleteLogicoTrait;
    use \App\Traits\DataInclusaoTrait;
    
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
     * @ORM\ManyToOne(targetEntity="App\Entity\ProjetoPessoa", inversedBy="projetoPessoaGrupoAtuacao")
     * @ORM\JoinColumn(name="CO_PROJETO_PESSOA", referencedColumnName="CO_SEQ_PROJETO_PESSOA")
     */
    private $projetoPessoa;

    /**
     * @var GrupoAtuacao
     *
     * @ORM\ManyToOne(targetEntity="App\Entity\GrupoAtuacao", inversedBy="projetoPessoaGrupoAtuacao")
     * @ORM\JoinColumn(name="CO_GRUPO_ATUACAO", referencedColumnName="CO_SEQ_GRUPO_ATUACAO")
     */
    private $grupoAtuacao;

    /**
     * @var ArrayCollection<ProjetoPessoaGrupoAtuacaoAreaTematica>
     *
     * @ORM\OneToMany(targetEntity="App\Entity\ProjetoPessoaGrupoAtuacaoAreaTematica", mappedBy="projetoPessoaGrupoAtuacao", cascade={"persist"})
     */
    private $projetosPessoasGrupoAtuacaoAreasTematicas;

    /**
     * @param \App\Entity\ProjetoPessoa $projetoPessoa
     * @param \App\Entity\GrupoAtuacao $grupoAtuacao
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
     * @param \App\Entity\ProjetoPessoa $projetoPessoa
     * @param \App\Entity\GrupoAtuacao $grupoAtuacao
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

    public function getStRegistroAtivo()
    {
        return $this->stRegistroAtivo;
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

