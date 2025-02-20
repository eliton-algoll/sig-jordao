<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * CursoGraduacao
 *
 * @ORM\Table(name="DBPETINFOSD.TB_CURSO_GRADUACAO")
 * @ORM\Entity(repositoryClass="App\Repository\CursoGraduacaoRepository")
 */
class CursoGraduacao extends AbstractEntity
{
    use \App\Traits\DeleteLogicoTrait;
    use \App\Traits\DataInclusaoTrait;
    
    /**
     * @var int
     *
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="SEQUENCE")
     * @ORM\Column(name="CO_SEQ_CURSO_GRADUACAO", type="integer", unique=true)
     * @ORM\SequenceGenerator(sequenceName="DBPETINFOSD.SQ_CURSOGRAD_COSEQCURSOGRADUAC", allocationSize=1, initialValue=1)
     */
    private $coSeqCursoGraduacao;

    /**
     * @var string
     *
     * @ORM\Column(name="DS_CURSO_GRADUACAO", type="string", length=60, unique=true)
     */
    private $dsCursoGraduacao;
    
    /**
     * @var ProjetoPessoaCursoGraduacao 
     * @ORM\OneToMany(targetEntity="App\Entity\ProjetoPessoaCursoGraduacao", mappedBy="cursoGraduacao", cascade={"persist"})
     */
    private $projetoPessoaCursoGraduacao;

    /**
     * @param string $dsCursoGraduacao
     * @param string $tpAreaTematica
     */
    public function __construct($dsCursoGraduacao)
    {
        $this->dsCursoGraduacao = $dsCursoGraduacao;
        $this->dtInclusao = new \DateTime();
        $this->stRegistroAtivo = 'S';
    }

    /**
     * Get coSeqCursoGraduacao
     *
     * @return int
     */
    public function getCoSeqCursoGraduacao()
    {
        return $this->coSeqCursoGraduacao;
    }

    /**
     * Set dsCursoGraduacao
     *
     * @param string $dsCursoGraduacao
     *
     * @return CursoGraduacao
     */
    public function setDsCursoGraduacao($dsCursoGraduacao)
    {
        $this->dsCursoGraduacao = $dsCursoGraduacao;

        return $this;
    }

    /**
     * Get dsCursoGraduacao
     *
     * @return string
     */
    public function getDsCursoGraduacao()
    {
        return $this->dsCursoGraduacao;
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
    
    public function getProjetoPessoaCursoGraduacao()
    {
        return $this->projetoPessoaCursoGraduacao;
    }
}

