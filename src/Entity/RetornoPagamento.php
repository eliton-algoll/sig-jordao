<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="DBPETINFOSD.TB_RETORNO_PAGAMENTO")
 * @ORM\Entity(repositoryClass="App\Repository\RetornoPagamentoRepository")
 */
class RetornoPagamento
{
    use \App\Traits\DataInclusaoTrait;
    use \App\Traits\DeleteLogicoTrait;
    
    /**
     *
     * @var integer
     * 
     * @ORM\Id
     * @ORM\Column(name="CO_SEQ_RETORNO_PAGAMENTO", type="integer")
     * @ORM\GeneratedValue(strategy="SEQUENCE")
     * @ORM\SequenceGenerator(sequenceName="DBPETINFOSD.SQ_RETORNOPAGTO_COSEQRETPAGTO", initialValue=1, allocationSize=1)
     */
    private $coSeqRetornoPagamento;
    
    /**
     *
     * @var FolhaPagamento
     * 
     * @ORM\ManyToOne(targetEntity="\App\Entity\FolhaPagamento")
     * @ORM\JoinColumn(name="CO_FOLHA_PAGAMENTO", referencedColumnName="CO_SEQ_FOLHA_PAGAMENTO", nullable=false)
     */
    private $folhaPagamento;
    
    /**
     *
     * @var string
     * 
     * @ORM\Column(name="NO_ARQUIVO_ORIGINAL", type="string", length=200, nullable=false)
     */
    private $noArquivoOriginal;
    
    /**
     *
     * @var string
     * 
     * @ORM\Column(name="NO_ARQUIVO_UPLOAD", type="string", length=200, nullable=false)
     */
    private $noArquivoUpload;
    
    /**
     *
     * @var integer
     * 
     * @ORM\Column(name="QT_PARTICIPANTE", type="integer", nullable=false)
     */
    private $qtParticipante;
    
    /**
     *
     * @var integer
     * 
     * @ORM\Column(name="QT_PARTICIPANTE_PAGO", type="integer", nullable=false)
     */
    private $qtParticipantePago;
    
    /**
     *
     * @var integer
     * 
     * @ORM\Column(name="QT_PARTICIPANTE_NAO_PAGO", type="integer", nullable=false)
     */
    private $qtParticipanteNaoPago;
    
    /**
     *
     * @var CabecalhoArquivoRetornoPagamento
     * 
     * @ORM\OneToOne(targetEntity="\App\Entity\CabecalhoArquivoRetornoPagamento", mappedBy="retornoPagamento")
     */
    private $cabecalhoArquivoRetornoPagamento;
    
    /**
     *
     * @var DetalheArquivoRetornoPagamento[]
     * 
     * @ORM\OneToMany(targetEntity="\App\Entity\DetalheArquivoRetornoPagamento", mappedBy="retornoPagamento")
     */
    private $detalheArquivoRetornoPagamento;
    
    /**
     *
     * @var RodapeArquivoRetornoPagamento
     * 
     * @ORM\OneToOne(targetEntity="\App\Entity\RodapeArquivoRetornoPagamento", mappedBy="retornoPagamento")
     */
    private $rodapeArquivoRetornoPagamento;
    
    /**
     * 
     * @param FolhaPagamento $folhaPagamento
     * @param string $noArquivoOriginal
     * @param string $noArquivoUpload
     * @param integer $qtParticipante
     * @param integer $qtParticipantePago
     * @param integer $qtParticipanteNaoPago
     */
    public function __construct(
        FolhaPagamento $folhaPagamento,
        $noArquivoOriginal,
        $noArquivoUpload,
        $qtParticipante,
        $qtParticipantePago,
        $qtParticipanteNaoPago
    ) {
        $this->folhaPagamento = $folhaPagamento;
        $this->noArquivoOriginal = $noArquivoOriginal;
        $this->noArquivoUpload = $noArquivoUpload;
        $this->qtParticipante = $qtParticipante;
        $this->qtParticipantePago = $qtParticipantePago;
        $this->qtParticipanteNaoPago = $qtParticipanteNaoPago;
        $this->stRegistroAtivo = 'S';
        $this->dtInclusao = new \DateTime();
    }
    
    /**
     * 
     * @return integer
     */
    public function getCoSeqRetornoPagamento()
    {
        return $this->coSeqRetornoPagamento;
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
    public function getNoArquivoOriginal()
    {
        return $this->noArquivoOriginal;
    }

    /**
     * 
     * @return string
     */
    public function getNoArquivoUpload()
    {
        return $this->noArquivoUpload;
    }

    /**
     * 
     * @return integer
     */
    public function getQtParticipante()
    {
        return $this->qtParticipante;
    }

    /**
     * 
     * @return integer
     */
    public function getQtParticipantePago()
    {
        return $this->qtParticipantePago;
    }

    /**
     * 
     * @return integer
     */
    public function getQtParticipanteNaoPago()
    {
        return $this->qtParticipanteNaoPago;
    }
}
