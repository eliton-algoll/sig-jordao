<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Cpb\RetornoPagamento\Detalhe;
use App\Cpb\DicionarioCpb;

/**
 * @ORM\Entity
 * @ORM\Table(name="DBPETINFOSD.TB_DETALHE_ARQUIVO_RETORNO")
 * @ORM\Entity(repositoryClass="App\Repository\DetalheArquivoRetornoPagamentoRepository")
 */
class DetalheArquivoRetornoPagamento
{
    /**
     *
     * @var integer
     * 
     * @ORM\Id
     * @ORM\Column(name="CO_SEQ_DETALHE_ARQUIVO_RETORNO", type="integer")
     * @ORM\GeneratedValue(strategy="SEQUENCE")
     * @ORM\SequenceGenerator(sequenceName="DBPETINFOSD.SQ_DETARQRET_COSEQDETARQRETOR", initialValue=1, allocationSize=1)
     */
    private $coSeqDetalheArquivoRetorno;
    
    /**
     *
     * @var RetornoPagamento
     * 
     * @ORM\ManyToOne(targetEntity="\App\Entity\RetornoPagamento")
     * @ORM\JoinColumn(name="CO_RETORNO_PAGAMENTO", referencedColumnName="CO_SEQ_RETORNO_PAGAMENTO", nullable=false)
     */
    private $retornoPagamento;
    
    /**
     *
     * @var string
     * 
     * @ORM\Column(name="NU_TIPO_REGISTRO", type="string", length=1, nullable=false)
     */
    private $nuTipoRegistro;
    
    /**
     *
     * @var string
     * 
     * @ORM\Column(name="NU_REGISTRO_ARQUIVO_RETORNO", type="string", length=7, nullable=false)
     */
    private $nuRegistroArquivoRetorno;
    
    /**
     *
     * @var string
     * 
     * @ORM\Column(name="NU_TIPO_LOTE", type="string", length=2, nullable=false)
     */
    private $nuTipoLote;
    
    /**
     *
     * @var string
     * 
     * @ORM\Column(name="NU_NIB", type="string", length=10)
     */
    private $nuNib;
    
    /**
     *
     * @var \DateTime
     * 
     * @ORM\Column(name="DT_FIM_PERIODO", type="datetime")
     */
    private $dtFimPeriodo;
    
    /**
     *
     * @var \DateTime
     * 
     * @ORM\Column(name="DT_INICIO_PERIODO", type="datetime")
     */
    private $dtInicioPeriodo;
    
    /**
     *
     * @var string
     * 
     * @ORM\Column(name="NU_NATUREZA_CREDITO", type="string", length=2)
     */
    private $nuNaturezaCredito;
    
    /**
     *
     * @var \DateTime
     * 
     * @ORM\Column(name="DT_MOVIMENTO_CREDITO", type="datetime")
     */
    private $dtMovimentoCredito;
    
    /**
     *
     * @var string
     * 
     * @ORM\Column(name="NU_ID_ORGAO_PAGADOR", type="string", length=6)
     */
    private $nuIdOrgaoPagador;
    
    /**
     *
     * @var string
     * 
     * @ORM\Column(name="VL_CREDITO", type="string", length=12)
     */
    private $vlCredito;
    
    /**
     *
     * @var string
     * 
     * @ORM\Column(name="NU_UNIDADE_MONETARIA", type="string", length=1, nullable=false)
     */
    private $nuUnidadeMonetaria;
    
    /**
     *
     * @var \DateTime
     * 
     * @ORM\Column(name="DT_FIM_VALIDADE", type="datetime")
     */
    private $dtFimValidade;
    
    /**
     *
     * @var \DateTime
     * 
     * @ORM\Column(name="DT_INICIO_VALIDADE", type="datetime")
     */
    private $dtInicioValidade;
    
    /**
     *
     * @var string
     * 
     * @ORM\Column(name="NU_INDICADOR_BLOQUEIO", type="string", length=1)
     */
    private $nuIndicadorBloqueio;
    
    /**
     *
     * @var string
     * 
     * @ORM\Column(name="NU_CONTA", type="string", length=10)
     */
    private $nuConta;
    
    /**
     *
     * @var string
     * 
     * @ORM\Column(name="NU_ORIGEM_ORCAMENTO", type="string", length=2)
     */
    private $nuOrigemOrcamento;
    
