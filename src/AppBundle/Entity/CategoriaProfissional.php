<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * CategoriaProfissional
 *
 * @ORM\Table(name="DBPET.TB_CATEGORIA_PROFISSIONAL")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\CategoriaProfissionalRepository")
 */
class CategoriaProfissional extends AbstractEntity
{
    use \AppBundle\Traits\DeleteLogicoTrait;
    use \AppBundle\Traits\DataInclusaoTrait;    
    
    /**
     * @var int
     *
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="SEQUENCE")
     * @ORM\Column(name="CO_SEQ_CATEGORIA_PROFISSIONAL", type="integer", unique=true)
     * @ORM\SequenceGenerator(sequenceName="DBPET.SQ_CATEGPROF_COSEQCATEGPROFIS", allocationSize=1, initialValue=1)
     */
    private $coSeqCategoriaProfissional;

    /**
     * @var string
     *
     * @ORM\Column(name="DS_CATEGORIA_PROFISSIONAL", type="string", length=255)
     */
    private $dsCategoriaProfissional;

    /**
     * @param string $dsCategoriaProfissional
     */
    public function __construct($dsCategoriaProfissional)
    {
        $this->dsCategoriaProfissional = $dsCategoriaProfissional;
        $this->stRegistroAtivo = 'S';
        $this->dtInclusao = new \DateTime();
    }
    
    /**
     * Get coSeqCategoriaProfissional
     *
     * @return int
     */
    public function getCoSeqCategoriaProfissional()
    {
        return $this->coSeqCategoriaProfissional;
    }

    /**
     * Get dsCategoriaProfissional
     *
     * @return string
     */
    public function getDsCategoriaProfissional()
    {
        return $this->dsCategoriaProfissional;
    }
}