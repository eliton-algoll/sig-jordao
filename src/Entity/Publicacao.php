<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use App\Entity\SituacaoFolha;

/**
 * Publicacao
 *
 * @ORM\Table(name="DBPETINFOSD.TB_PUBLICACAO")
 * @ORM\Entity(repositoryClass="App\Repository\PublicacaoRepository")
 */
class Publicacao extends AbstractEntity
{
    use \App\Traits\DeleteLogicoTrait;
    use \App\Traits\DataInclusaoTrait;
    
    const EDITAL = 'E';
    const MEMORANDO = 'M';
    const PORTARIA = 'P';
    
    /**
     * @var int
     *
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="SEQUENCE")
     * @ORM\Column(name="CO_SEQ_PUBLICACAO", type="integer")
     * @ORM\SequenceGenerator(sequenceName="DBPETINFOSD.SQ_PUBLICACAO_COSEQPUBLICACAO", allocationSize=1, initialValue=1)
     */
    private $coSeqPublicacao;

    /**
     * @var Programa
     *
     * @ORM\ManyToOne(targetEntity="App\Entity\Programa", inversedBy="publicacoes")
     * @ORM\JoinColumn(name="CO_PROGRAMA", referencedColumnName="CO_SEQ_PROGRAMA")
     */
    private $programa;

    /**
     * @var int
     *
     * @ORM\Column(name="NU_PUBLICACAO", type="integer")
     */
    private $nuPublicacao;

