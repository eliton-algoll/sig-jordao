<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * TipoAreaTematica
 *
 * @ORM\Table(name="DBPET.TB_TIPO_AREA_TEMATICA")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\TipoAreaTematicaRepository")
 */
class TipoAreaTematica extends AbstractEntity
{
    use \AppBundle\Traits\DeleteLogicoTrait;
    use \AppBundle\Traits\DataInclusaoTrait;    

    /**
     * @var int
     *
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="SEQUENCE")
     * @ORM\Column(name="CO_SEQ_TIPO_AREA_TEMATICA", type="integer")
     * @ORM\SequenceGenerator(sequenceName="DBPET.SQ_TPAREATEM_COSEQTPAREATEM", allocationSize=1, initialValue=1)
     */
    private $coSeqTipoAreaTematica;

    /**
     * @var string
     *
     * @ORM\Column(name="DS_TIPO_AREA_TEMATICA", type="string", length=100)
     */
    private $dsTipoAreaTematica;

    /**
     * @var string
     *
     * @ORM\Column(name="TP_AREA_TEMATICA", type="string", length=1)
     */
    private $tpAreaTematica;

    /**
     * @var string
     *
     * @ORM\Column(name="TP_AREA_FORMACAO", type="string", length=1)
     */
    private $tpAreaFormacao;

    /**
     * @param string $dsTipoAreaTematica
     * @param string $tpAreaTematica
     */
    public function __construct($dsTipoAreaTematica, $tpAreaTematica, $tpAreaFormacao)
    {
        $this->dsTipoAreaTematica = $dsTipoAreaTematica;
        $this->tpAreaTematica = $tpAreaTematica;
        $this->tpAreaFormacao = $tpAreaFormacao;
        $this->dtInclusao = new \DateTime();
        $this->stRegistroAtivo = 'S';
    }
    
    /**
     * Get coSeqTipoAreaTematica
     *
     * @return int
     */
    public function getCoSeqTipoAreaTematica()
    {
        return $this->coSeqTipoAreaTematica;
    }

    /**
     * Get dsTipoAreaTematica
     *
     * @return string
     */
    public function getDsTipoAreaTematica()
    {
        return $this->dsTipoAreaTematica;
    }

    /**
     * @param string $dsTipoAreaTematica
     */
    public function setDsTipoAreaTematica($dsTipoAreaTematica)
    {
        $this->dsTipoAreaTematica = $dsTipoAreaTematica;
    }

    /**
     * @param string $tpAreaTematica
     */
    public function setTpAreaTematica($tpAreaTematica)
    {
        $this->tpAreaTematica = $tpAreaTematica;
    }

    /**
     * @param string $tpAreaFormacao
     */
    public function setTpAreaFormacao($tpAreaFormacao)
    {
        $this->tpAreaFormacao = $tpAreaFormacao;
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

    /**
     * Get tpAreaTematica
     *
     * @return string
     */
    public function getTpAreaTematica($returnNome = false)
    {
        if ($returnNome) {
            
            $nomes = array(
                1 => 'Cursos',
                2 => 'Áreas Temáticas'
            );
            
            return $nomes[$this->tpAreaTematica];
        }
        
        return $this->tpAreaTematica;
    }

    /**
     * Get tpAreaTematica
     *
     * @return string
     */
    public function getTpAreaFormacao($returnNome = false)
    {
        if ($returnNome) {

            $nomes = array(
                1 => 'Saúde',
                2 => 'Ciências Humanas',
                3 => 'Ciências Sociais Aplicadas'
            );

            return ($this->tpAreaFormacao) ? $nomes[$this->tpAreaFormacao] : '-';
        }

        return $this->tpAreaFormacao;
    }
    
    /**
     * @return boolean
     */
    public function isEquals(TipoAreaTematica $tipoAreaTematica)
    {
        return $this->coSeqTipoAreaTematica === $tipoAreaTematica->getCoSeqTipoAreaTematica();
    }
}