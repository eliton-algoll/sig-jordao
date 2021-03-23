<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * Programa
 *
 * @ORM\Table(name="DBPET.TB_PROGRAMA")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\ProgramaRepository")
 */
class Programa extends AbstractEntity
{
    use \AppBundle\Traits\DeleteLogicoTrait;
    use \AppBundle\Traits\DataInclusaoTrait;

    const AREA_ATUACAO = 1;
    const GRUPO_TUTORIAL = 2;
    
    /**
     * @var int
     *
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="SEQUENCE")
     * @ORM\Column(name="CO_SEQ_PROGRAMA", type="integer")
     * @ORM\SequenceGenerator(sequenceName="DBPET.SQ_PROGRAMA_COSEQPROGRAMA", allocationSize=1, initialValue=1)
     */
    private $coSeqPrograma;

    /**
     * @var string
     *
     * @ORM\Column(name="DS_PROGRAMA", type="string", length=255)
     */
    private $dsPrograma;

    /**
     * @ORM\OneToMany(targetEntity="AppBundle\Entity\Publicacao", mappedBy="programa", cascade={"persist"})
     * @var ArrayCollection<Publicacao>
     */
    private $publicacoes;
    
    /**
     * @var string
     * 
     * @ORM\Column(name="TP_AREA_TEMATICA", type="string", length=1)
     */
    private $tpAreaTematica;

    /**
     * @var integer
     *
     * @ORM\Column(name="TP_PROGRAMA", type="integer")
     */
    private $tpPrograma;

    /**
     * Programa constructor.
     * @param $dsPrograma
     * @param $tpAreaTematica
     * @param $tpPrograma
     * @throws \Exception
     */
    public function __construct($dsPrograma, $tpAreaTematica, $tpPrograma)
    {
        $this->dsPrograma = $dsPrograma;
        $this->setTpAreaTematica($tpAreaTematica);
        $this->setTpPrograma($tpPrograma);
        $this->stRegistroAtivo = 'S';
        $this->dtInclusao = new \DateTime();
        $this->publicacoes = new ArrayCollection();
    }

    /**
     * Get coSeqPrograma
     *
     * @return int
     */
    public function getCoSeqPrograma()
    {
        return $this->coSeqPrograma;
    }

    /**
     * Get dsPrograma
     *
     * @return string
     */
    public function getDsPrograma()
    {
        return $this->dsPrograma;
    }
    
    /**
     * @return ArrayCollection<Publicacao>
     */
    public function getPublicacoes()
    {
        return $this->publicacoes;
    }
    
    /**
     * @return ArrayCollection<Publicacao>
     */
    public function getPublicacoesAtivas()
    {
        return $this->publicacoes->filter(function ($publicacao) {
            return $publicacao->isAtivo();
        });
    }

    /**
     * @param Publicacao $publicacao
     * @return Programa
     */
    public function addPublicacao(Publicacao $publicacao)
    {
        if (!$this->publicacoes->contains($publicacao)) {
            $this->publicacoes[] = $publicacao;
        }
        
        return $this;
    }

    /**
     * @param string $novoNome
     * @return Programa
     */
    public function renomear($novoNome)
    {
        $this->dsPrograma = $novoNome;
        return $this;
    }
    
    /**
     * @param integer $tpAreaTematica
     * @return Programa
     * @throws \InvalidArgumentException
     */
    public function setTpAreaTematica($tpAreaTematica)
    {
        if (!in_array($tpAreaTematica, array(1 ,2))) {
            throw new \InvalidArgumentException('Tipo de área temática inválida');
        }
        
        $this->tpAreaTematica = $tpAreaTematica;
        
        return $this;
    }

    /**
     * @param $tpPrograma
     */
    public function setTpPrograma($tpPrograma)
    {
        if (!in_array($tpPrograma, self::getTpProgramas())) {
            throw new \InvalidArgumentException('Tipo de programa inválido');
        }

        $this->tpPrograma = $tpPrograma;
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
     * @return int
     */
    public function getTpPrograma()
    {
        return $this->tpPrograma;
    }

    /**
     * @return array
     */
    public static function getTpProgramas()
    {
        return array(
            'Área de Atuação' => self::AREA_ATUACAO,
            'Grupo Tutorial' => self::GRUPO_TUTORIAL,
        );
    }

    /**
     * @return bool
     */
    public function isAreaAtuacao()
    {
        return self::AREA_ATUACAO == $this->tpPrograma;
    }

    /**
     * @return bool
     */
    public function isGrupoTutorial()
    {
        return self::GRUPO_TUTORIAL == $this->tpPrograma;
    }
}
