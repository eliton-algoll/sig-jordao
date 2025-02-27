<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="DBPETINFOSD.TB_TIPO_ASSUNTO")
 * @ORM\Entity(repositoryClass="App\Repository\TipoAssuntoRepository")
 */
class TipoAssunto extends AbstractEntity
{
    use \App\Traits\DataInclusaoTrait;
    use \App\Traits\DeleteLogicoTrait;
    
    const OUTROS = 4;
    
    /**
     *
     * @var integer
     *
     * @ORM\Id
     * @ORM\Column(name="CO_SEQ_TIPO_ASSUNTO", type="integer")
     * @ORM\GeneratedValue(strategy="SEQUENCE")
     * @ORM\SequenceGenerator(sequenceName="DBPETINFOSD.SQ_TIPOASSUNTO_COSEQTIPASSUNTO", allocationSize=1, initialValue=1)
     */
    private $coSeqTipoAssunto;
    
    /**
     *
     * @var string
     *
     * @ORM\Column(name="DS_TIPO_ASSUNTO", type="string", length=60, nullable=false)
     */
    private $dsTipoAssunto;
    
    /**
     *
     * @param string $dsTipoAssunto
     */
    public function __construct($dsTipoAssunto)
    {
        $this->dsTipoAssunto = $dsTipoAssunto;
        $this->stRegistroAtivo = 'S';
        $this->dtInclusao = new \DateTime();
    }

    /**
     *
     * @return integer
     */
    public function getCoSeqTipoAssunto()
    {
        return $this->coSeqTipoAssunto;
    }

    /**
     *
     * @return string
     */
    public function getDsTipoAssunto()
    {
        return $this->dsTipoAssunto;
    }
    
    /**
     * 
     * @return boolean
     */
    public function isOutros()
    {
        return $this->getCoSeqTipoAssunto() === self::OUTROS;
    }
}
