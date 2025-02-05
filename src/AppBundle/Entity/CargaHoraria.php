<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * CargaHoraria
 *
 * @ORM\Table(name="DBPET.TB_CARGA_HORARIA")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\CargaHorariaRepository")
 */
class CargaHoraria extends AbstractEntity
{
    use \AppBundle\Traits\DeleteLogicoTrait;
    use \AppBundle\Traits\DataInclusaoTrait;
    
    /**
     * @var int
     *
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="SEQUENCE")
     * @ORM\Column(name="CO_SEQ_CARGA_HORARIA", type="integer", unique=true)
     * @ORM\SequenceGenerator(sequenceName="DBPET.SQ_CARGAHOR_COSEQCARGAHORARIA", allocationSize=1, initialValue=1)
     */
    private $coSeqCargaHoraria;

    /**
     * @var int
     *
     * @ORM\Column(name="QT_HORA", type="integer", unique=true)
     */
    private $qtHora;
    
    /**
     * @var ArrayCollection DadosPessoais 
     * 
     * @ORM\OneToMany(targetEntity="AppBundle\Entity\DadoPessoal", mappedBy="cargaHoraria")
     */
    private $dadosPessoais;
    
    public function __construct($qtHora)
    {
        $this->qtHora = $qtHora;
        $this->stRegistroAtivo = 'S';
        $this->dtInclusao = new \DateTime();
        $this->dadosPessoais = new ArrayCollection();
    }

    /**
     * Get coSeqCargaHoraria
     *
     * @return int
     */
    public function getCoSeqCargaHoraria()
    {
        return $this->coSeqCargaHoraria;
    }

    /**
     * Get qtHora
     *
     * @return int
     */
    public function getQtHora()
    {
        return $this->qtHora;
    }
    
    /**
     * @return ArrayCollection<DadoPessoal>
     */
    public function getDadosPessoais()
    {
        return $this->dadosPessoais;
    }
}

