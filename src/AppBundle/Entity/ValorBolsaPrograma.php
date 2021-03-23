<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * ValorBolsaPrograma
 *
 * @ORM\Table(name="DBPET.TB_VALOR_BOLSA_PROGRAMA")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\ValorBolsaProgramaRepository")
 */
class ValorBolsaPrograma extends AbstractEntity
{
    use \AppBundle\Traits\DeleteLogicoTrait;
    use \AppBundle\Traits\DataInclusaoTrait;
    
    /**
     * @var int
     *
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     * @ORM\Column(name="CO_SEQ_VALOR_BOLSA_PROGRAMA", type="integer")
     * @ORM\SequenceGenerator(sequenceName="DBPET.SQ_VLBOLSAPROG_COSEQVLBOLSAPRG", allocationSize=1, initialValue=1)
     */
    private $coSeqValorBolsaPrograma;

    /**
     * @var Publicacao
     *
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Publicacao", inversedBy="valorBolsaPrograma")
     * @ORM\JoinColumn(name="CO_PUBLICACAO", referencedColumnName="CO_SEQ_PUBLICACAO")
     */
    private $publicacao;

    /**
     * @var Perfil
     *
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Perfil")
     * @ORM\JoinColumn(name="CO_PERFIL", referencedColumnName="CO_SEQ_PERFIL")
     */
    private $perfil;

    /**
     * @var float
     *
     * @ORM\Column(name="VL_BOLSA", type="decimal", precision=8, scale=2)
     */
    private $vlBolsa;
    
    /**
     *
     * @var string
     * 
     * @ORM\Column(name="NU_MES_VIGENCIA", type="string", length=2, nullable=false)
     */
    private $nuMesVigencia;
    
    /**
     *
     * @var string
     * 
     * @ORM\Column(name="NU_ANO_VIGENCIA", type="string", length=4, nullable=false)
     */
    private $nuAnoVigencia;
    
    /**
     *
     * @var string
     * 
     * @ORM\Column(name="ST_PERIODO_VIGENCIA", type="string", length=1, nullable=false)
     */
    private $stPeriodoVigencia;
    
    public $isInFolha = false;
    

    /**
     * @param Publicacao $publicacao
     * @param Perfil $perfil
     * @param float $vlBolsa
     * @param string $nuMesVigencia
     * @param string $nuAnoVigencia
     * @param boolean $isVigente
     */
    public function __construct(
        $publicacao,
        $perfil,
        $vlBolsa,
        $nuMesVigencia,
        $nuAnoVigencia,
        $isVigente = true
    ) {
        $this->publicacao = $publicacao;
        $this->perfil = $perfil;
        $this->vlBolsa = $vlBolsa;
        $this->nuMesVigencia = $nuMesVigencia;
        $this->nuAnoVigencia = $nuAnoVigencia;
        $this->stPeriodoVigencia = $isVigente ? 'S' : 'N';
        $this->dtInclusao = new \DateTime();
        $this->stRegistroAtivo = 'S';
    }    
    
    public function setPublicacao(Publicacao $publicacao)
    {
        $this->publicacao = $publicacao;
    }

    public function setPerfil(Perfil $perfil)
    {
        $this->perfil = $perfil;
    }

    public function setVlBolsa($vlBolsa)
    {
        $this->vlBolsa = $vlBolsa;
    }

    public function setNuMesVigencia($nuMesVigencia)
    {
        $this->nuMesVigencia = $nuMesVigencia;
    }

    public function setNuAnoVigencia($nuAnoVigencia)
    {
        $this->nuAnoVigencia = $nuAnoVigencia;
    }
    
    /**
     * 
     * @param boolean $stPeriodoVigencia
     */
    public function setStPeriodoVigencia($stPeriodoVigencia)
    {
        $this->stPeriodoVigencia = (true === $stPeriodoVigencia) ? 'S' : 'N';
    }
    

    /**
     * Get coSeqValorBolsaPrograma
     *
     * @return int
     */
    public function getCoSeqValorBolsaPrograma()
    {
        return $this->coSeqValorBolsaPrograma;
    }

    /**
     * Get publicacao
     *
     * @return Publicacao
     */
    public function getPublicacao()
    {
        return $this->publicacao;
    }

    /**
     * Get perfil
     *
     * @return Perfil
     */
    public function getPerfil()
    {
        return $this->perfil;
    }

    /**
     * Get vlBolsa
     *
     * @return float
     */
    public function getVlBolsa()
    {
        return $this->vlBolsa;
    }
    
    /**
     * 
     * @return string
     */
    public function getNuMesVigencia()
    {
        return $this->nuMesVigencia;
    }

    /**
     * 
     * @return string
     */
    public function getNuAnoVigencia()
    {
        return $this->nuAnoVigencia;
    }
    
    /**
     * 
     * @return string
     */
    public function getStPeriodoVigencia()
    {
        return $this->stPeriodoVigencia;
    }
        
    /**
     * 
     * @return string
     */
    public function getInicoVigencia()
    {
        return ($this->getNuMesVigencia() && $this->getNuAnoVigencia()) ? $this->getNuMesVigencia() . '/' . $this->getNuAnoVigencia() : ' - ';
    }
    
    /**
     * 
     * @return boolean
     */
    public function isInicioVigenciaMenorDtAtual()
    {
        $now = new \DateTime();
        
        return ($this->getNuAnoVigencia() . $this->getNuMesVigencia() < $now->format('Ym'));
    }
    
    /**
     * 
     * @return boolean
     */
    public function isVigenciaFutura()
    {
        $now = new \DateTime();
        
        return ($this->getNuAnoVigencia() . $this->getNuMesVigencia() > $now->format('Ym'));
    }
    
    /**
     * 
     * @return boolean
     */
    public function isVigente()
    {
        return $this->stPeriodoVigencia == 'S';
    }
    
    public function descontinuar()
    {
        $this->stPeriodoVigencia = 'N';
    }
    
    public function entrarEmVigencia()
    {
        $this->stPeriodoVigencia = 'S';
    }
}

