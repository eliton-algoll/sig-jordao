<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use AppBundle\Cpb\RetornoCadastro\Trailer;

/**
 * @ORM\Entity
 * @ORM\Table(name="DBPET.TB_RODAPE_RET_CRIA_CONTA")
 * @ORM\Entity(repositoryClass="\AppBundle\Repository\RodapeRetornoCriacaoContaRepository")
 */
class RodapeRetornoCriacaoConta
{
    /**
     *
     * @var integer
     * 
     * @ORM\Id
     * @ORM\Column(name="CO_SEQ_RODAPE_RET_CRIA_CONTA", type="integer")
     * @ORM\GeneratedValue(strategy="SEQUENCE")
     * @ORM\SequenceGenerator(sequenceName="DBPET.SQ_RODRETCRIACON_COSEQRODRETCC", initialValue=1, allocationSize=1)
     */
    private $coSeqRodapeRetCriaConta;
    
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
     * @ORM\Column(name="QT_REGISTRO_DETALHE", type="string", length=8)
     */
    private $qtRegistroDetalhe;    
    
    /**
     *
     * @var string
     * 
     * @ORM\Column(name="NU_SEQUENCIAL_LOTE", type="string", length=2, nullable=false)
     */
    private $nuSequencialaLote;
    
    /**
     * 
     * @param RetornoCriacaoConta $retornoCriacaoConta
     * @param Trailer $trailer
     */
    public function __construct(
        RetornoCriacaoConta $retornoCriacaoConta,
        Trailer $trailer
    ) {
        $this->retornoCriacaoConta = $retornoCriacaoConta;
        $this->fromTrailer($trailer);
    }
    
    /**
     * 
     * @return integer
     */
    public function getCoSeqRodapeRetCriaConta()
    {
        return $this->coSeqRodapeRetCriaConta;
    }

    /**
     * 
     * @return string
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
    public function getQtRegistroDetalhe()
    {
        return $this->qtRegistroDetalhe;
    }
    
    /**
     * 
     * @return string
     */
    public function getNuSequencialaLote()
    {
        return $this->nuSequencialaLote;
    }
        
    /**
     * 
     * @param Trailer $trailer
     */
    private function fromTrailer(Trailer $trailer)
    {
        $this->nuTipoRegistro = $trailer->getCsTipoRegistro();
        $this->nuSequencialRegistro = $trailer->getNuSeqRegistro();
        $this->nuTipoLote = $trailer->getCsTipoLote();
        $this->nuIdBanco = $trailer->getIdBanco();
        $this->qtRegistroDetalhe = $trailer->getQtRegDetalhe();        
        $this->nuSequencialaLote = $trailer->getNuSeqLote();
    }
}
