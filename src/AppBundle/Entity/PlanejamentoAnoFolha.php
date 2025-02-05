<?php

namespace AppBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="DBPET.TB_PLANEJAMENTO_ANO_FOLHA")
 * @ORM\Entity(repositoryClass="\AppBundle\Repository\PlanejamentoAnoFolhaRepository")
 */
class PlanejamentoAnoFolha extends AbstractEntity
{
    use \AppBundle\Traits\DataInclusaoTrait;
    use \AppBundle\Traits\DeleteLogicoTrait;
    
    /**
     *
     * @var integer
     * 
     * @ORM\Id
     * @ORM\Column(name="CO_SEQ_PLANEJAMENTO_ANO_FOLHA", type="integer")
     * @ORM\GeneratedValue(strategy="SEQUENCE")
     * @ORM\SequenceGenerator(sequenceName="DBPET.SQ_PLANANOFLS_COSEQPLANEJANOFL", allocationSize=1, initialValue=1)
     */
    private $coSeqPlanejamentoAnoFolha;
    
    /**
     *
     * @var \AppBundle\Entity\Publicacao
     * 
     * @ORM\ManyToOne(targetEntity="\AppBundle\Entity\Publicacao")
     * @ORM\JoinColumn(name="CO_PUBLICACAO", referencedColumnName="CO_SEQ_PUBLICACAO", nullable=false)
     */
    private $publicacao;
    
    /**
     *
     * @var integer
     * 
     * @ORM\Column(name="NU_ANO", type="integer", nullable=false)
     */
    private $nuAno;
    
    /**
     *
     * @var ArrayCollection<\AppBundle\Entity\PlanejamentoMesFolha>
     * 
     * @ORM\OneToMany(targetEntity="\AppBundle\Entity\PlanejamentoMesFolha", mappedBy="planejamentoAnoFolha", cascade={"persist", "persist"})
     */
    private $planejamentoMesesFolha;
    
    /**
     * 
     * @param \AppBundle\Entity\Publicacao $publicacao
     * @param type $nuAno
     */
    public function __construct(Publicacao $publicacao, $nuAno)
    {
        $this->publicacao = $publicacao;
        $this->nuAno = (int) str_pad($nuAno, 4, '0', STR_PAD_LEFT);
        $this->planejamentoMesesFolha = new ArrayCollection();
        $this->stRegistroAtivo = 'S';
        $this->dtInclusao = new \DateTime();
    }
    
    /**
     * 
     * @param string $nuMes
     * @param integer $nuDiaAbertura
     */
    public function addPlanejamentoMesFolha($nuMes, $nuDiaAbertura)
    {
        $this->planejamentoMesesFolha->add(new PlanejamentoMesFolha($this, $nuMes, $nuDiaAbertura));
    }

    /**
     * 
     * @return integer
     */
    public function getCoSeqPlanejamentoAnoFolha()
    {
        return $this->coSeqPlanejamentoAnoFolha;
    }
    
    /**
     * 
     * @return \AppBundle\Entity\Publicacao
     */
    public function getPublicacao()
    {
        return $this->publicacao;
    }
    
    /**
     * 
     * @return integer
     */
    public function getNuAno()
    {
        return $this->nuAno;
    }
    
    /**
     * 
     * @return ArrayCollection<\AppBundle\Entity\PlanejamentoMesFolha>
     */
    public function getPlanejamentoMesesFolha()
    {
        return $this->planejamentoMesesFolha;
    }
    
    /**
     * 
     * @return ArrayCollection<\AppBundle\Entity\PlanejamentoMesFolha>
     */
    public function getPlanejamentoMesesFolhaAtivos()
    {
        return $this->planejamentoMesesFolha->filter(function ($planejamentoMesFolha) {
            return $planejamentoMesFolha->isAtivo();
        });
    }
    
    /**
     * 
     * @param string $nuMes
     * @return \AppBundle\Entity\PlanejamentoMesFolha
     */
    public function getPlanejamentoMesFolhaByNuMes($nuMes)
    {
        return $this->planejamentoMesesFolha->filter(function ($planejamentoMesFolha) use ($nuMes) {
            return $nuMes == $planejamentoMesFolha->getNuMes() && $planejamentoMesFolha->isAtivo();
        })->first();
    }
    
    /**
     * 
     * @return boolean
     */
    public function isNuAnoCorrente()
    {
        $now = new \DateTime();
        return $now->format('Y') == $this->getNuAno();
    }
    
    /**
     * 
     * @return boolean
     */
    public function isNuAnoFuturo()
    {
        $now = new \DateTime();
        return $now->format('Y') < $this->getNuAno();
    }
    
    /**
     * 
     * @return integer
     */
    public function getNextAno()
    {
        return $this->getNuAno() + 1 ;
    }
}
