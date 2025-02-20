<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * CategoriaProfissional
 *
 * @ORM\Table(name="DBPETINFOSD.TB_CATEGORIA_PROFISSIONAL")
 * @ORM\Entity(repositoryClass="App\Repository\CategoriaProfissionalRepository")
 */
class CategoriaProfissional extends AbstractEntity
{
    use \App\Traits\DeleteLogicoTrait;
    use \App\Traits\DataInclusaoTrait;    
    
    /**
     * @var int
     *
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="SEQUENCE")
     * @ORM\Column(name="CO_SEQ_CATEGORIA_PROFISSIONAL", type="integer", unique=true)
     * @ORM\SequenceGenerator(sequenceName="DBPETINFOSD.SQ_CATEGPROF_COSEQCATEGPROFIS", allocationSize=1, initialValue=1)
     */
    private $coSeqCategoriaProfissional;

    /**
     * @var string
     *
     * @ORM\Column(name="DS_CATEGORIA_PROFISSIONAL", type="string", length=255)
     */
    private $dsCategoriaProfissional;

    /**
     * @var string
     *
     * @ORM\Column(name="TP_AREA_FORMACAO", type="string", length=1)
     */
    private $tpAreaFormacao;

    /**
     * @param string $dsCategoriaProfissional
     */
    public function __construct($dsCategoriaProfissional, $tpAreaFormacao = null)
    {
        $this->dsCategoriaProfissional = $dsCategoriaProfissional;
        $this->stRegistroAtivo = 'S';
        $this->dtInclusao = new \DateTime();
        $this->tpAreaFormacao = $tpAreaFormacao;
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

    /**
     * @return mixed|string|null
     */
    public function getTpAreaFormacao()
    {
        return $this->tpAreaFormacao;
    }
}