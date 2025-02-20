<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="DBPETINFOSD.TB_EMISSAO_CERTIFICADO")
 * @ORM\Entity(repositoryClass="App\Repository\EmissaoCertificadoRepository")
 */
class EmissaoCertificado extends AbstractEntity
{
    use \App\Traits\DataInclusaoTrait;
    
    const CERTIFICADO = 'C';
    const DECLARACAO = 'D';
    
    /**
     *
     * @var integer
     * 
     * @ORM\Id
     * @ORM\Column(name="CO_SEQ_EMISSAO_CERTIFICADO", type="integer")
     * @ORM\GeneratedValue(strategy="SEQUENCE")
     * @ORM\SequenceGenerator(sequenceName="DBPETINFOSD.SQ_EMISCERTIF_COSEQEMISCERTIF", allocationSize=1, initialValue=1)
     */
    private $coSeqHistoricoCertificado;
    
    /**
     *
     * @var ProjetoPessoa
     * 
     * @ORM\ManyToOne(targetEntity="App\Entity\ProjetoPessoa")
     * @ORM\JoinColumn(name="CO_PROJETO_PESSOA_PARTICIPANTE", referencedColumnName="CO_SEQ_PROJETO_PESSOA", nullable=false)
     */
    private $projetoPessoaParticipante;
    
    /**
     *
     * @var ProjetoPessoa
     * 
     * @ORM\ManyToOne(targetEntity="App\Entity\ProjetoPessoa")
     * @ORM\JoinColumn(name="CO_PROJETO_PESSOA_RESPONSAVEL", referencedColumnName="CO_SEQ_PROJETO_PESSOA", nullable=false)
     */
    private $projetoPessoaResponsavel;
    
    /**
     *
     * @var Municipio
     * 
     * @ORM\ManyToOne(targetEntity="App\Entity\Municipio")
     * @ORM\JoinColumn(name="CO_MUNICIPIO_IBGE", referencedColumnName="CO_MUNICIPIO_IBGE", nullable=false)
     */
    private $municipio;
    
    /**
     *
     * @var integer
     * 
     * @ORM\Column(name="QT_CARGA_HORARIA", type="integer", nullable=false)
     */
    private $qtCargaHoraria;
    
    /**
     *
     * @var string
     * 
     * @ORM\Column(name="TP_DOCUMENTO", type="string", length=1, nullable=false)
     */
    private $tpDocumento;
    
    /**
     * 
     * @param \App\Entity\ProjetoPessoa $projetoPessoaParticipante
     * @param \App\Entity\ProjetoPessoa $projetoPessoaResponsavel
     * @param \App\Entity\Municipio $municipio
     * @param integer $qtCargaHoraria     
     */
    public function __construct(
        ProjetoPessoa $projetoPessoaParticipante,
        ProjetoPessoa $projetoPessoaResponsavel,
        Municipio $municipio,
        $qtCargaHoraria
    ) {
        $this->projetoPessoaParticipante = $projetoPessoaParticipante;
        $this->projetoPessoaResponsavel = $projetoPessoaResponsavel;
        $this->municipio = $municipio;
        $this->qtCargaHoraria = $qtCargaHoraria;
        $this->tpDocumento = $projetoPessoaParticipante->isAtivo() ? self::CERTIFICADO : self::DECLARACAO;
        $this->dtInclusao = new \DateTime();
    }

    /**
     * 
     * @return integer
     */
    public function getCoSeqHistoricoCertificado()
    {
        return $this->coSeqHistoricoCertificado;
    }

    /**
     * 
     * @return ProjetoPessoa
     */
    public function getProjetoPessoaParticipante()
    {
        return $this->projetoPessoaParticipante;
    }

    /**
     * 
     * @return ProjetoPessoa
     */
    public function getProjetoPessoaResponsavel()
    {
        return $this->projetoPessoaResponsavel;
    }

    /**
     * 
     * @return Municipio
     */
    public function getMunicipio()
    {
        return $this->municipio;
    }

    /**
     * 
     * @return integer
     */
    public function getQtCargaHoraria()
    {
        return $this->qtCargaHoraria;
    }

    /**
     * 
     * @return boolean
     */
    public function isCertificado()
    {
        return $this->tpDocumento == self::DECLARACAO;
    }
    
    /**
     * 
     * @return boolean
     */
    public function isDeclaracao()
    {
        return $this->tpDocumento == self::DECLARACAO;
    }
}
