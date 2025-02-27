<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Titulacao
 *
 * @ORM\Table(name="DBPETINFOSD.TB_TITULACAO")
 * @ORM\Entity(repositoryClass="App\Repository\TitulacaoRepository")
 */
class Titulacao extends AbstractEntity
{
    use \App\Traits\DeleteLogicoTrait;
    use \App\Traits\DataInclusaoTrait;
    
    /**
     * @var int
     *
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="SEQUENCE")
     * @ORM\Column(name="CO_SEQ_TITULACAO", type="integer", unique=true)
     * @ORM\SequenceGenerator(sequenceName="DBPETINFOSD.SQ_CARGAHOR_COSEQCARGAHORARIA", allocationSize=1, initialValue=1)
     */
    private $coSeqTitulacao;

    /**
     * @var string
     *
     * @ORM\Column(name="DS_TITULACAO", type="string", length=50, unique=true)
     */
    private $dsTitulacao;

    /**
     * 
     * @param string $dsTitulacao
     */
    public function __construct($dsTitulacao)
    {
        $this->dsTitulacao = $dsTitulacao;
        $this->stRegistroAtivo = 'S';
        $this->dtInclusao = new \DateTime();
    }

    /**
     * Get coSeqTitulacao
     *
     * @return int
     */
    public function getCoSeqTitulacao()
    {
        return $this->coSeqTitulacao;
    }

    
    /**
     * Get dsTitulacao
     *
     * @return string
     */
    public function getDsTitulacao()
    {
        return $this->dsTitulacao;
    }
}

