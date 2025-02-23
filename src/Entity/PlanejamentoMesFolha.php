<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="DBPETINFOSD.TB_PLANEJAMENTO_MES_FOLHA")
 * @ORM\Entity(repositoryClass="App\Repository\PlanejamentoMesFolhaRepository")
 */
class PlanejamentoMesFolha extends AbstractEntity
{
    use \App\Traits\DataInclusaoTrait;
    use \App\Traits\DeleteLogicoTrait;
    
    /**
     *
     * @var integer
     * 
     * @ORM\Id
     * @ORM\Column(name="CO_SEQ_PLANEJAMENTO_MES_FOLHA", type="integer")
     * @ORM\GeneratedValue(strategy="SEQUENCE")
     * @ORM\SequenceGenerator(sequenceName="DBPETINFOSD.SQ_PLANMESFLS_COSEQPLANEJMESFL", allocationSize=1, initialValue=1)
     */
    private $coSeqPlanejamentoMesFolha;
    
    /**
     *
     * @var PlanejamentoAnoFolha
     * 
     * @ORM\ManyToOne(targetEntity="\App\Entity\PlanejamentoAnoFolha")
     * @ORM\JoinColumn(name="CO_PLANEJAMENTO_ANO_FOLHA", referencedColumnName="CO_SEQ_PLANEJAMENTO_ANO_FOLHA", nullable=false)
     */
    private $planejamentoAnoFolha;
    
    /**
     *
     * @var string
     * 
     * @ORM\Column(name="NU_MES", type="string", length=2, nullable=false)
     */
    private $nuMes;
    
    /**
     *
     * @var integer
     * 
     * @ORM\Column(name="NU_DIA", type="integer", nullable=false)
     */
    private $nuDiaAbertura;
    
    /**
     *
     * @var FolhaPagamento
     * 
     * @ORM\OneToOne(targetEntity="App\Entity\FolhaPagamento", mappedBy="planejamentoMesFolha")
     */
    private $folhaPagamento;
    
    /**
     * 
     * @param PlanejamentoAnoFolha $planejamentoAnoFolha
     * @param string $nuMes
     * @param integer $nuDiaAbertura
     */
    public function __construct(PlanejamentoAnoFolha $planejamentoAnoFolha, $nuMes, $nuDiaAbertura)
    {
        $this->planejamentoAnoFolha = $planejamentoAnoFolha;
        $this->nuMes = str_pad($nuMes, 2, '0', STR_PAD_LEFT);
        $this->nuDiaAbertura = $nuDiaAbertura;
        $this->stRegistroAtivo = 'S';
        $this->dtInclusao = new \DateTime();
    }
    
    /**
     * 
     * @return integer
     */
    public function getCoSeqPlanejamentoMesFolha()
    {
        return $this->coSeqPlanejamentoMesFolha;
    }
        
    /**
     * 
     * @return integer
     */
    public function getPlanejamentoAnoFolha()
    {
        return $this->planejamentoAnoFolha;
    }

    /**
     * 
     * @return string
     */
    public function getNuMes()
    {
        return $this->nuMes;
    }

    /**
     * 
     * @return integer
     */
    public function getNuDiaAbertura()
    {
        return $this->nuDiaAbertura;
    }
    
    /**
     * 
     * @return FolhaPagamento
     */
    public function getFolhaPagamento()
    {
        return $this->folhaPagamento;
    }
            
    /**
     * 
     * @return string
     */
    public function getNoMes()
    {
        $date = \DateTime::createFromFormat("!m", $this->nuMes);
        return $date->format('F');
    }
    
    /**
     * 
     * @param integer $nuDiaAbertura
     */
    public function setNuDiaAbertura($nuDiaAbertura)
    {
        $this->nuDiaAbertura = $nuDiaAbertura;
    }
    
    /**
     * 
     * @return array
     */
    public function toArray($parent = null)
    {
        return [
            'coSeqPlanejamentoMesFolha' => $this->coSeqPlanejamentoMesFolha,
            'nuMes' => $this->nuMes,
            'nuDiaAbertura' => $this->nuDiaAbertura,
            'isAtivo' => $this->isAtivo(),
            'dtInclusao' => $this->dtInclusao->format('d/m/Y'),
        ];
    }
}
