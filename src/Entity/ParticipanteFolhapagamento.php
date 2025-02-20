<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Class ParticipanteFolhapagamento
 * @package App\Entity
 *
 * @ORM\Entity
 * @ORM\Table(name="DBPETINFOSD.TH_PARTICIPANTE_FOLHAPAGAMENTO")
 * @ORM\Entity(repositoryClass="\App\Repository\ParticipanteFolhapagamentoRepository")
 */
class ParticipanteFolhapagamento  extends AbstractEntity
{

    /**
     * @var int
     *
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="SEQUENCE")
     * @ORM\Column(name="CO_SEQ_PARTICIPANTE_FOLHAPAGAM", type="integer")
     * @ORM\SequenceGenerator(sequenceName="DBPETINFOSD.SQ_PARTFLPAG_COSEQPARTFOLHAPAG", allocationSize=1, initialValue=1)
     */
    private $coSeqParticipanteFolhapagam;

    /**
     * @var ProjetoPessoa
     *
     * @ORM\ManyToOne(targetEntity="App\Entity\ProjetoPessoa")
     * @ORM\JoinColumn(name="CO_PROJETO_PESSOA", referencedColumnName="CO_SEQ_PROJETO_PESSOA")
     */
    private $projetoPessoa;

    /**
     * @var FolhaPagamento
     *
     * @ORM\ManyToOne(targetEntity="App\Entity\FolhaPagamento")
     * @ORM\JoinColumn(name="CO_FOLHA_PAGAMENTO", referencedColumnName="CO_SEQ_FOLHA_PAGAMENTO")
     */
    private $folhaPagamento;

    /**
     * @var string stRegistroativoProjpes
     *
     * @ORM\Column(name="ST_REGISTROATIVO_PROJPES", type="string", nullable=false)
     */
    private $stRegistroativoProjpes;

    /**
     * @var string stVoluntario
     *
     * @ORM\Column(name="ST_VOLUNTARIO", type="string", nullable=false)
     */
    private $stVoluntario;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="DT_GERACAO_FOLHA", type="datetime", nullable=false)
     */
    private $dtGeracaoFolha;

    /**
     * @param ProjetoPessoa $projetoPessoa
     * @param FolhaPagamento $folhaPagamento
     */
    public function __construct(
        ProjetoPessoa $projetoPessoa,
        FolhaPagamento $folhaPagamento
    ) {
        $this->projetoPessoa = $projetoPessoa;
        $this->folhaPagamento = $folhaPagamento;
        $this->stRegistroativoProjpes = $projetoPessoa->getStRegistroAtivo();
        $this->stVoluntario = $projetoPessoa->getStVoluntarioProjeto();
        $this->dtGeracaoFolha = new \DateTime();
    }

    /**
     * @return \App\Entity\FolhaPagamento
     */
    public function getFolhaPagamento()
    {
        return $this->folhaPagamento;
    }

    /**
     * @return \App\Entity\ProjetoPessoa
     */
    public function getProjetoPessoa()
    {
        return $this->projetoPessoa;
    }

    /**
     * @return int
     */
    public function getCoSeqParticipanteFolhapagam()
    {
        return $this->coSeqParticipanteFolhapagam;
    }

    /**
     * @return \DateTime
     */
    public function getDtGeracaoFolha()
    {
        return $this->dtGeracaoFolha;
    }

    /**
     * @return string
     */
    public function getStRegistroativoProjpes()
    {
        return $this->stRegistroativoProjpes;
    }

    /**
     * @return string
     */
    public function getStVoluntario()
    {
        return $this->stVoluntario;
    }

    /**
     * @return string
     */
    public function getDescricaoSituacao()
    {
        return ($this->stRegistroativoProjpes) ? 'Ativo' : 'Inativo';
    }
} 