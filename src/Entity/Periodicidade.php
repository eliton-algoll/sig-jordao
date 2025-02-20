<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="DBPETINFOSD.TB_PERIODICIDADE")
 */
class Periodicidade extends AbstractEntity
{
    use \App\Traits\DataInclusaoTrait;
    use \App\Traits\DeleteLogicoTrait;
    
    /**
     *
     * @var integer
     * 
     * @ORM\Id
     * @ORM\Column(name="CO_SEQ_PERIODICIDADE", type="integer")
     * @ORM\GeneratedValue(strategy="SEQUENCE")
     * @ORM\SequenceGenerator(sequenceName="DBPETINFOSD.SQ_PERIOD_COSEQPERIODICIDADE", allocationSize=1, initialValue=1)
     */
    private $coSeqPeriodicidade;
    
    /**
     *
     * @var string
     * 
     * @ORM\Column(name="DS_PERIODICIDADE", type="string", length=60, nullable=false)
     */
    private $dsPeriodicidade;
    
    /**
     * 
     * @param string $dsPeriodicidade
     */
    public function __construct($dsPeriodicidade)
    {
        $this->dsPeriodicidade = $dsPeriodicidade;
        $this->dtInclusao = new \DateTime();
        $this->stRegistroAtivo = 'S';
    }

    /**
     * 
     * @return integer
     */
    public function getCoSeqPeriodicidade()
    {
        return $this->coSeqPeriodicidade;
    }

    /**
     * 
     * @return string
     */
    public function getDsPeriodicidade()
    {
        return $this->dsPeriodicidade;
    }
}
