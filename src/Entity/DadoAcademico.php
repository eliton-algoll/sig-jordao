<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * DadoAcademico
 *
 * @ORM\Table(name="DBPETINFOSD.TB_DADO_ACADEMICO")
 * @ORM\Entity(repositoryClass="App\Repository\DadoAcademicoRepository")
 */
class DadoAcademico extends AbstractEntity
{
    use \App\Traits\DeleteLogicoTrait;
    use \App\Traits\DataInclusaoTrait;
    
    /**
     * @var int
     *
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="SEQUENCE")
     * @ORM\Column(name="CO_SEQ_DADO_ACADEMICO", type="integer", unique=true)
     * @ORM\SequenceGenerator(sequenceName="DBPETINFOSD.SQ_DADOACADEM_COSEQDADOACADEM", allocationSize=1, initialValue=1)
     */
    private $coSeqDadoAcademico;

    /**
     * @var ProjetoPessoa
     * 
     * @ORM\ManyToOne(targetEntity="App\Entity\ProjetoPessoa")
     * @ORM\JoinColumn(name="CO_PROJETO_PESSOA", referencedColumnName="CO_SEQ_PROJETO_PESSOA")
     */
    private $projetoPessoa;

    /**
     * @var Titulacao
     *
     * @ORM\OneToOne(targetEntity="App\Entity\Titulacao")
     * @ORM\JoinColumn(name="CO_TITULACAO", referencedColumnName="CO_SEQ_TITULACAO")
     */
    private $titulacao;

    /**
     * @var CategoriaProfissional
     *
     * @ORM\ManyToOne(targetEntity="App\Entity\CategoriaProfissional")
     * @ORM\JoinColumn(name="CO_CATEGORIA_PROFISSIONAL", referencedColumnName="CO_SEQ_CATEGORIA_PROFISSIONAL")
     */
    private $categoriaProfissional;

    /**
     * @var string
     *
     * @ORM\Column(name="NU_ANO_INGRESSO", type="string", length=4)
     */
    private $nuAnoIngresso;

    /**
     * @var string
     *
     * @ORM\Column(name="NU_MATRICULA", type="string", length=30)
     */
    private $nuMatricula;

    /**
     * @var int
     *
     * @ORM\Column(name="NU_SEMESTRE", type="integer")
     */
    private $nuSemestre;

    /**
     * @var string
     *
     * @ORM\Column(name="CO_CNES", type="string", length=7)
     */
    private $coCnes;

    /**
     * @var string stAlunoRegular
     *
     * @ORM\Column(name="ST_ALUNO_REGULAR", type="string", length=1, nullable=false)
     */
    private $stAlunoRegular;

    /**
     * @var string stDeclaracaoCursoPenultimo
     *
     * @ORM\Column(name="ST_DECLARACAO_CURSO_PENULTIMO", type="string", length=1, nullable=false)
     */
    private $stDeclaracaoCursoPenultimo;
    
    /**
     * @param ProjetoPessoa $projetoPessoa
     * @param Titulacao $titulacao
     * @param CategoriaProfissional $categoriaProfissional
     * @param string $nuAnoIngresso
     * @param string $nuMatricula
     * @param integer $nuSemestre
     * @param string $coCnes
     */
    public function __construct(
        ProjetoPessoa $projetoPessoa, 
        Titulacao $titulacao = null,
        CategoriaProfissional $categoriaProfissional = null,
        $nuAnoIngresso = null,
        $nuMatricula = null,
        $nuSemestre = null,
        $coCnes = null,
        $stAlunoRegular = null,
        $stDeclaracaoCursoPenultimo = null
    ) {
        $this->projetoPessoa = $projetoPessoa;
        $this->titulacao = $titulacao;
        $this->categoriaProfissional = $categoriaProfissional;
        $this->nuAnoIngresso = $nuAnoIngresso;
        $this->nuMatricula = $nuMatricula;
        $this->nuSemestre = $nuSemestre;
        $this->coCnes = $coCnes;
        $this->stAlunoRegular = $stAlunoRegular;
        $this->stDeclaracaoCursoPenultimo = $stDeclaracaoCursoPenultimo ? 'S' : 'N';
        $this->stRegistroAtivo = 'S';
        $this->dtInclusao = new \DateTime();
    }

