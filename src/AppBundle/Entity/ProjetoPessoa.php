<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * ProjetoPessoa
 *
 * @ORM\Table(name="DBPET.TB_PROJETO_PESSOA")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\ProjetoPessoaRepository")
 */
class ProjetoPessoa extends AbstractEntity
{
    use \AppBundle\Traits\DeleteLogicoTrait;
    use \AppBundle\Traits\DataInclusaoTrait;    
    
    /**
     * @var int
     *
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="SEQUENCE")
     * @ORM\Column(name="CO_SEQ_PROJETO_PESSOA", type="integer")
     * @ORM\SequenceGenerator(sequenceName="DBPET.SQ_PROJPESSOA_COSEQPROJPESSOA", allocationSize=1, initialValue=1)
     */
    private $coSeqProjetoPessoa;

    /**
     * @var Projeto
     *
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Projeto", inversedBy="projetosPessoas")
     * @ORM\JoinColumn(name="CO_PROJETO", referencedColumnName="CO_SEQ_PROJETO")
     */
    private $projeto;

    /**
     * @var PessoaPerfil pessoaPerfil
     *
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\PessoaPerfil", inversedBy="projetosPessoas", cascade={"persist"})
     * @ORM\JoinColumn(name="CO_PESSOA_PERFIL", referencedColumnName="CO_SEQ_PESSOA_PERFIL")
     */
    private $pessoaPerfil;
    
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
     * @var ProjetoPessoaGrupoAtuacao
     * @ORM\OneToMany(targetEntity="AppBundle\Entity\ProjetoPessoaGrupoAtuacao", mappedBy="projetoPessoa", cascade={"persist"})
     */
    private $projetoPessoaGrupoAtuacao;
    
    /**
     * @var DadoAcademico
     * @ORM\OneToMany(targetEntity="AppBundle\Entity\DadoAcademico", mappedBy="projetoPessoa", cascade={"persist"})
     */
    private $dadosAcademicos;
    
    /**
     * @var ProjetoPessoaCursoGraduacao 
     * @ORM\OneToMany(targetEntity="AppBundle\Entity\ProjetoPessoaCursoGraduacao", mappedBy="projetoPessoa", cascade={"persist"})
     */
    private $projetoPessoaCursoGraduacao;

