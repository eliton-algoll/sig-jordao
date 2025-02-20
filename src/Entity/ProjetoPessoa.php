<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * ProjetoPessoa
 *
 * @ORM\Table(name="DBPETINFOSD.TB_PROJETO_PESSOA")
 * @ORM\Entity(repositoryClass="App\Repository\ProjetoPessoaRepository")
 */
class ProjetoPessoa extends AbstractEntity
{
    use \App\Traits\DeleteLogicoTrait;
    use \App\Traits\DataInclusaoTrait;

    /**
     * @var int
     *
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="SEQUENCE")
     * @ORM\Column(name="CO_SEQ_PROJETO_PESSOA", type="integer")
     * @ORM\SequenceGenerator(sequenceName="DBPETINFOSD.SQ_PROJPESSOA_COSEQPROJPESSOA", allocationSize=1, initialValue=1)
     */
    private $coSeqProjetoPessoa;

    /**
     * @var Projeto
     *
     * @ORM\ManyToOne(targetEntity="App\Entity\Projeto", inversedBy="projetosPessoas")
     * @ORM\JoinColumn(name="CO_PROJETO", referencedColumnName="CO_SEQ_PROJETO")
     */
    private $projeto;

    /**
     * @var PessoaPerfil pessoaPerfil
     *
     * @ORM\ManyToOne(targetEntity="App\Entity\PessoaPerfil", inversedBy="projetosPessoas", cascade={"persist"})
     * @ORM\JoinColumn(name="CO_PESSOA_PERFIL", referencedColumnName="CO_SEQ_PESSOA_PERFIL")
     */
    private $pessoaPerfil;

    /**
     * @var IdentidadeGenero
     *
     * @ORM\OneToOne(targetEntity="App\Entity\IdentidadeGenero", cascade={"persist"})
     * @ORM\JoinColumn(name="CO_IDENTIDADE_GENERO", referencedColumnName="CO_IDENTIDADE_GENERO")
     */
    private $identidadeGenero;

    /**
     * @var string stVoluntarioProjeto
     *
     * @ORM\Column(name="ST_VOLUNTARIO_PROJETO", type="string", nullable=false)
     */
    private $stVoluntarioProjeto;

    /**
     *
     * @var \DateTime
     *
     * @ORM\Column(name="DT_DESLIGAMENTO", type="datetime", nullable=true)
     */
    private $dtDesligamento;

    /**
     * @var string coEixoAtuacao
     *
     * @ORM\Column(name="CO_EIXO_ATUACAO", type="string", length=1, nullable=true)
     */
    private $coEixoAtuacao;

    /**
     *
     * @var string
     *
     * @ORM\Column(name="NO_DOCUMENTO_BANCARIO", type="string", length=200)
     */
    private $noDocumentoBancario;

    /**
     *
     * @var string
     *
     * @ORM\Column(name="NO_DOCUMENTO_MATRICULA", type="string", length=200, nullable=true)
     */
    private $noDocumentoMatricula;

    /**
     * @var ProjetoPessoaGrupoAtuacao
     * @ORM\OneToMany(targetEntity="App\Entity\ProjetoPessoaGrupoAtuacao", mappedBy="projetoPessoa", cascade={"persist"})
     */
    private $projetoPessoaGrupoAtuacao;

    /**
     * @var DadoAcademico
     * @ORM\OneToMany(targetEntity="App\Entity\DadoAcademico", mappedBy="projetoPessoa", cascade={"persist"})
     */
    private $dadosAcademicos;

    /**
     * @var ProjetoPessoaCursoGraduacao
     * @ORM\OneToMany(targetEntity="App\Entity\ProjetoPessoaCursoGraduacao", mappedBy="projetoPessoa", cascade={"persist"})
     */
    private $projetoPessoaCursoGraduacao;

    /**
     * @param Projeto $projeto
     * @param PessoaPerfil $pessoaPerfil
     * @param string $stVoluntarioProjeto
     */
    public function __construct(Projeto $projeto, PessoaPerfil $pessoaPerfil, $stVoluntarioProjeto = 'N', $coEixoAtuacao = null, $genero = null, $filenameBank = null, $filenameMatricula = null)
    {
        $this->projetoPessoaGrupoAtuacao = new ArrayCollection();
        $this->projetoPessoaCursoGraduacao = new ArrayCollection();
        $this->dadosAcademicos = new ArrayCollection();
        $this->projeto = $projeto;
        $this->pessoaPerfil = $pessoaPerfil;
        $this->identidadeGenero = $genero;
        $this->stVoluntarioProjeto = $stVoluntarioProjeto;
        $this->coEixoAtuacao = $coEixoAtuacao;
        $this->noDocumentoBancario = $filenameBank;
        $this->noDocumentoMatricula = $filenameMatricula;
        $this->stRegistroAtivo = 'S';
        $this->dtInclusao = new \DateTime();
    }

