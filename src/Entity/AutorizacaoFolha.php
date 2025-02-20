<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * AutorizacaoFolha
 *
 * @ORM\Table(name="DBPETINFOSD.TB_AUTORIZACAO_FOLHA")
 * @ORM\Entity(repositoryClass="App\Repository\AutorizacaoFolhaRepository")
 */
class AutorizacaoFolha extends AbstractEntity
{
    use \App\Traits\DeleteLogicoTrait;
    use \App\Traits\DataInclusaoTrait;

    /**
     * @var int
     *
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     * @ORM\Column(name="CO_SEQ_AUTORIZACAO_FOLHA", type="integer")
     * @ORM\SequenceGenerator(sequenceName="DBPETINFOSD.SQ_AUTORFOLHA_COSEQAUTORFOLHA", allocationSize=1, initialValue=1)
     */
    private $coSeqAutorizacaoFolha;

    /**
     * @var ProjetoFolhaPagamento
     *
     * @ORM\ManyToOne(targetEntity="App\Entity\ProjetoFolhaPagamento", inversedBy="autorizacaoFolha")
     * @ORM\JoinColumn(name="CO_PROJ_FOLHA_PAGAM", referencedColumnName="CO_SEQ_PROJ_FOLHA_PAGAM")
     */
    private $projetoFolhaPagamento;

    /**
     * @var ProjetoPessoa
     *
     * @ORM\ManyToOne(targetEntity="App\Entity\ProjetoPessoa")
     * @ORM\JoinColumn(name="CO_PROJETO_PESSOA", referencedColumnName="CO_SEQ_PROJETO_PESSOA")
     */
    private $projetoPessoa;

    /**
     * @var float
     *
     * @ORM\Column(name="VL_BOLSA", type="decimal", precision=8, scale=2)
     */
    private $vlBolsa;

    /**
     * @var string
     *
     * @ORM\Column(name="ST_PARECER", type="string", length=1)
     */
    private $stParecer;

    /**
     * @var string
     *
     * @ORM\Column(name="CO_BANCO", type="string")
     */
    private $coBanco;

    /**
     * @var string
     *
     * @ORM\Column(name="CO_AGENCIA_BANCARIA", type="string")
     */
    private $coAgenciaBancaria;

    /**
     * @var string
     *
     * @ORM\Column(name="CO_CONTA", type="string")
     */
    private $coConta;

    /**
     *
     * @var DetalheArquivoRetornoPagamento
     *
     * @ORM\OneToOne(targetEntity="\App\Entity\DetalheArquivoRetornoPagamento")
     * @ORM\JoinColumn(name="CO_DETALHE_ARQUIVO_RETORNO", referencedColumnName="CO_SEQ_DETALHE_ARQUIVO_RETORNO")
     */
    private $detalheArquivoRetornoPagamento;

    /**
     * @param ProjetoFolhaPagamento $projetoFolhaPagamento
     * @param ProjetoPessoa $projetoPessoa
     * @param float $vlBolsa
     * @param string $stParecer
     */
    public function __construct(
        ProjetoFolhaPagamento $projetoFolhaPagamento,
        ProjetoPessoa $projetoPessoa,
        $vlBolsa,
        $stParecer = 'S',
        $coBanco,
        $coAgenciaBancaria,
        $coConta
    )
    {
        $this->projetoFolhaPagamento = $projetoFolhaPagamento;
        $this->projetoPessoa = $projetoPessoa;
        $this->vlBolsa = $vlBolsa;
        $this->stParecer = $stParecer;
        $this->coBanco = $coBanco;
        $this->coAgenciaBancaria = $coAgenciaBancaria;
        $this->coConta = $coConta;
        $this->dtInclusao = new \DateTime();
        $this->stRegistroAtivo = 'S';
        $this->projetoFolhaPagamento->addAutorizacaoFolha($this);
    }

    /**
     *
     * @param DetalheArquivoRetornoPagamento $detalheArquivoRetornoPagamento
     */
    public function setDetalheArquivoRetornoPagamento(DetalheArquivoRetornoPagamento $detalheArquivoRetornoPagamento)
    {
        if (!$this->detalheArquivoRetornoPagamento) {
            $this->detalheArquivoRetornoPagamento = $detalheArquivoRetornoPagamento;
        }
    }

    /**
     * Get coSeqAutorizacaoFolha
     *
     * @return int
     */
    public function getCoSeqAutorizacaoFolha()
    {
        return $this->coSeqAutorizacaoFolha;
    }

    /**
     * Get projetoFolhaPagamento
     *
     * @return ProjetoFolhaPagamento
     */
    public function getProjetoFolhaPagamento()
    {
        return $this->projetoFolhaPagamento;
    }

    /**
     * Get projetoPessoa
     *
     * @return ProjetoPessoa
     */
    public function getProjetoPessoa()
    {
        return $this->projetoPessoa;
    }

    /**
     * Get vlBolsa
     *
     * @return string
     */
    public function getVlBolsa()
    {
        return $this->vlBolsa;
    }

    /**
     * Get stParecer
     *
     * @return string
     */
    public function getStParecer()
    {
        return $this->stParecer;
    }

    /**
     * Get coBanco
     *
     * @return string
     */
    public function getCoBanco()
    {
        return $this->coBanco;
    }

    /**
     * Get coAgenciaBancaria
     *
     * @return string
     */
    public function getCoAgenciaBancaria()
    {
        return $this->coAgenciaBancaria;
    }

    /**
     * Get coConta
     *
     * @return string
     */
    public function getCoConta()
    {
        return $this->coConta;
    }

    public function removeDetalheArquivoRetornoPagamento()
    {
        $this->detalheArquivoRetornoPagamento = null;
    }

    public function setProjetoFolhaPagamento(ProjetoFolhaPagamento $projetoFolhaPagamento)
    {
        $this->projetoFolhaPagamento = $projetoFolhaPagamento;
    }

    public function __clone()
    {
        $this->projetoFolhaPagamento = null;
        $this->dtInclusao = new \DateTime();
    }

}

