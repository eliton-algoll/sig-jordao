<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use AppBundle\Cpb\RetornoCadastro\Header;

/**
 * @ORM\Entity
 * @ORM\Table(name="DBPET.TB_CABECALHO_RET_CRIA_CONTA")
 * @ORM\Entity(repositoryClass="\AppBundle\Repository\CabecalhoRetornoCriacaoContaRepository")
 */
class CabecalhoRetornoCriacaoConta
{
    /**
     *
     * @var integer
     * 
     * @ORM\Id
     * @ORM\Column(name="CO_SEQ_CABECALHO_RET_CRIA_CONT", type="integer")
     * @ORM\GeneratedValue(strategy="SEQUENCE")
     * @ORM\SequenceGenerator(sequenceName="DBPET.SQ_CABRETCRIACON_COSEQCABRETCC", initialValue=1, allocationSize=1)
     */
    private $coSeqCabecalhoRetCriaCont;
    
    /**
     *
     * @var RetornoCriacaoConta
     * 
     * @ORM\OneToOne(targetEntity="\AppBundle\Entity\RetornoCriacaoConta")
     * @ORM\JoinColumn(name="CO_RETORNO_CRIACAO_CONTA", referencedColumnName="CO_SEQ_RETORNO_CRIACAO_CONTA", nullable=false)
     */
    private $retornoCriacaoConta;
    
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
     * @ORM\Column(name="NU_SEQUENCIAL_REGISTRO", type="string", length=7, nullable=false)
     */
    private $nuSequencialRegistro;
    
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
     * @param RetornoCriacaoConta $retornoCriacaoConta
     * @param Header $header
     */
    public function __construct(
        RetornoCriacaoConta $retornoCriacaoConta,
        Header $header
    ) {
        $this->retornoCriacaoConta = $retornoCriacaoConta;
        $this->fromHeader($header);
    }

    /**
     * 
     * @return integer
     */
    public function getCoSeqCabecalhoRetCriaCont()
    {
        return $this->coSeqCabecalhoRetCriaCont;
    }

    /**
     * 
     * @return RetornoCriacaoConta
     */
    public function getRetornoCriacaoConta()
    {
        return $this->retornoCriacaoConta;
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
    public function getNuSequencialRegistro()
    {
        return $this->nuSequencialRegistro;
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
        $this->nuSequencialRegistro = $header->getNuSeqRegistro();
        $this->nuTipoLote = $header->getCsTipoLote();
        $this->nuIdBanco = $header->getIdBanco();
        $this->nuMeioPagamento = $header->getCsMeioPagto();
        $this->dtGravacaoLote = \DateTime::createFromFormat('Ymd', $header->getDtGravacaoLote());
        $this->nuSequencialLote = $header->getNuSeqLote();
        $this->nuCodigoConvenio = $header->getNuCodConv();
        $this->nuControleTransmissao = $header->getNuCtrlTrans();
    }
}