    /**
     * Get coSeqProjetoPessoa
     *
     * @return int
     */
    public function getCoSeqProjetoPessoa()
    {
        return $this->coSeqProjetoPessoa;
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
     * Get pesssoaPerfil
     *
     * @return PessoaPerfil
     */
    public function getPessoaPerfil()
    {
        return $this->pessoaPerfil;
    }

    /**
     *
     * @return string
     */
    public function getNoDocumentoBancario()
    {
        return $this->noDocumentoBancario;
    }
    /**
     *
     * @return string
     */
    public function getNoDocumentoMatricula()
    {
        return $this->noDocumentoMatricula;
    }

    /**
     * @return IdentidadeGenero|mixed|null
     */
    public function getIdentidadeGenero()
    {
        return $this->identidadeGenero;
    }

    /**
     * @return string
     */
    public function getStVoluntarioProjeto()
    {
        return $this->stVoluntarioProjeto;
    }

    /**
     *
     * @return \DateTime|null
     */
    public function getDtDesligamento()
    {
        return $this->dtDesligamento;
    }

    /**
     * @return string
     */
    public function getCoEixoAtuacao()
    {
        return $this->coEixoAtuacao;
    }

    /**
     * Get projetoPessoaGrupoAtuacao
     *
     * @return ProjetoPessoaGrupoAtuacao
     */
    public function getProjetoPessoaGrupoAtuacao()
    {
        return $this->projetoPessoaGrupoAtuacao;
    }

    /**
     * Get dadoAcademico
     *
     * @return ArrayCollection<DadoAcademico>
     */
    public function getDadosAcademicos()
    {
        return $this->dadosAcademicos;
    }

    /**
     * Get dadoAcademicoAtivo
     *
     * @return DadoAcademico
     */
    public function getDadoAcademicoAtivo()
    {
        return $this->dadosAcademicos->filter(function ($dadoAcademico) {
            return $dadoAcademico->isAtivo();
        })->first();
    }

    /**
     * Get projetoPessoaGrupoAtuacao
     *
     * @return ArrayCollection<ProjetoPessoaGrupoAtuacao>
     */
    public function getProjetoPessoaGrupoAtuacaoAtivo()
    {
        return $this->projetoPessoaGrupoAtuacao->filter(function ($projetoPessoaGrupoAtuacao) {
            return $projetoPessoaGrupoAtuacao->isAtivo();
        });
    }

    /**
     * Get projetoPessoaCursoGraduacao
     *
     * @return ProjetoPessoaCursoGraduacao
     */
    public function getProjetoPessoaCursoGraduacao()
    {
        return $this->projetoPessoaCursoGraduacao;
    }

    /**
     * Get projetoPessoaCursoGraduacao
     *
     * @return ProjetoPessoaCursoGraduacao
     */
    public function getProjetoPessoaCursoGraduacaoAtivo()
    {
        return $this->projetoPessoaCursoGraduacao->filter(function ($projetoPessoaCursoGraduacao) {
            return $projetoPessoaCursoGraduacao->isAtivo();
        });
    }

    /**
     * Get projetoPessoaCursoGraduacao
     *
     * @return ProjetoPessoaCursoGraduacao
     */
    public function getProjetoPessoaCursoGraduacaoFist()
    {
        return $this->projetoPessoaCursoGraduacao->first();
    }

    /**
     * @param IdentidadeGenero|mixed|null $identidadeGenero
     */
    public function setIdentidadeGenero($identidadeGenero)
    {
        $this->identidadeGenero = $identidadeGenero;
    }

    /**
     *
     * @param string $noDocumentoBancario
     */
    public function setNoDocumentoBancario($noDocumentoBancario)
    {
        $this->noDocumentoBancario = $noDocumentoBancario;
    }
    /**
     *
     * @param string $noDocumentoMatricula
     */
    public function setNoDocumentoMatricula($noDocumentoMatricula)
    {
        $this->noDocumentoMatricula = $noDocumentoMatricula;
    }

    /**
     * @param string $stVoluntarioProjeto
     * @return ProjetoPessoa
     */
    public function setStVoluntarioProjeto($stVoluntarioProjeto)
    {
        $this->stVoluntarioProjeto = $stVoluntarioProjeto;
        return $this;
    }

    /**
     * @param string $coEixoAtuacao
     * @return ProjetoPessoa
     */
    public function setCoEixoAtuacao($coEixoAtuacao)
    {
        $this->coEixoAtuacao = $coEixoAtuacao;
        return $this;
    }

    public function inativar()
    {
        $this->stRegistroAtivo = 'N';
        $this->dtDesligamento = new \DateTime();
    }

    public function isAtivo()
    {
        return $this->stRegistroAtivo == 'S';
    }

    /**
     * @param GrupoAtuacao $grupoAtuacao
     * @return boolean
     */
    public function hasGrupoAtuacao(GrupoAtuacao $grupoAtuacao)
    {
        foreach ($this->getProjetoPessoaGrupoAtuacaoAtivo() as $projetoPessoaGrupoAtuacao) {
            if ($projetoPessoaGrupoAtuacao->getGrupoAtuacao() == $grupoAtuacao) {
                return true;
            }
        }
        return false;
    }

    public function isGrupoAtuacaoVinculado(GrupoAtuacao $grupoAtuacao)
    {
        foreach ($this->projetoPessoaGrupoAtuacao as $projetoPessoaGrupoAtuacao) {
            if ($projetoPessoaGrupoAtuacao->getGrupoAtuacao() == $grupoAtuacao) {
                return $projetoPessoaGrupoAtuacao;
            }
        }
        return false;
    }

    /**
     * @param GrupoAtuacao $grupoAtuacao
     * @return ProjetoPessoa
     */
    public function addGrupoAtuacao(GrupoAtuacao $grupoAtuacao)
    {
        if ($projetoPessoaGrupoAtuacao = $this->isGrupoAtuacaoVinculado($grupoAtuacao)) {
            $projetoPessoaGrupoAtuacao->ativar();
            $grupoAtuacaoVinculado = $projetoPessoaGrupoAtuacao->getGrupoAtuacao()->ativar();
        } else {
            $grupoAtuacaoVinculado = new ProjetoPessoaGrupoAtuacao($this, $grupoAtuacao);
            $this->projetoPessoaGrupoAtuacao->add($grupoAtuacaoVinculado);
        }

        return $grupoAtuacaoVinculado;
    }

    /**
     *
     * @param \App\Entity\CursoGraduacao $cursoGraduacao
     * @return boolean
     */
    public function hasCursoGraduacao(CursoGraduacao $cursoGraduacao)
    {
        foreach ($this->projetoPessoaCursoGraduacao as $projetoPessoaCursoGraduacao) {
            if ($projetoPessoaCursoGraduacao->getCursoGraduacao() == $cursoGraduacao) {
                return true;
            }
        }
        return false;
    }

    public function inativarAllProjetoPessoaCursoGraduacao()
    {
        foreach ($this->projetoPessoaCursoGraduacao as $projetoPessoaCursoGraduacao) {
            $projetoPessoaCursoGraduacao->inativar();
        }
    }

    public function isCursoGraduacaoVinculado(CursoGraduacao $cursoGraduacao)
    {
        foreach ($this->projetoPessoaCursoGraduacao as $projetoPessoaCursoGraduacao) {
            if ($projetoPessoaCursoGraduacao->getCursoGraduacao() == $cursoGraduacao) {
                return $projetoPessoaCursoGraduacao;
            }
        }
        return false;
    }

    /**
     * @param CursoGraduacao $cursoGraduacao
     * @return ProjetoPessoa
     */
    public function addCursoGraduacao(CursoGraduacao $cursoGraduacao)
    {
        if ($projetoPessoaCursoGraduacao = $this->isCursoGraduacaoVinculado($cursoGraduacao)) {
            $projetoPessoaCursoGraduacao->ativar();
            $cursoGraduacaoVinculado = $projetoPessoaCursoGraduacao->getCursoGraduacao()->ativar();
        } else {
            $cursoGraduacaoVinculado = new ProjetoPessoaCursoGraduacao($this, $cursoGraduacao);
            $this->projetoPessoaCursoGraduacao->add($cursoGraduacaoVinculado);
        }

        return $cursoGraduacaoVinculado;
    }

    /**
     * @param GrupoAtuacao $grupoAtuacao
     * @param AreaTematica $areaTematica
     * @throws \Exception
     */
    public function addGrupoTutorial(GrupoAtuacao $grupoAtuacao, $coEixoAtuacao = null)
    {
        if (!is_null($coEixoAtuacao) && $grupoAtuacao->qtdPreceptoresBolsistas()==0) {
            $grupoAtuacao->setCoEixoAtuacao($coEixoAtuacao);
        }
 
        if ($projetoPessoaGrupoAtuacao = $this->getProjetoPessoaGrupoAtuacaoByGrupoAtuacao($grupoAtuacao)) {
            $this->setCoEixoAtuacao($coEixoAtuacao);
            $projetoPessoaGrupoAtuacao->ativar();
        } else {
            $this->projetoPessoaGrupoAtuacao->add(ProjetoPessoaGrupoAtuacao::create($this, $grupoAtuacao));
        }
    }

    /**
     * @param GrupoAtuacao $grupoAtuacao
     * @param AreaTematica $areaTematica
     * @return ProjetoPessoaGrupoAtuacao|null
     */
    public function getProjetoPessoaGrupoAtuacaoByGrupoAtuacao(GrupoAtuacao $grupoAtuacao)
    {
        return $this->projetoPessoaGrupoAtuacao->filter(function (ProjetoPessoaGrupoAtuacao $projetoPessoaGrupoAtuacao) use ($grupoAtuacao) {
            return $projetoPessoaGrupoAtuacao->getGrupoAtuacao() == $grupoAtuacao;
        })->first();
    }

    /**
     * @return ArrayCollection<ProjetoPessoaGrupoAtuacao>
     */
    public function getProjetoPessoaGrupoAtuacaoAtivos()
    {
        return $this->projetoPessoaGrupoAtuacao->filter(
            function (ProjetoPessoaGrupoAtuacao $projetoPessoaGrupoAtuacao) {
                return $projetoPessoaGrupoAtuacao->isAtivo();
            }
        );
    }

    public function inativarAllDadosAcademicos()
    {
        foreach ($this->dadosAcademicos as $dadoAcademico) {
            $dadoAcademico->inativar();
        }
    }

    public function isDadoAcademicoVinculado(
        Titulacao             $titulacao = null,
        CategoriaProfissional $categoriaProfissional = null,
                              $nuAnoIngresso = null,
                              $nuMatricula = null,
                              $nuSemestre = null,
                              $coCnes = null,
                              $stAlunoRegular = null,
                              $stDeclaracaoCursoPenultimo = null
    )
    {
        foreach ($this->dadosAcademicos as $dadoAcademico) {
            if (
                $dadoAcademico->getTitulacao() == $titulacao &&
                $dadoAcademico->getCategoriaProfissional() == $categoriaProfissional &&
                $dadoAcademico->getNuAnoIngresso() == $nuAnoIngresso &&
                $dadoAcademico->getNuMatricula() == $nuMatricula &&
                $dadoAcademico->getNuSemestre() == $nuSemestre &&
                $dadoAcademico->getCoCnes() == $coCnes &&
                $dadoAcademico->getStAlunoRegular() == $stAlunoRegular &&
                $dadoAcademico->getStDeclaracaoCursoPenultimo() == $stDeclaracaoCursoPenultimo
            ) {
                return $dadoAcademico;
            }
        }

        return false;
    }

    /**
     * @param Titulacao $titulacao
     * @param CategoriaProfissional $categoriaProfissional
     * @param string $nuAnoIngresso
     * @param string $nuMatricula
     * @param string $nuSemestre
     * @param string $coCnes
     * @param string $stAlunoRegular
     * @param string $stDeclaracaoCursoPenultimo
     * @return ProjetoPessoa
     */
    public function addDadosAcademicos(
        Titulacao             $titulacao = null,
        CategoriaProfissional $categoriaProfissional = null,
                              $nuAnoIngresso = null,
                              $nuMatricula = null,
                              $nuSemestre = null,
                              $coCnes = null,
                              $stAlunoRegular = null,
                              $stDeclaracaoCursoPenultimo = null
    )
    {
        $this->inativarAllDadosAcademicos();
        if ($dadoAcademicoVinculado = $this->isDadoAcademicoVinculado(
            $titulacao,
            $categoriaProfissional,
            $nuAnoIngresso,
            $nuMatricula,
            $nuSemestre,
            $coCnes,
            $stAlunoRegular,
            $stDeclaracaoCursoPenultimo
        )) {
            $dadoAcademicoVinculado->ativar();
        } else {
            $dadoAcademicoVinculado = new DadoAcademico(
                $this,
                $titulacao,
                $categoriaProfissional,
                $nuAnoIngresso,
                $nuMatricula,
                $nuSemestre,
                $coCnes,
                $stAlunoRegular,
                $stDeclaracaoCursoPenultimo
            );
            $this->dadosAcademicos->add($dadoAcademicoVinculado);
        }

        return $this;
    }

    /**
     * Inativa todos as vinculos com grupos de atuação
     * @return ProjetoPessoa
     */
    public function inativarAllGruposAtuacao()
    {
        foreach ($this->projetoPessoaGrupoAtuacao->toArray() as $projetoPessoa) {
            $projetoPessoa->inativar();
        }
        return $this;
    }

    /**
     * Inativa todos os cursosGraduacao ligados à Pessoa
     * @return ProjetoPessoa
     */
    public function inativarAllCursosGraduacao()
    {
        foreach ($this->projetoPessoaCursoGraduacao as $cursoGraduacao) {
            $cursoGraduacao->inativar();
        }
        return $this;
    }

    /**
     * @return CursoGraduacao
     */
    public function getCursoGraduacaoEstudanteOuPreceptor()
    {
        $projetoPessoaCursoGraduacao = $this->projetoPessoaCursoGraduacao->filter(function ($projetoPessoaCursoGraduacao) {
            $noRole = $projetoPessoaCursoGraduacao->getProjetoPessoa()->getPessoaPerfil()->getPerfil()->getNoRole();

            if (($noRole == Perfil::ROLE_ESTUDANTE) || ($noRole == Perfil::ROLE_PRECEPTOR)) {
                return $projetoPessoaCursoGraduacao->isAtivo();
            }
        })->first();

        if ($projetoPessoaCursoGraduacao) {
            return $projetoPessoaCursoGraduacao->getCursoGraduacao();
        }

        return null;
    }

    /**
     * @return array
     */
    public function getCursosLecionados()
    {
        $projetoPessoaCursosGraduacao = $this->projetoPessoaCursoGraduacao->filter(function ($projetoPessoaCursoGraduacao) {
            return $projetoPessoaCursoGraduacao->isAtivo();
        });

        if ($projetoPessoaCursosGraduacao) {
            $cursoGraduacao = [];
            foreach ($projetoPessoaCursosGraduacao as $projetoPessoaCursoGraduacao) {
                $cursoGraduacao[] = $projetoPessoaCursoGraduacao->getCursoGraduacao();
            }

            return $cursoGraduacao;
        }
    }

    /**
     * @return string
     */
    public function getCursosLecionadosStr()
    {
        $cursos = array();
        foreach ($this->getCursosLecionados() as $cursoLecionado) {
            $cursos[] = $cursoLecionado->getDsCursoGraduacao();
        }
        return implode(', ', $cursos);
    }

    /**
     * @return boolean
     */
    public function isVoluntario()
    {
        return $this->stVoluntarioProjeto == 'S';
    }

    /**
     * @return float
     */
    public function getVlBolsa()
    {
        foreach ($this->getProjeto()->getPublicacao()->getValorBolsaProgramaVigentes() as $valorBolsaPrograma) {
            if ($valorBolsaPrograma->getPerfil()->getCoSeqPerfil() == $this->getPessoaPerfil()->getPerfil()->getCoSeqPerfil()) {
                return $valorBolsaPrograma->getVlBolsa();
            }
        }
    }

    /**
     * @return string
     */
    public function getDescricaoGruposAtuacao()
    {
        $grupos = array();

        foreach ($this->getProjetoPessoaGrupoAtuacaoAtivo() as $item) {
            $grupos[] = $item->getGrupoAtuacao()->getDescricaoAreasTematicas();
        }

        asort($grupos);

        return implode(', ', $grupos);
    }

    public function hasDadoAcademico()
    {
        return $this->dadosAcademicos ? $this->dadosAcademicos : false;
    }

    /**
     *
     * @return string
     */
    public function getDescricaoParticipante()
    {
        return $this->getProjeto()->getCoSeqProjeto() . ' - '
            . $this->getPessoaPerfil()->getPessoaFisica()->getPessoa()->getNuCpfCnpjPessoa() . ' '
            . $this->getPessoaPerfil()->getPessoaFisica()->getPessoa()->getNoPessoa();
    }

    /**
     *
     * @return int
     */
    public function getPerfilParticipante()
    {
        return $this->getPessoaPerfil()->getPerfil()->getCoSeqPerfil();
    }

}