    /**
     * Get coSeqDadoAcademico
     *
     * @return int
     */
    public function getCoSeqDadoAcademico()
    {
        return $this->coSeqDadoAcademico;
    }

    /**
     * Get projetoPessoa
     * 
     * @return ProjetoPessoa
     */
    public function getProjetoPessoa()
    {
        return $this->projetoPessoa;
    }

    /**
     * Get titulacao
     *
     * @return Titulacao
     */
    public function getTitulacao()
    {
        return $this->titulacao;
    }

    /**
     * Get categoriaProfissional
     *
     * @return CategoriaProfissional
     */
    public function getCategoriaProfissional()
    {
        return $this->categoriaProfissional;
    }

    /**
     * Get nuAnoIngresso
     *
     * @return string
     */
    public function getNuAnoIngresso()
    {
        return $this->nuAnoIngresso;
    }

    /**
     * Get nuMatricula
     *
     * @return string
     */
    public function getNuMatricula()
    {
        return $this->nuMatricula;
    }

    /**
     * Get nuSemestre
     *
     * @return int
     */
    public function getNuSemestre()
    {
        return $this->nuSemestre;
    }

    /**
     * Get coCnes
     *
     * @return string
     */
    public function getCoCnes()
    {
        return $this->coCnes;
    }

    /**
     * Get stAlunoRegular
     *
     * @return string
     */
    public function getStAlunoRegular()
    {
        return $this->stAlunoRegular;
    }

    /**
     * Get stDeclaracaoCursoPenultimo
     *
     * @return string
     */
    public function getStDeclaracaoCursoPenultimo()
    {
        return $this->stDeclaracaoCursoPenultimo;
    }
    
    /**
     * @param Titulacao|null $titulacao
     * @return DadoAcademico
     */
    public function setTitulacao(Titulacao $titulacao = null)
    {
        $this->titulacao = $titulacao;
        return $this;
    }

    /**
     * @param CategoriaProfissional|null $categoriaProfissional
     * @return DadoAcademico
     */
    public function setCategoriaProfissional(CategoriaProfissional $categoriaProfissional = null)
    {
        $this->categoriaProfissional = $categoriaProfissional;
        return $this;
    }

    /**
     * @param string|null $nuAnoIngresso
     * @return DadoAcademico
     */
    public function setNuAnoIngresso($nuAnoIngresso = null)
    {
        $this->nuAnoIngresso = $nuAnoIngresso;
        return $this;
    }

    /**
     * @param string|null $nuMatricula
     * @return DadoAcademico
     */
    public function setNuMatricula($nuMatricula = null)
    {
        $this->nuMatricula = $nuMatricula;
        return $this;
    }

    /**
     * @param string|null $nuSemestre
     * @return DadoAcademico
     */
    public function setNuSemestre($nuSemestre = null)
    {
        $this->nuSemestre = $nuSemestre;
        return $this;
    }

    /**
     * @param string|null $coCnes
     * @return DadoAcademico
     */
    public function setCoCnes($coCnes = null)
    {
        $this->coCnes = $coCnes;
        return $this;
    }

    /**
     * @param string|null $stAlunoRegular
     * @return DadoAcademico
     */
    public function setStAlunoRegular($stAlunoRegular = null)
    {
        $this->stAlunoRegular = $stAlunoRegular;
        return $this;
    }

    /**
     * @param string|null $stDeclaracaoCursoPenultimo
     * @return DadoAcademico
     */
    public function setStDeclaracaoCursoPenultimo($stDeclaracaoCursoPenultimo = null)
    {
        $this->stDeclaracaoCursoPenultimo = $stDeclaracaoCursoPenultimo;
        return $this;
    }

}