    /**
     * @var string
     *
     * @ORM\Column(name="DS_PUBLICACAO", type="string", length=2000)
     */
    private $dsPublicacao;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="DT_PUBLICACAO", type="date")
     */
    private $dtPublicacao;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="DT_INICIO_VIGENCIA", type="date")
     */
    private $dtInicioVigencia;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="DT_FIM_VIGENCIA", type="date")
     */
    private $dtFimVigencia;

    /**
     * @var string
     *
     * @ORM\Column(name="TP_PUBLICACAO", type="string", length=1)
     */
    private $tpPublicacao;
    
    /**
     * @var ArrayCollection<QuantidadePublicacao>
     * 
     * @ORM\OneToMany(targetEntity="App\Entity\QuantidadePublicacao", mappedBy="publicacao", cascade={"persist"})
     * @ORM\OrderBy({"tipoQuantitativoPublicacao" = "ASC"})
     */
    private $quantidades;
    
    /**
     * @var ArrayCollection<Projeto>
     * 
     * @ORM\OneToMany(targetEntity="App\Entity\Projeto", mappedBy="publicacao")
     * @ORM\OrderBy({"coSeqProjeto" = "ASC"})
     */
    private $projetos;    
    
    /**
     *
     * @var ArrayCollection<ValorBolsaPrograma>
     * 
     * @ORM\OneToMany(targetEntity="App\Entity\ValorBolsaPrograma", mappedBy="publicacao")
     */
    private $valorBolsaPrograma;
    
    /**
     * @var ArrayCollection<FolhaPagamento>
     * 
     * @ORM\OneToMany(targetEntity="App\Entity\FolhaPagamento", mappedBy="publicacao")
     * @ORM\OrderBy({"nuAno" = "DESC", "nuMes" = "DESC"})
     */
    private $folhasPagamento;

    /**
     * @param Programa $programa
     * @param integer $nuPublicacao
     * @param \DateTime $dtPublicacao
     * @param \DateTime $dtInicioVigencia
     * @param \DateTime $dtFimVigencia
     * @param string $tpPublicacao
     * @param string|null $dsPublicacao
     */
    public function __construct(
        Programa $programa, 
        $nuPublicacao, 
        $dtPublicacao,
        $dtInicioVigencia,
        $dtFimVigencia,
        $tpPublicacao,
        $dsPublicacao = null
    ) {
        $this->stRegistroAtivo = 'S';
        $this->dtInclusao = new \DateTime();
        $this->programa = $programa;
        $this->nuPublicacao = $nuPublicacao;
        $this->dtPublicacao = $dtPublicacao;
        $this->dtInicioVigencia = $dtInicioVigencia;
        $this->dtFimVigencia = $dtFimVigencia;
        $this->tpPublicacao = $tpPublicacao;
        $this->dsPublicacao = $dsPublicacao;
        $this->quantidades = new ArrayCollection();
        $this->projetos = new ArrayCollection();
        $this->valorBolsaPrograma = new ArrayCollection();
        $this->folhasPagamento = new ArrayCollection();
    }
    
    /**
     * Get coSeqPublicacao
     *
     * @return int
     */
    public function getCoSeqPublicacao()
    {
        return $this->coSeqPublicacao;
    }

    /**
     * Get programa
     *
     * @return Programa
     */
    public function getPrograma()
    {
        return $this->programa;
    }

    /**
     * Get nuPublicacao
     *
     * @return int
     */
    public function getNuPublicacao()
    {
        return $this->nuPublicacao;
    }

    /**
     * Get dsPublicacao
     *
     * @return string
     */
    public function getDsPublicacao()
    {
        return $this->dsPublicacao;
    }

    /**
     * Get dtPublicacao
     *
     * @return \DateTime
     */
    public function getDtPublicacao()
    {
        return $this->dtPublicacao;
    }

    /**
     * Get dtInicioVigencia
     *
     * @return \DateTime
     */
    public function getDtInicioVigencia()
    {
        return $this->dtInicioVigencia;
    }

    /**
     * Get dtFimVigencia
     *
     * @return \DateTime
     */
    public function getDtFimVigencia()
    {
        return $this->dtFimVigencia;
    }

    /**
     * Get tpPublicacao
     *
     * @return string
     */
    public function getTpPublicacao($returnNome = false)
    {
        if ($returnNome) {
            
            $nomes = array(
                'E' => 'Edital',
                'M' => 'Memorando',
                'P' => 'Portaria'
            );
            
            return $nomes[$this->tpPublicacao];
        }
        
        return $this->tpPublicacao;
    }
    
    /**
     * @return ArrayCollection<QuantidadePublicacao>
     */
    public function getQuantidades()
    {
        return $this->quantidades;
    }
    
    /**
     * @return ArrayCollection<ValorBolsaPrograma>
     */
    public function getValorBolsaProgramaAtivos()
    {
        return $this->valorBolsaPrograma->filter(function($valorBolsaPrograma){
            return $valorBolsaPrograma->isAtivo();
        });
    }
    
    /**
     * 
     * @return ArrayCollection<ValorBolsaPrograma>
     */
    public function getValorBolsaProgramaVigentes()
    {
        return $this->getValorBolsaProgramaAtivos()->filter(function($valorBolsaPrograma){
            return $valorBolsaPrograma->isVigente();
        });
    }
    
    /**
     * @return ArrayCollection<Projeto>
     */
    public function getProjetosAtivos()
    {
        return $this->projetos->filter(function($projeto){
            return $projeto->isAtivo();
        });
    }
        
    /**
     * @param int $nuPublicacao
     * @return Publicacao
     */
    public function setNuPublicacao($nuPublicacao)
    {
        $this->nuPublicacao = $nuPublicacao;
        return $this;
    }
    
    /**
     * @param string|null $dsPublicacao
     * @return Publicacao
     */
    public function setDsPublicacao($dsPublicacao = null)
    {
        $this->dsPublicacao = $dsPublicacao;
        return $this;
    }
    
    /**
     * @param \DateTime $dtPublicacao
     * @return Publicacao
     */
    public function setDtPublicacao(\DateTime $dtPublicacao)
    {
        $this->dtPublicacao = $dtPublicacao;
        return $this;
    }
    
    /**
     * @param \DateTime $dtInicioVigencia
     * @return Publicacao
     */    
    public function setDtInicioVigencia(\DateTime $dtInicioVigencia)
    {
        $this->dtInicioVigencia = $dtInicioVigencia;
        return $this;
    }
    
    /**
     * @param \DateTime $dtFimVigencia
     * @return Publicacao
     */
    public function setDtFimVigencia(\DateTime $dtFimVigencia)
    {
        $this->dtFimVigencia = $dtFimVigencia;
        return $this;
    }
    
    /**
     * @param string $tpPublicacao
     * @return Publicacao
     */
    public function setTpPublicacao($tpPublicacao)
    {
        if (!in_array($tpPublicacao, array('E', 'M', 'P'))) {
            throw new \InvalidArgumentException('Tipo de publicação inválida.');
        }
        
        $this->tpPublicacao = $tpPublicacao;
        return $this;
    }
    
    /**
     * @param QuantidadePublicacao $quantidade
     * @return Publicacao
     */
    public function addQuantidade(QuantidadePublicacao $quantidade)
    {
        if ($this->hasQuantidadeByTipo($quantidade->getTipoQuantitativoPublicacao())) {
            
            $tipoQuantitativo = $quantidade->getTipoQuantitativoPublicacao();
            
            throw new \LogicException(
                'Publicação já possui o tipo de quantidade "' . $tipoQuantitativo->getDsTipoquantidadePublicacao() . '"'
            );
        }
        
        $this->quantidades[] = $quantidade;
        return $this;
    }    
    
    /**
     * @param TipoQuantitativoPublicacao | integer $tipoQuantitativo
     * @return boolean
     */
    public function hasQuantidadeByTipo($tipoQuantitativo)
    {
        foreach ($this->quantidades as $quantidade) {
            
            if ($tipoQuantitativo instanceof TipoQuantitativoPublicacao) {
                $tipoQuantitativo = $tipoQuantitativo->getCoSeqTipoquantidPublicacao();
            }
            
            $tipoQuantidade = $quantidade->getTipoQuantitativoPublicacao();
            
            if ($quantidade->isAtivo() && $tipoQuantidade->getCoSeqTipoquantidPublicacao() == $tipoQuantitativo) {
                return true;
            }
        }
        
        return false;
    }
    
    /**
     * 
     * @param TipoQuantitativoPublicacao | integer $tipoQuantitativo
     * @return QuantidadePublicacao | null
     */
    public function getQuantidadeByTipo($tipoQuantitativo)
    {
        foreach ($this->quantidades as $quantidade) {
            
            if ($tipoQuantitativo instanceof TipoQuantitativoPublicacao) {
                $tipoQuantitativo = $tipoQuantitativo->getCoSeqTipoquantidPublicacao();
            }
            
            $tipoQuantidade = $quantidade->getTipoQuantitativoPublicacao();
            
            if ($quantidade->isAtivo() && $tipoQuantidade->getCoSeqTipoquantidPublicacao() == $tipoQuantitativo) {
                return $quantidade;
            }
        }
        
        return null;
    }
    
    /**
     * @return boolean
     */
    public function hasProjetos()
    {
        return (bool) (!$this->projetos->isEmpty());
    }
    
    /**
     * @return Publicacao
     */
    public function inativar()
    {
        if ($this->hasProjetos()) {
            throw new \DomainException('Não é possível inativar uma publicação que já possui projetos');
        }
        
        $this->stRegistroAtivo = 'N';
        
        return $this;
    }
    
    /**
     * @param boolean $prependPrograma
     * @return string
     */
    public function getDescricaoCompleta($prependPrograma = true)
    {
        $dataPublicacao = $this->getDtPublicacao()->format('d/m/Y');
        
        $publicacao = $this->getTpPublicacao(true) . ' nº ' . $this->getNuPublicacao() . ' de ' . $dataPublicacao;
        
        if ($prependPrograma) {
            return $this->programa->getDsPrograma() . ' - ' . $publicacao;
        }

        return $publicacao;
    }
    
    public function getQuantidadeMinimaGrupo()
    {
        return $this->quantidades->filter(function($quantidade){
            return $quantidade->getTipoQuantitativoPublicacao()->getCoSeqTipoquantidPublicacao() == \App\Entity\TipoQuantitativoPublicacao::QTD_MINIMA_BOLSISTAS_GRUPO;
        })->first();
    }
    
    public function getQuantidadeMaximaGrupo()
    {
        return $this->quantidades->filter(function($quantidade){
            return $quantidade->getTipoQuantitativoPublicacao()->getCoSeqTipoquantidPublicacao() == \App\Entity\TipoQuantitativoPublicacao::QTD_MAXIMA_BOLSISTAS_GRUPO;
        })->first();
    }

    /**
     * @return int
     */
    public function hasFolhaPagamentoMensalAberta()
    {
        return $this->folhasPagamento->filter(function(FolhaPagamento $folhaPagamento) {
            return $folhaPagamento->getSituacao()->getCoSeqSituacaoFolha() == SituacaoFolha::ABERTA &&
                $folhaPagamento->isAtivo() &&
                $folhaPagamento->isMensal();
        })->count(); 
    }
    
    /**
     * 
     * @return ArrayCollection<\App\Entity\FolhaPagamento>
     */
    public function getFolhasPagamentoAtivas()
    {
        return $this->folhasPagamento->filter(function ($folhaPagamento) {
            return $folhaPagamento->isAtivo();
        });
    }

    /**
     * @return bool
     * @throws \Exception
     */
    public function isVigente()
    {
        $now = new \DateTime();

        return $now > $this->dtInicioVigencia && $now < $this->dtFimVigencia;
    }
    
    /**
     * 
     * @return array
     */
    static public function getTiposPublicacao()
    {
        return [
            'Edital' => self::EDITAL,
            'Portaria' => self::PORTARIA,
            'Memorando' => self::MEMORANDO,
        ];
    }
    
    /**
     * 
     * @param string $tpPublicacao
     * @return string
     */
    static public function getDescricaoTipoByTpPublicacao($tpPublicacao)
    {
        return array_search($tpPublicacao, static::getTiposPublicacao());
    }
}