<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * FolhaPagamento
 *
 * @ORM\Table(name="DBPET.TB_FOLHA_PAGAMENTO")
 * @ORM\Entity(repositoryClass="App\Repository\FolhaPagamentoRepository")
 */
class FolhaPagamento extends AbstractEntity
{
    use \App\Traits\DeleteLogicoTrait;
    use \App\Traits\DataInclusaoTrait;
    use \App\Traits\MaskTrait;
    
    const MENSAL = 'M';
    const SUPLEMENTAR = 'S';
    
    /**
     * @var int
     *
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     * @ORM\Column(name="CO_SEQ_FOLHA_PAGAMENTO", type="integer")
     * @ORM\SequenceGenerator(sequenceName="DBPET.SQ_FOLHAPAGAM_COSEQFOLHAPAGAM", allocationSize=1, initialValue=1)
     */
    private $coSeqFolhaPagamento;

    /**
     * @var Publicacao
     *
     * @ORM\ManyToOne(targetEntity="App\Entity\Publicacao", inversedBy="folhasPagamento")
     * @ORM\JoinColumn(name="CO_PUBLICACAO", referencedColumnName="CO_SEQ_PUBLICACAO")
     */
    private $publicacao;

    /**
     * @var SituacaoFolha
     *
     * @ORM\ManyToOne(targetEntity="App\Entity\SituacaoFolha")
     * @ORM\JoinColumn(name="CO_SITUACAO_FOLHA", referencedColumnName="CO_SEQ_SITUACAO_FOLHA")
     */
    private $situacao;

    /**
     * @var string
     *
     * @ORM\Column(name="NU_MES", type="string", length=2)
     */
    private $nuMes;

    /**
     * @var string
     *
     * @ORM\Column(name="NU_ANO", type="string", length=4)
     */
    private $nuAno;

    /**
     * @var string
     *
     * @ORM\Column(name="NU_SIPAR", type="string", length=20, nullable=true)
     */
    private $nuSipar;
    
    /**
     * @var string
     *
     * @ORM\Column(name="NU_ORDEM_BANCARIA", type="string", length=12, nullable=true)
     */
    private $nuOrdemBancaria;

    /**
     * @var float
     *
     * @ORM\Column(name="VL_TOTAL", type="decimal", precision=8, scale=2, nullable=true)
     */
    private $vlTotal;
    
    /**
     *
     * @var string
     * 
     * @ORM\Column(name="TP_FOLHA_PAGAMENTO", type="string", length=1, nullable=false)
     */
    private $tpFolhaPagamento;

    /**
     *
     * @var string
     * 
     * @ORM\Column(name="DS_JUSTIFICATIVA_FOLHA_SUPLEM", type="string", length=4000, nullable=true)
     */
    private $dsJustificativa;
    
    /**
     *
     * @var string
     * 
     * @ORM\Column(name="DS_JUSTIFICATIVA_CANCELAMENTO", type="string", length=4000, nullable=false) 
     */
    private $dsJustificativaCancelamento;
    
    /**
     *
     * @var PlanejamentoMesFolha
     * 
     * @ORM\OneToOne(targetEntity="App\Entity\PlanejamentoMesFolha")
     * @ORM\JoinColumn(name="CO_PLANEJAMENTO_MES_FOLHA", referencedColumnName="CO_SEQ_PLANEJAMENTO_MES_FOLHA", nullable=true)
     */
    private $planejamentoMesFolha;
    
    /**
     *
     * @var string
     * 
     * @ORM\Column(name="ST_RETORNO_PAGAMENTO", type="string", length=1, nullable=false)
     */
    private $stRetornoPagamento;
    
    /**
     * @var ArrayCollection<ProjetoFolhaPagamento>
     * @ORM\OneToMany(targetEntity="App\Entity\ProjetoFolhaPagamento", mappedBy="folhaPagamento", cascade={"persist"})
     * @ORM\OrderBy({"coSeqProjFolhaPagam" = "ASC"})
     */
    private $projetosFolhaPagamento;
    
    /**
     * @var ArrayCollection<TramitacaoSituacaoFolha>
     * @ORM\OneToMany(targetEntity="App\Entity\TramitacaoSituacaoFolha", mappedBy="folhaPagamento", cascade={"persist"})
     * @ORM\OrderBy({"dtInclusao" = "DESC"})
     */
    private $tramitacaoSituacaoFolha;
    
    /**
     *
     * @var ArrayCollection<RetornoPagamento>
     * 
     * @ORM\OneToMany(targetEntity="\App\Entity\RetornoPagamento", mappedBy="folhaPagamento")
     * @ORM\OrderBy({"dtInclusao" = "DESC"})
     */
    private $retornoPagamento;