    /**
     *
     * @var string
     * 
     * @ORM\Column(name="NU_INDICACAO_PIONEIRA", type="string", length=1)
     */
    private $nuIndicacaoPioneira;
    
    /**
     *
     * @var string
     * 
     * @ORM\Column(name="NU_NIT", type="string", length=11)
     */
    private $nuNit;
    
    /**
     *
     * @var string
     * 
     * @ORM\Column(name="NU_TIPO_CREDITO", type="string", length=2)
     */
    private $nuTipoCredito;
    
    /**
     *
     * @var string
     * 
     * @ORM\Column(name="NU_ESPECIE", type="string", length=3)
     */
    private $nuEspecie;
    
    /**
     *
     * @var string
     * 
     * @ORM\Column(name="NU_SEQUENCIAL_REMESSA", type="string", length=6)
     */
    private $nuSequencialRemessa;
    
    /**
     *
     * @var string
     * 
     * @ORM\Column(name="NU_SEQUENCIAL_CREDITO", type="string", length=11)
     */
    private $nuSequencialCredito;
    
    /**
     *
     * @var string
     * 
     * @ORM\Column(name="NU_SITUACAO_CREDITO", type="string", length=4)
     */
    private $nuSituacaoCredito;
    
    /**
     *
     * @var string
     * 
     * @ORM\Column(name="NU_SITUACAO_REMESSA", type="string", length=1)
     */
    private $nuSituacaoRemessa;
    
    /**
     *
     * @var string
     * 
     * @ORM\Column(name="NU_CONTROLE_CREDITO", type="string", length=7)
     */
    private $nuControleCredito;
    
    /**
     *
     * @var AutorizacaoFolha
     * 
     * @ORM\OneToOne(targetEntity="\App\Entity\AutorizacaoFolha", mappedBy="detalheArquivoRetornoPagamento")
     */
    private $autorizacaoFolha;
    
    /**
     * 
     * @param RetornoPagamento $retornoPagamento
     * @param Detalhe $detalhe
     */
    public function __construct(RetornoPagamento $retornoPagamento, Detalhe $detalhe)
    {
        $this->retornoPagamento = $retornoPagamento;
        $this->fromDetalhe($detalhe);
    }
    
    /**
     * 
     * @return integer
     */
    public function getCoSeqDetalheArquivoRetorno()
    {
        return $this->coSeqDetalheArquivoRetorno;
    }

    /**
     * 
     * @return RetornoPagamento
     */
    public function getRetornoPagamento()
    {
        return $this->retornoPagamento;
    }

    /**
     * 
     * @return string
     */
    public function getNuTipoRegistro()
    {
        return $this->nuTipoRegistro;
    }

    /**
     * 
     * @return string
     */
    public function getNuRegistroArquivoRetorno()
    {
        return $this->nuRegistroArquivoRetorno;
    }
    
    /**
     * 
     * @return string
     */
    public function getNuTipoLote()
    {
        return $this->nuTipoLote;
    }

    /**
     * 
     * @return string
     */
    public function getNuNib()
    {
        return $this->nuNib;
    }

    /**
     * 
     * @return \DateTime
     */
    public function getDtFimPeriodo()
    {
        return $this->dtFimPeriodo;
    }

    /**
     * 
     * @return \DateTime
     */
    public function getDtInicioPeriodo()
    {
        return $this->dtInicioPeriodo;
    }

    /**
     * 
     * @return string
     */
    public function getNuNaturezaCredito()
    {
        return $this->nuNaturezaCredito;
    }

    /**
     * 
     * @return \DateTime
     */
    public function getDtMovimentoCredito()
    {
        return $this->dtMovimentoCredito;
    }

    /**
     * 
     * @return string
     */
    public function getNuIdOrgaoPagador()
    {
        return $this->nuIdOrgaoPagador;
    }

    /**
     * 
     * @return string
     */
    public function getVlCredito()
    {
        return $this->vlCredito;
    }

    /**
     * 
     * @return string
     */
    public function getNuUnidadeMonetaria()
    {
        return $this->nuUnidadeMonetaria;
    }

    /**
     * 
     * @return \DateTime
     */
    public function getDtFimValidade()
    {
        return $this->dtFimValidade;
    }

    /**
     * 
     * @return \DateTime
     */
    public function getDtInicioValidade()
    {
        return $this->dtInicioValidade;
    }

