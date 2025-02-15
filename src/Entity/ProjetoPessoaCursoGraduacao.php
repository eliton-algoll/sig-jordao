<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Entity\ProjetoPessoa;
use App\Entity\CursoGraduacao;

/**
 * ProjetoPessoaCursoGraduacao
 *
 * @ORM\Table(name="DBPET.RL_PROJPES_CURSOGRADUACAO")
 * @ORM\Entity(repositoryClass="App\Repository\ProjetoPessoaCursoGraduacaoRepository")
 */
class ProjetoPessoaCursoGraduacao extends AbstractEntity
{
    use \App\Traits\DeleteLogicoTrait;
    use \App\Traits\DataInclusaoTrait;
    
    /**
     * @var int
     *
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="SEQUENCE")
     * @ORM\Column(name="CO_SEQ_PROJPES_CURSOGRAD", type="integer", unique=true)
     * @ORM\SequenceGenerator(sequenceName="DBPET.SQ_PROJPESCURG_COSEQPJPESCURGR", allocationSize=1, initialValue=1)
     */
    private $coSeqProjetoPessoaCursoGraduacao;

    /**
     * @var ProjetoPessoa
     * 
     * @ORM\ManyToOne(targetEntity="App\Entity\ProjetoPessoa", inversedBy="projetoPessoaCursoGraduacao")
     * @ORM\JoinColumn(name="CO_PROJETO_PESSOA", referencedColumnName="CO_SEQ_PROJETO_PESSOA")
     */
    private $projetoPessoa;
    
    /**
     * @var CursoGraduacao
     * 
     * @ORM\ManyToOne(targetEntity="App\Entity\CursoGraduacao")
     * @ORM\JoinColumn(name="CO_CURSO_GRADUACAO", referencedColumnName="CO_SEQ_CURSO_GRADUACAO")
     */
    private $cursoGraduacao;
    
    public function __construct(ProjetoPessoa $projetoPessoa, CursoGraduacao $cursoGraduacao)
    {
        $this->projetoPessoa    = $projetoPessoa;
        $this->cursoGraduacao   = $cursoGraduacao;
        $this->stRegistroAtivo  = 'S';
        $this->dtInclusao       = new \DateTime();
    }

    /**
     * Set coSeqProjetoPessoaCursoGraduacao
     *
     * @param integer $coSeqProjetoPessoaCursoGraduacao
     *
     * @return ProjetoPessoaCursoGraduacao
     */
    public function setCoSeqProjetoPessoaCursoGraduacao($coSeqProjetoPessoaCursoGraduacao)
    {
        $this->coSeqProjetoPessoaCursoGraduacao = $coSeqProjetoPessoaCursoGraduacao;

        return $this;
    }

    /**
     * Get coSeqProjetoPessoaCursoGraduacao
     *
     * @return int
     */
    public function getCoSeqProjetoPessoaCursoGraduacao()
    {
        return $this->coSeqProjetoPessoaCursoGraduacao;
    }

    /**
     * Set projetoPessoa
     *
     * @param integer $projetoPessoa
     *
     * @return ProjetoPessoaCursoGraduacao
     */
    public function setProjetoPessoa($projetoPessoa)
    {
        $this->projetoPessoa = $projetoPessoa;

        return $this;
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
     * Set cursoGraduacao
     *
     * @param integer $cursoGraduacao
     *
     * @return ProjetoPessoaCursoGraduacao
     */
    public function setCursoGraduacao($cursoGraduacao)
    {
        $this->cursoGraduacao = $cursoGraduacao;

        return $this;
    }

    /**
     * Get cursoGraduacao
     *
     * @return CursoGraduacao
     */
    public function getCursoGraduacao()
    {
        return $this->cursoGraduacao;
    }
}