    /**
     * @param Projeto $projeto
     * @param PessoaPerfil $pessoaPerfil
     * @param string $stVoluntarioProjeto
     */
    public function __construct(Projeto $projeto, PessoaPerfil $pessoaPerfil, $stVoluntarioProjeto = 'N')
    {
        $this->projetoPessoaGrupoAtuacao = new ArrayCollection();
        $this->projetoPessoaCursoGraduacao = new ArrayCollection();
        $this->dadosAcademicos = new ArrayCollection();
        $this->projeto = $projeto;
        $this->pessoaPerfil = $pessoaPerfil;
        $this->stVoluntarioProjeto = $stVoluntarioProjeto;
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
        return $this->dadosAcademicos->filter(function($dadoAcademico){
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
     * @param string $stVoluntarioProjeto
     * @return ProjetoPessoa
     */
    public function setStVoluntarioProjeto($stVoluntarioProjeto)
    {
        $this->stVoluntarioProjeto = $stVoluntarioProjeto;
        return $this;
    }
    
    public function inativar()
    {
        $this->stRegistroAtivo = 'N';
        $this->dtDesligamento = new \DateTime();
    }
            
    /**
     * @param GrupoAtuacao $grupoAtuacao
     * @return boolean
     */
    public function hasGrupoAtuacao(GrupoAtuacao $grupoAtuacao)
    {
        foreach ($this->getProjetoPessoaGrupoAtuacaoAtivo() as $projetoPessoaGrupoAtuacao) {
            if($projetoPessoaGrupoAtuacao->getGrupoAtuacao() == $grupoAtuacao){
                return true;
            }
        }
        return false;
    }
    
    public function isGrupoAtuacaoVinculado(GrupoAtuacao $grupoAtuacao)
    {
        foreach($this->projetoPessoaGrupoAtuacao as $projetoPessoaGrupoAtuacao) {
            if($projetoPessoaGrupoAtuacao->getGrupoAtuacao() == $grupoAtuacao) {
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
     * @param \AppBundle\Entity\CursoGraduacao $cursoGraduacao
     * @return boolean
     */
    public function hasCursoGraduacao(CursoGraduacao $cursoGraduacao)
    {
        foreach ($this->projetoPessoaCursoGraduacao as $projetoPessoaCursoGraduacao) {
            if ($projetoPessoaCursoGraduacao->getCursoGraduacao() == $cursoGraduacao){
                return true;
            }
        }
        return false;
    }
    
    public function inativarAllProjetoPessoaCursoGraduacao()
    {
        foreach($this->projetoPessoaCursoGraduacao as $projetoPessoaCursoGraduacao) {
            $projetoPessoaCursoGraduacao->inativar();
        }
    }
    
    public function isCursoGraduacaoVinculado(CursoGraduacao $cursoGraduacao)
    {
        foreach($this->projetoPessoaCursoGraduacao as $projetoPessoaCursoGraduacao) {
            if($projetoPessoaCursoGraduacao->getCursoGraduacao() == $cursoGraduacao) {
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
    public function addGrupoTutorial(GrupoAtuacao $grupoAtuacao)
    {
        if ($projetoPessoaGrupoAtuacao = $this->getProjetoPessoaGrupoAtuacaoByGrupoAtuacao($grupoAtuacao)) {
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
    public function getProjetoPessoaGrupoAtuacaoByGrupoAtuacao(GrupoAtuacao $grupoAtuacao) {
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
            function(ProjetoPessoaGrupoAtuacao $projetoPessoaGrupoAtuacao) {
                return $projetoPessoaGrupoAtuacao->isAtivo();
            }
        );
    }
    
    public function inativarAllDadosAcademicos()
    {
        foreach($this->dadosAcademicos as $dadoAcademico) {
            $dadoAcademico->inativar();
        }
    }
    
    public function isDadoAcademicoVinculado(
        Titulacao $titulacao = null,
        CategoriaProfissional $categoriaProfissional = null,
        $nuAnoIngresso = null,
        $nuMatricula = null,
        $nuSemestre = null,
        $coCnes = null    
    ) {
        foreach($this->dadosAcademicos as $dadoAcademico) {
            if(
            $dadoAcademico->getTitulacao() == $titulacao &&
            $dadoAcademico->getCategoriaProfissional() == $categoriaProfissional &&
            $dadoAcademico->getNuAnoIngresso() == $nuAnoIngresso &&
            $dadoAcademico->getNuMatricula() == $nuMatricula &&
            $dadoAcademico->getNuSemestre() == $nuSemestre &&
            $dadoAcademico->getCoCnes() == $coCnes
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
     * @return ProjetoPessoa
     */
    public function addDadosAcademicos(
        Titulacao $titulacao = null,
        CategoriaProfissional $categoriaProfissional = null,
        $nuAnoIngresso = null,
        $nuMatricula = null,
        $nuSemestre = null,
        $coCnes = null
    ) {
        $this->inativarAllDadosAcademicos();
        if($dadoAcademicoVinculado = $this->isDadoAcademicoVinculado(
            $titulacao,
            $categoriaProfissional,
            $nuAnoIngresso,
            $nuMatricula,
            $nuSemestre,
            $coCnes
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
                $coCnes
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
    public function getCursoGraduacaoEstudante()
    {
        $projetoPessoaCursoGraduacao = $this->projetoPessoaCursoGraduacao->filter(function($projetoPessoaCursoGraduacao){
            if($projetoPessoaCursoGraduacao->getProjetoPessoa()->getPessoaPerfil()->getPerfil()->getNoRole() == Perfil::ROLE_ESTUDANTE) {
                return $projetoPessoaCursoGraduacao->isAtivo();
            }
        })->first();
        
        if($projetoPessoaCursoGraduacao) {
            return $projetoPessoaCursoGraduacao->getCursoGraduacao();
        }
    }
    
    /**
     * @return array
     */
    public function getCursosLecionados()
    {
        $projetoPessoaCursosGraduacao = $this->projetoPessoaCursoGraduacao->filter(function($projetoPessoaCursoGraduacao) {
            return $projetoPessoaCursoGraduacao->isAtivo();            
        });
        
        if($projetoPessoaCursosGraduacao) {
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
        return $this->dadosAcademicos ? $this->dadosAcademicos: false;
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
}