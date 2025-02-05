<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="DBPET.TB_RETORNO_CRIACAO_CONTA")
 * @ORM\Entity(repositoryClass="\AppBundle\Repository\RetornoCriacaoContaRepository")
 */
class RetornoCriacaoConta
{
    use \AppBundle\Traits\DataInclusaoTrait;
    use \AppBundle\Traits\DeleteLogicoTrait;
    
    /**
     *
     * @var integer
     * 
     * @ORM\Id
     * @ORM\Column(name="CO_SEQ_RETORNO_CRIACAO_CONTA", type="integer")
     * @ORM\GeneratedValue(strategy="SEQUENCE")
     * @ORM\SequenceGenerator(sequenceName="DBPET.SQ_RETCRIACONT_COSEQRETCRIACON", initialValue=1, allocationSize=1)
     */
    private $coSeqRetornoCriacaoConta;
    
    /**
     *
     * @var Publicacao
     * 
     * @ORM\ManyToOne(targetEntity="\AppBundle\Entity\Publicacao")
     * @ORM\JoinColumn(name="CO_PUBLICACAO", referencedColumnName="CO_SEQ_PUBLICACAO", nullable=false)
     */
    private $publicacao;
    
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
     * @ORM\Column(name="QT_PARTICIPANTE_CONTA_CRIADA", type="integer", nullable=false)
     */
    private $qtParticipanteContaCriada;
    
    /**
     *
     * @var integer
     * 
     * @ORM\Column(name="QT_PARTICIP_CONTA_NAO_CRIADA", type="integer", nullable=false)
     */
    private $qtParticipContaNaoCriada;
    
    /**
     *
     * @var CabecalhoRetornoCriacaoConta
     * 
     * @ORM\OneToOne(targetEntity="\AppBundle\Entity\CabecalhoRetornoCriacaoConta", mappedBy="retornoCriacaoConta")
     */
    private $cabecalhoRetornoCriacaoConta;
    
    /**
     *
     * @var DetalheRetornoCriacaoConta[]
     * 
     * @ORM\OneToMany(targetEntity="\AppBundle\Entity\DetalheRetornoCriacaoConta", mappedBy="retornoCriacaoConta")
     */
    private $detalheRetornoCriacaoConta;
    
    /**
     *
     * @var RodapeRetornoCriacaoConta
     * 
     * @ORM\OneToOne(targetEntity="\AppBundle\Entity\RodapeRetornoCriacaoConta", mappedBy="retornoCriacaoConta")
     */
    private $rodapeRetornoCriacaoConta;
    
    /**
     * 
     * @param Publicacao $publicacao
     * @param string $noArquivoOriginal
     * @param string $noArquivoUpload
     * @param integer $qtParticipante
     * @param integer $qtParticipanteContaCriada
     * @param integer $qtParticipContaNaoCriada
     */
    public function __construct(
        Publicacao $publicacao,
        $noArquivoOriginal,
        $noArquivoUpload,
        $qtParticipante,
        $qtParticipanteContaCriada,
        $qtParticipContaNaoCriada
    ) {
        $this->publicacao = $publicacao;
        $this->noArquivoOriginal = $noArquivoOriginal;
        $this->noArquivoUpload = $noArquivoUpload;
        $this->qtParticipante = $qtParticipante;
        $this->qtParticipanteContaCriada = $qtParticipanteContaCriada;
        $this->qtParticipContaNaoCriada = $qtParticipContaNaoCriada;
        $this->stRegistroAtivo = 'S';
        $this->dtInclusao = new \DateTime();
    }
    
    /**
     * 
     * @return integer
     */
    public function getCoSeqRetornoCriacaoConta()
    {
        return $this->coSeqRetornoCriacaoConta;
    }
    
    /**
     * 
     * @return Publicacao
     */
    public function getPublicacao()
    {
        return $this->publicacao;
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
    public function getQtParticipanteContaCriada()
    {
        return $this->qtParticipanteContaCriada;
    }

    /**
     * 
     * @return integer
     */
    public function getQtParticipContaNaoCriada()
    {
        return $this->qtParticipContaNaoCriada;
    }
}