    /**
     * @param Publicacao $publicacao
     * @param SituacaoFolha $situacao
     * @param string $nuMes
     * @param string $nuAno
     * @param string $nuSipar
     * @param float $vlTotal
     * @param float $nuOrdemBancaria
     * @param string $dsJustificativa
     */
    public function __construct(
        Publicacao $publicacao, 
        SituacaoFolha $situacao, 
        $nuMes, 
        $nuAno, 
        $nuSipar = null, 
        $vlTotal = null,
        $nuOrdemBancaria = null,
        $dsJustificativa = null
    ) {
        $this->publicacao = $publicacao;
        $this->situacao = $situacao;
        $this->nuMes = $nuMes;
        $this->nuAno = $nuAno;
        $this->nuSipar = $nuSipar;
        $this->vlTotal = $vlTotal;
        $this->nuOrdemBancaria = $nuOrdemBancaria;
        $this->tpFolhaPagamento = self::MENSAL;
        $this->stRetornoPagamento = 'N';
        $this->dtInclusao = new \DateTime();
        $this->stRegistroAtivo = 'S';
        $this->dsJustificativa = $dsJustificativa;
        $this->projetosFolhaPagamento = new ArrayCollection();
        $this->tramitacaoSituacaoFolha = new ArrayCollection();
        $this->retornoPagamento = new ArrayCollection();
    }
    
