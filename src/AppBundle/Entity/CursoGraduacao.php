<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * CursoGraduacao
 *
 * @ORM\Table(name="DBPET.TB_CURSO_GRADUACAO")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\CursoGraduacaoRepository")
 */
class CursoGraduacao extends AbstractEntity
{
    use \AppBundle\Traits\DeleteLogicoTrait;
    use \AppBundle\Traits\DataInclusaoTrait;
    
    /**
     * @var int
     *
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="SEQUENCE")
     * @ORM\Column(name="CO_SEQ_CURSO_GRADUACAO", type="integer", unique=true)
     * @ORM\SequenceGenerator(sequenceName="DBPET.SQ_CURSOGRAD_COSEQCURSOGRADUAC", allocationSize=1, initialValue=1)
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
     * @ORM\OneToMany(targetEntity="AppBundle\Entity\ProjetoPessoaCursoGraduacao", mappedBy="cursoGraduacao", cascade={"persist"})
     */
    private $projetoPessoaCursoGraduacao;


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
    
    public function getProjetoPessoaCursoGraduacao()
    {
        return $this->projetoPessoaCursoGraduacao;
    }
}

