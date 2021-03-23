<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * TramitacaoSituacaoFolha
 *
 * @ORM\Table(name="DBPET.TH_TRAMITACAO_SITUACAO_FOLHA")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\TramitacaoSituacaoFolhaRepository")
 */
class TramitacaoSituacaoFolha extends AbstractEntity
{
    /**
     * @var int
     *
     * @ORM\Column(name="CO_SEQ_TRAMITACAO_SITUACFOL", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="SEQUENCE")
     * @ORM\SequenceGenerator(sequenceName="DBPET.SQ_TRAMSITFLS_COSEQTRAMSITFOLH", allocationSize=1, initialValue=1)
     */
    private $coSeqTramitacaoSituacfol;

    /**
     * @var FolhaPagamento
     *
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\FolhaPagamento", inversedBy="tramitacaoSituacaoFolha")
     * @ORM\JoinColumn(name="CO_FOLHA_PAGAMENTO", referencedColumnName="CO_SEQ_FOLHA_PAGAMENTO")
     */
    private $folhaPagamento;

    /**
     * @var SituacaoFolha
     *
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\SituacaoFolha")
     * @ORM\JoinColumn(name="CO_SITUACAO_FOLHA", referencedColumnName="CO_SEQ_SITUACAO_FOLHA")
     */
    private $situacaoFolha;

    /**
     * @var PessoaPerfil
     *
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\PessoaPerfil")
     * @ORM\JoinColumn(name="CO_PESSOA_PERFIL", referencedColumnName="CO_SEQ_PESSOA_PERFIL")
     */
    private $pessoaPerfil;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="DT_INCLUSAO", type="datetime")
     */
    private $dtInclusao;


    public function __construct(
        FolhaPagamento $folhaPagamento,
        SituacaoFolha $situacaoFolha,
        PessoaPerfil $pessoaPerfil
    )
    {
        $this->folhaPagamento = $folhaPagamento;
        $this->situacaoFolha = $situacaoFolha;
        $this->pessoaPerfil = $pessoaPerfil;
        $this->dtInclusao = new \DateTime();
    }
    
    /**
     * Set coSeqTramitacaoSituacfol
     *
     * @param integer $coSeqTramitacaoSituacfol
     *
     * @return TramitacaoSituacaoFolha
     */
    public function setCoSeqTramitacaoSituacfol($coSeqTramitacaoSituacfol)
    {
        $this->coSeqTramitacaoSituacfol = $coSeqTramitacaoSituacfol;

        return $this;
    }

    /**
     * Get coSeqTramitacaoSituacfol
     *
     * @return int
     */
    public function getCoSeqTramitacaoSituacfol()
    {
        return $this->coSeqTramitacaoSituacfol;
    }

    /**
     * Set folhaPagamento
     *
     * @param FolhaPagamento $folhaPagamento
     *
     * @return TramitacaoSituacaoFolha
     */
    public function setFolhaPagamento(FolhaPagamento $folhaPagamento)
    {
        $this->folhaPagamento = $folhaPagamento;

        return $this;
    }

    /**
     * Get folhaPagamento
     *
     * @return FolhaPagamento
     */
    public function getFolhaPagamento()
    {
        return $this->folhaPagamento;
    }

    /**
     * Set coSituacaoFolha
     *
     * @param SituacaoFolha $situacaoFolha
     *
     * @return TramitacaoSituacaoFolha
     */
    public function setSituacaoFolha(SituacaoFolha $situacaoFolha)
    {
        $this->situacaoFolha = $situacaoFolha;

        return $this;
    }

    /**
     * Get situacaoFolha
     *
     * @return SituacaoFolha
     */
    public function getSituacaoFolha()
    {
        return $this->situacaoFolha;
    }

    /**
     * Set coPessoaPerfil
     *
     * @param PessoaPerfil $pessoaPerfil
     *
     * @return TramitacaoSituacaoFolha
     */
    public function setPessoaPerfil(PessoaPerfil $pessoaPerfil)
    {
        $this->pessoaPerfil = $pessoaPerfil;

        return $this;
    }

    /**
     * Get pessoaPerfil
     *
     * @return PessoaPerfil
     */
    public function getPessoaPerfil()
    {
        return $this->pessoaPerfil;
    }

    /**
     * Set dtInclusao
     *
     * @param \DateTime $dtInclusao
     *
     * @return TramitacaoSituacaoFolha
     */
    public function setDtInclusao($dtInclusao)
    {
        $this->dtInclusao = $dtInclusao;

        return $this;
    }

    /**
     * Get dtInclusao
     *
     * @return \DateTime
     */
    public function getDtInclusao()
    {
        return $this->dtInclusao;
    }
}