    /**
     * Get coSeqFolhaPagamento
     *
     * @return int
     */
    public function getCoSeqFolhaPagamento()
    {
        return $this->coSeqFolhaPagamento;
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
     * Get situacao
     *
     * @return SituacaoFolha
     */
    public function getSituacao()
    {
        return $this->situacao;
    }

    /**
     * Set situacao
     *
     * @param SituacaoFolha $situacao
     * @return FolhaPagamento
     */
    public function setSituacao(SituacaoFolha $situacao)
    {
        $this->situacao = $situacao;
        return $this;
    }

    /**
     * Set nuSipar
     *
     * @param string $nuSipar
     * @return FolhaPagamento
     */
    public function setNuSipar($nuSipar)
    {
        $this->nuSipar = $nuSipar;
        return $this;
    }

    /**
     * Set nuOrdemBancaria
     *
     * @param string $nuOrdemBancaria
     * @return FolhaPagamento
     */
    public function setNuOrdemBancaria($nuOrdemBancaria)
    {
        $this->nuOrdemBancaria = strtoupper($nuOrdemBancaria);
        return $this;
    }
    
    /**
     * 
     * @param string $dsJustificativaCancelamento
     */
    public function setDsJustificativaCancelamento($dsJustificativaCancelamento)
    {
        $this->dsJustificativaCancelamento = $dsJustificativaCancelamento;
    }
        
    /**
     * @param TramitacaoSituacaoFolha $tramitacaoSituacaoFolha
     * @return FolhaPagamento
     */
    public function setHistoricoTramitacaoFolha(TramitacaoSituacaoFolha $tramitacaoSituacaoFolha)
    {
        $this->tramitacaoSituacaoFolha = $tramitacaoSituacaoFolha;
        return $this;
    }

    /**
     * 
     * @param Projeto $projeto
     * @param SituacaoProjetoFolha $situacao
     * @param PessoaPerfil $pessoaPerfil
     * @param string $stParecer
     * @param string $dsJustificativa
     * @param string $stDeclaracao
     */
    public function addProjetoFolhaPagamento(
        Projeto $projeto,
        SituacaoProjetoFolha $situacao,
        PessoaPerfil $pessoaPerfil,
        $stParecer = null,
        $dsJustificativa = null,
        $stDeclaracao = 'S'
    ) {
        if (!$this->hasProjetoFolhaPagamento($projeto)) {
            $this->projetosFolhaPagamento->add(
                new ProjetoFolhaPagamento(
                    $projeto,
                    $this,
                    $situacao,
                    $pessoaPerfil,
                    $stParecer,
                    $dsJustificativa,
                    $stDeclaracao
                )
            );
        }
    }

    /**
     * @param ProjetoFolhaPagamento $projetoFolhaPagamento
     */
    public function addRawProjetoFolhaPagamento(ProjetoFolhaPagamento $projetoFolhaPagamento)
    {
        if (!$this->hasProjetoFolhaPagamento($projetoFolhaPagamento->getProjeto())) {
            $this->projetosFolhaPagamento->add($projetoFolhaPagamento);
        }
    }
    
    /**
     * 
     * @param \App\Entity\Projeto $projeto
     * @return boolean
     */
    public function hasProjetoFolhaPagamento(Projeto $projeto)
    {
        return $this->projetosFolhaPagamento->exists(function ($key, $projetoFolhaPagamento) use ($projeto) {
            return $projeto == $projetoFolhaPagamento->getProjeto();
        });
    }
    
    /**
     * 
     * @param PlanejamentoMesFolha $planejamentoMesFolha
     * @return FolhaPagamento
     */
    public function setPlanejamentoMesFolha(PlanejamentoMesFolha $planejamentoMesFolha)
    {
        $this->planejamentoMesFolha = $planejamentoMesFolha;
        return $this;
    }
    
    /**
     * 
     * @param \App\Entity\Projeto $projeto
     * @return ProjetoFolhaPagamento|null
     */
    public function getProjetoFolhaPagamentoByProjeto(Projeto $projeto)
    {
        return $this->projetosFolhaPagamento->filter(function ($projetoFolhaPagamento) use ($projeto) {
            return $projeto == $projetoFolhaPagamento->getProjeto();
        })->first();
    }

    /**
     * Get nuMes
     *
     * @return string
     */
    public function getNuMes()
    {
        return $this->nuMes;
    }

    /**
     * Get nuAno
     *
     * @return string
     */
    public function getNuAno()
    {
        return $this->nuAno;
    }
    
    /**
     * Get nuSipar
     *
     * @return string
     */
    public function getNuSipar()
    {
        return $this->nuSipar;
    }
    
    /**
     * Get nuSipar sem máscara
     *
     * @return string
     */
    public function getNuSiparClean()
    {
        return $this->unmask($this->nuSipar);
    }
    
    /**
     * Get nuOrdemBancaria
     *
     * @return string
     */
    public function getNuOrdemBancaria()
    {
        return $this->nuOrdemBancaria;
    }

    /**
     * Get vlTotal
     *
     * @return float
     */
    public function getVlTotal()
    {
        return $this->vlTotal;
    }
    
    /**
     * 
     * @return string
     */
    public function getDsJustificativa()
    {
        return $this->dsJustificativa;
    }
    
    /**
     * 
     * @return string
     */
    public function getDsJustificativaCancelamento()
    {
        return $this->dsJustificativaCancelamento;
    }
            
    /**
     * @return ArrayCollection<ProjetoFolhaPagamento>
     */
    public function getProjetosFolhaPagamento()
    {
        return $this->projetosFolhaPagamento;
    }
    
    /**
     * @return ArrayCollection<TramitacaoSituacaoFolha>
     */
    public function getTramitacaoSituacaoFolha()
    {
        return $this->tramitacaoSituacaoFolha;
    }
    
    /**     
     * @return string
     */
    public function getNuMesAno()
    {
        return $this->getNuMes() . '/' . $this->getNuAno();
    }

    /**
     * @return ArrayCollection<ProjetoFolhaPagamento>
     */
    public function getProjetosFolhaPagamentoAtivos()
    {
        return $this->projetosFolhaPagamento->filter(function (ProjetoFolhaPagamento $item) {
            return $item->isAtivo();
        });
    }
    
    /**
     * @return ArrayCollection<ProjetoFolhaPagamento>
     */
    public function getProjetosFolhaPagamentoAtivosAprovados()
    {
        return $this->projetosFolhaPagamento->filter(function (ProjetoFolhaPagamento $item) {
            return $item->isAtivo() && $item->isAprovada();
        });
    }
    
    /**
     * @return ArrayCollection<ProjetoFolhaPagamento>
     */
    public function getProjetosAtivos()
    {
        return $this->projetosFolhaPagamento->filter(function (ProjetoFolhaPagamento $item) {
            return $item->isAtivo() && $item->getProjeto()->isAtivo();
        });
    }
    
    /**
     * @return ArrayCollection<ProjetoFolhaPagamento>
     */
    public function getProjetosCancelados()
    {
        return $this->projetosFolhaPagamento->filter(function (ProjetoFolhaPagamento $item) {
            return $item->getStParecer() == 'N';
        });
    }
        
    /**
     * @return string
     */
    public function getReferenciaExtenso()
    {
        setlocale(LC_ALL, "pt_BR", "pt_BR.utf-8", "portuguese");

        $referencia = strftime('%B/%Y', strtotime('01-' . $this->nuMes .'-' . $this->nuAno));

        if (!mb_detect_encoding($referencia, 'UTF-8', true)) {
            $referencia = utf8_encode($referencia);
        }

        return $referencia;
    }
    
    /**
     * @return ArrayCollection<ProjetoFolhaPagamento>
     */
    public function getProjetosFolhaPagamentoHomologadas()
    {
        return $this->projetosFolhaPagamento->filter(function(ProjetoFolhaPagamento $item) {
            return $item->isHomologada() && $item->isAtivo();
        });
    }
    
    /**
     * 
     * @return ArrayCollection<RetornoPagamento>
     */
    public function getRetornosPagamentosAtivos()
    {
        return $this->retornoPagamento->filter(function(RetornoPagamento $retornoPagamento) {
            return $retornoPagamento->isAtivo();
        });
    }
    
    /**
     * 
     * @return string
     */
    public function getDescricaoCompletaFolha()
    {
        return $this->getReferenciaExtenso() . ' - ' . $this->getDescricaoTipoFolha() . (($this->getNuSipar()) ? ' - Nº SEI: ' . $this->getNuSipar() : null);
    }

    /**
     * @return boolean
     */
    public function hasProjetosPendentesHomologacao()
    {
        $result = false;
        
        foreach ($this->getProjetosFolhaPagamentoAtivos() as $projetoFolha) {
            
            if ($projetoFolha->isAutorizada()) {
                $result = true;
                break;
            }
        }
        
        return $result;
    }
    
    /**
     * @return boolean
     */
    public function isFechada()
    {
        return $this->situacao->getCoSeqSituacaoFolha() == SituacaoFolha::FECHADA;
    }
    
    /**
     * @return boolean
     */
    public function isAberta()
    {
        return $this->situacao->getCoSeqSituacaoFolha() == SituacaoFolha::ABERTA;
    }
    
    /**
     * @return boolean
     */
    public function isEnviadaFundo()
    {
        return $this->situacao->getCoSeqSituacaoFolha() == SituacaoFolha::ENVIADA_FUNDO;
    }
    
    /**
     * @return boolean
     */
    public function isHomologada()
    {
        return $this->situacao->getCoSeqSituacaoFolha() == SituacaoFolha::HOMOLOGADA;
    }
    
    /**
     * @return boolean
     */
    public function isOrdemBancaria()
    {
        return $this->situacao->getCoSeqSituacaoFolha() == SituacaoFolha::ORDEM_BANCARIA_EMITIDA;
    }
    
    /**
     * 
     * @return boolean
     */
    public function isMensal()
    {
        return $this->tpFolhaPagamento == self::MENSAL;
    }
    
    /**
     * @return FolhaPagamento
     */
    public function calcularValorTotal()
    {
        $sum = 0;
        foreach ($this->getProjetosFolhaPagamentoAtivos() as $projetoFolha) {
            $sum += $projetoFolha->getValorTotal();
        }
        $this->vlTotal = $sum;
        return $this;
    }    
    
    public function setTpFolhaMensal()
    {
        $this->tpFolhaPagamento = self::MENSAL;
    }
    
    public function setTpFolhaSuplementar()
    {
        $this->tpFolhaPagamento = self::SUPLEMENTAR;
    }
    
    /**
     * @return ArrayCollection<TramitacaoSituacaoFolha>
     */
    public function getHistoricoTramitacaoFolha()
    {
        return $this->tramitacaoSituacaoFolha->toArray();
    }
    
    public function addTramitacaoSituacao(TramitacaoSituacaoFolha $tramitacaoSituacaoFolha)
    {
        $this->tramitacaoSituacaoFolha->add($tramitacaoSituacaoFolha);
    }

    public function inativaAllAutorizacoes()
    {
        foreach ($this->projetosFolhaPagamento as $projetoFolhaPagamento) {
            $projetoFolhaPagamento->inativaAutorizacoes();
        }
    }
    
    public function retornaPagamento()
    {
        $this->stRetornoPagamento = 'S';
    }
    
    public function cancelaRetornoPagamento()
    {
        $this->stRetornoPagamento = 'N';
    }
    
    /**
     * 
     * @return boolean
     */
    public function isRetornoPagamento()
    {
        return 'S' === $this->stRetornoPagamento;
    }
    
    /**
     * 
     * @return array
     */
    static public function getTiposFolha()
    {
        return [
            'Mensal' => self::MENSAL,
            'Suplementar' => self::SUPLEMENTAR,
        ];
    }

    /**
     * 
     * @return string
     */
    public function getDescricaoTipoFolha()
    {
        switch ($this->tpFolhaPagamento) {
            case self::MENSAL: return 'Mensal';
            case self::SUPLEMENTAR: return 'Suplementar';
        }
    }
    
    /**
     * 
     * @return boolean
     */
    public function isReadyToCancel()
    {
        if ($this->isMensal()) {
            return false;
        } else {
            return $this->isAberta() || $this->isFechada() || $this->isHomologada();
        }
    }

}
