<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use AppBundle\Cpb\RetornoPagamento\Header;

/**
 * @ORM\Entity
 * @ORM\Table(name="DBPET.TB_CABECALHO_ARQUIVO_RETORNO")
 * @ORM\Entity(repositoryClass="\AppBundle\Repository\CabecalhoArquivoRetornoPagamentoRepository")
 */
class CabecalhoArquivoRetornoPagamento
{
    /**
     *
     * @var integer
     * 
     * @ORM\Id
     * @ORM\Column(name="CO_SEQ_CABECALHO_ARQ_RETORNO", type="integer")
     * @ORM\GeneratedValue(strategy="SEQUENCE")
     * @ORM\SequenceGenerator(sequenceName="DBPET.SQ_CABARQRET_COSEQCABARQRET", initialValue=1, allocationSize=1)
     */
    private $coSeqCabecalhoArqRetorno;
    
    /**
     *
     * @var RetornoPagamento
     * 
     * @ORM\OneToOne(targetEntity="\AppBundle\Entity\RetornoPagamento")
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
     * @ORM\Column(name="NU_REGISTRO_CABEC_ARQ_RETORNO", type="string", length=7, nullable=false)
     */
    private $nuRegistroCabecArqRetorno;
    
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
     * @ORM\Column(name="NU_ID_BANCO", type="string", length=3, nullable=false)
     */
    private $nuIdBanco;
    
    /**
     *
     * @var string
     * 
     * @ORM\Column(name="NU_MEIO_PAGAMENTO", type="string", length=2, nullable=false)
     */
    private $nuMeioPagamento;
    
    /**
     *
     * @var \DateTime
     * 
     * @ORM\Column(name="DT_GRAVACAO_LOTE", type="datetime", nullable=false)
     */
    private $dtGravacaoLote;
    
    /**
     *
     * @var string
     * 
     * @ORM\Column(name="NU_SEQUENCIAL_LOTE", type="string", length=2, nullable=false)
     */
    private $nuSequencialLote;
    
    /**
     *
     * @var string
     * 
     * @ORM\Column(name="NU_CODIGO_CONVENIO", type="string", length=6, nullable=false)
     */
    private $nuCodigoConvenio;
    
    /**
     *
     * @var string
     * 
     * @ORM\Column(name="NU_CONTROLE_TRANSMISSAO", type="string", length=6, nullable=false)
     */
    private $nuControleTransmissao;
    
    /**
     * 
     * @param RetornoPagamento $retornoPagamento
     * @param Header $header
     */
    public function __construct(RetornoPagamento $retornoPagamento, Header $header)
    {
        $this->retornoPagamento = $retornoPagamento;
        $this->fromHeader($header);
    }
    
    /**
     * 
     * @return integer
     */
    public function getCoSeqCabecalhoArqRetorno()
    {
        return $this->coSeqCabecalhoArqRetorno;
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
    public function getNuRegistroCabecArqRetorno()
    {
        return $this->nuRegistroCabecArqRetorno;
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
    public function getNuIdBanco()
    {
        return $this->nuIdBanco;
    }

    /**
     * 
     * @return string
     */
    public function getNuMeioPagamento()
    {
        return $this->nuMeioPagamento;
    }

    /**
     * 
     * @return \DateTime
     */
    public function getDtGravacaoLote()
    {
        return $this->dtGravacaoLote;
    }

    /**
     * 
     * @return string
     */
    public function getNuSequencialLote()
    {
        return $this->nuSequencialLote;
    }

    /**
     * 
     * @return string
     */
    public function getNuCodigoConvenio()
    {
        return $this->nuCodigoConvenio;
    }

    /**
     * 
     * @return string
     */
    public function getNuControleTransmissao()
    {
        return $this->nuControleTransmissao;
    }
    
    /**
     * 
     * @param Header $header
     */
    private function fromHeader(Header $header)
    {
        $this->nuTipoRegistro = $header->getCsTipoRegistro();
        $this->nuRegistroCabecArqRetorno = $header->getNuSeqRegistro();
        $this->nuTipoLote = $header->getCsTipoLote();
        $this->nuIdBanco = $header->getIdBanco();
        $this->nuMeioPagamento = $header->getCsMeioPagto();
        $this->dtGravacaoLote = \DateTime::createFromFormat('Ymd', $header->getDtGravacaoLote());
        $this->nuSequencialLote = $header->getNuSeqLote();
        $this->nuCodigoConvenio = $header->getNuCodConv();
        $this->nuControleTransmissao = $header->getNuCtrlTrans();
    }
}