    /**
     * 
     * @return string
     */
    public function getNuIndicadorBloqueio()
    {
        return $this->nuIndicadorBloqueio;
    }

    /**
     * 
     * @return string
     */
    public function getNuConta()
    {
        return $this->nuConta;
    }

    /**
     * 
     * @return string
     */
    public function getNuOrigemOrcamento()
    {
        return $this->nuOrigemOrcamento;
    }

    /**
     * 
     * @return string
     */
    public function getNuIndicacaoPioneira()
    {
        return $this->nuIndicacaoPioneira;
    }

    /**
     * 
     * @return string
     */
    public function getNuNit()
    {
        return $this->nuNit;
    }

    /**
     * 
     * @return string
     */
    public function getNuTipoCredito()
    {
        return $this->nuTipoCredito;
    }

    /**
     * 
     * @return string
     */
    public function getNuEspecie()
    {
        return $this->nuEspecie;
    }

    /**
     * 
     * @return string
     */
    public function getNuSequencialRemessa()
    {
        return $this->nuSequencialRemessa;
    }

    /**
     * 
     * @return string
     */
    public function getNuSequencialCredito()
    {
        return $this->nuSequencialCredito;
    }

    /**
     * 
     * @return string
     */
    public function getNuSituacaoCredito()
    {
        return $this->nuSituacaoCredito;
    }

    /**
     * 
     * @return string
     */
    public function getNuSituacaoRemessa()
    {
        return $this->nuSituacaoRemessa;
    }

    /**
     * 
     * @return string
     */
    public function getNuControleCredito()
    {
        return $this->nuControleCredito;
    }
    
    /**
     * 
     * @return AutorizacaoFolha
     */
    public function getAutorizacaoFolha()
    {
        return $this->autorizacaoFolha;
    }
        
    /**
     * 
     * @return string
     */
    public function getDescricaoSituacaoCredito()
    {
        return current(array_keys(DicionarioCpb::getValues('SIT-CMD'), $this->getNuSituacaoCredito()));
    }
    
    /**
     * 
     * @param Detalhe $detalhe
     */
    private function fromDetalhe(Detalhe $detalhe)
    {
        $this->nuTipoRegistro = $detalhe->getCsTipoRegistro();
        $this->nuRegistroArquivoRetorno = $detalhe->getNuSeqRegistro();
        $this->nuTipoLote = $detalhe->getCsTipoLote();
        $this->nuNib = $detalhe->getNuNib();
        $this->dtFimPeriodo = \DateTime::createFromFormat('Ymd', $detalhe->getDtFimPeriodo());
        $this->dtInicioPeriodo = \DateTime::createFromFormat('Ymd', $detalhe->getDtIniPeriodo());
        $this->nuNaturezaCredito = $detalhe->getCsNaturCredito();
        $this->dtMovimentoCredito = \DateTime::createFromFormat('Ymd', $detalhe->getDtMovCredito());
        $this->nuIdOrgaoPagador = $detalhe->getIdOrgaoPagador();
        $this->vlCredito = $detalhe->getVlCredito();
        $this->nuUnidadeMonetaria = $detalhe->getCsUnidMonet();
        $this->dtFimValidade = \DateTime::createFromFormat('Ymd', $detalhe->getDtFimValidade());
        $this->dtInicioValidade = \DateTime::createFromFormat('Ymd', $detalhe->getDtIniValidade());
        $this->nuIndicadorBloqueio = $detalhe->getInCrBloqueado();
        $this->nuConta = $detalhe->getNuConta();
        $this->nuOrigemOrcamento = $detalhe->getCsOrigemOrcamento();
        $this->nuIndicacaoPioneira = $detalhe->getInPioneira();
        $this->nuNit = $detalhe->getIdNit();
        $this->nuTipoCredito = $detalhe->getCsTipoCredito();
        $this->nuEspecie = $detalhe->getCsEspecie();
        $this->nuSequencialRemessa = $detalhe->getNuSeqRms();
        $this->nuSequencialCredito = $detalhe->getNuSeqCredito();
        $this->nuSituacaoCredito = $detalhe->getSitCmd();
        $this->nuSituacaoRemessa = $detalhe->getSitRms();
        $this->nuControleCredito = $detalhe->getNuCtrlCred();
    }
}
