<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use AppBundle\Cpb\RetornoPagamento\Trailer;

/**
 * @ORM\Entity
 * @ORM\Table(name="DBPET.TB_RODAPE_ARQUIVO_RETORNO")
 * @ORM\Entity(repositoryClass="\AppBundle\Repository\RodapeArquivoRetornoPagamentoRepository")
 */
class RodapeArquivoRetornoPagamento
{
    /**
     *
     * @var integer
     * 
     * @ORM\Id
     * @ORM\Column(name="CO_SEQ_RODAPE_ARQUIVO_RETORNO", type="integer")
     * @ORM\GeneratedValue(strategy="SEQUENCE")
     * @ORM\SequenceGenerator(sequenceName="DBPET.SQ_RODARQRET_COSEQRODARQRETORN", initialValue=1, allocationSize=1)
     */
    private $coSeqRodapeArquivoRetorno;
    
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
     * @ORM\Column(name="NU_TIPO_REGISTRO", type="string", length=1)
     */
    private $nuTipoRegistro;
    
    /**
     *
     * @var string
     * 
     * @ORM\Column(name="NU_REGISTRO_RODAPE_ARQ_RETORNO", type="string", length=7)
     */
    private $nuRegistroRodapeArqRetorno;
    
    /**
     *
     * @var string
     * 
     * @ORM\Column(name="NU_TIPO_LOTE", type="string", length=2)
     */
    private $nuTipoLote;
    
    /**
     *
     * @var string
     * 
     * @ORM\Column(name="NU_ID_BANCO", type="string", length=3)
     */
    private $nuIdBanco;
    
    /**
     *
     * @var string
     * 
     * @ORM\Column(name="QT_REGISTRO_DETALHE", type="string", length=8)
     */
    private $qtRegistroDetalhe;
    
    /**
     *
     * @var string
     * 
     * @ORM\Column(name="VL_REGISTRO_DETALHE", type="string", length=17)
     */
    private $vlRegistroDetalhe;
    
    /**
     *
     * @var string
     * 
     * @ORM\Column(name="NU_SEQUENCIAL_LOTE", type="string", length=2)
     */
    private $nuSequencialLote;
    
    /**
     * 
     * @param RetornoPagamento $retornoPagamento
     * @param Trailer $trailer
     */
    public function __construct(RetornoPagamento $retornoPagamento, Trailer $trailer)
    {
        $this->retornoPagamento = $retornoPagamento;
        $this->fromTrailer($trailer);
    }
    
    /**
     * 
     * @return integer
     */
    public function getCoSeqRodapeArquivoRetorno()
    {
        return $this->coSeqRodapeArquivoRetorno;
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
    public function getNuRegistroRodapeArqRetorno()
    {
        return $this->nuRegistroRodapeArqRetorno;
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
    public function getQtRegistroDetalhe()
    {
        return $this->qtRegistroDetalhe;
    }

    /**
     * 
     * @return string
     */
    public function getVlRegistroDetalhe()
    {
        return $this->vlRegistroDetalhe;
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
     * @param Trailer $trailer
     */
    private function fromTrailer(Trailer $trailer)
    {
        $this->nuTipoRegistro = $trailer->getCsTipoRegistro();
        $this->nuRegistroRodapeArqRetorno = $trailer->getNuSeqRegistro();
        $this->nuTipoLote = $trailer->getCsTipoLote();
        $this->nuIdBanco = $trailer->getIdBanco();
        $this->qtRegistroDetalhe = $trailer->getQtRegDetalhe();
        $this->vlRegistroDetalhe = $trailer->getVlRegDetalhe();
        $this->nuSequencialLote = $trailer->getNuSeqLote();
    }
}
