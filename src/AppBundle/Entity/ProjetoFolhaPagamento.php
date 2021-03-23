<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use AppBundle\Entity\AutorizacaoFolha;

/**
 * ProjetoFolhaPagamento
 *
 * @ORM\Table(name="DBPET.TB_PROJETO_FOLHAPAGAMENTO")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\ProjetoFolhaPagamentoRepository")
 */
class ProjetoFolhaPagamento extends AbstractEntity
{
    use \AppBundle\Traits\DeleteLogicoTrait;
    use \AppBundle\Traits\DataInclusaoTrait;    

    /**
     * @var int
     *
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     * @ORM\Column(name="CO_SEQ_PROJ_FOLHA_PAGAM", type="integer")
     * @ORM\SequenceGenerator(sequenceName="DBPET.SQ_PROJFLSPAG_COSEQPROJFLSPAG", allocationSize=1, initialValue=1)
     */
    private $coSeqProjFolhaPagam;

    /**
     * @var Projeto
     *
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Projeto")
     * @ORM\JoinColumn(name="CO_PROJETO", referencedColumnName="CO_SEQ_PROJETO")
     */
    private $projeto;

    /**
     * @var FolhaPagamento
     *
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\FolhaPagamento", inversedBy="projetosFolhaPagamento")
     * @ORM\JoinColumn(name="CO_FOLHA_PAGAMENTO", referencedColumnName="CO_SEQ_FOLHA_PAGAMENTO")
     */
    private $folhaPagamento;

    /**
     * @var SituacaoProjetoFolha
     *
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\SituacaoProjetoFolha")
     * @ORM\JoinColumn(name="CO_SITUACAO_PROJ_FOLHA", referencedColumnName="CO_SEQ_SITUACAO_PROJ_FOLHA")
     */
    private $situacao;

    /**
     * @var string
     *
     * @ORM\Column(name="ST_PARECER", type="string", length=1, nullable=true)
     */
    private $stParecer;
    
    /**
     * @var string
     *
     * @ORM\Column(name="DS_JUSTIFICATIVA", type="string", length=4000, nullable=false)
     */
    private $dsJustificativa;
    
    /**
     *
     * @var string
     * 
     * @ORM\Column(name="ST_DECLARACAO", type="string", length=1, nullable=false)
     */
    private $stDeclaracao;
    
    /**
     *
     * @var PessoaPerfil
     * 
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\PessoaPerfil")
     * @ORM\JoinColumn(name="CO_PESSOA_PERFIL", referencedColumnName="CO_SEQ_PESSOA_PERFIL", nullable=false)
     */
    private $pessoaPerfil;
    
    /**
     *
     * @var ArrayCollection<AutorizacaoFolha>
     * 
     * @ORM\OneToMany(targetEntity="AppBundle\Entity\AutorizacaoFolha", mappedBy="projetoFolhaPagamento", cascade={"persist", "persist"})
     * @ORM\OrderBy({"coSeqAutorizacaoFolha"="ASC"})
     */
    private $autorizacaoFolha;

    /**
     * @param Projeto $projeto
     * @param FolhaPagamento $folhaPagamento
     * @param SituacaoProjetoFolha $situacao
     * @param PessoaPerfil $pessoaPerfil
     * @param string $stParecer
     * @param string $dsJustificativa
     * @param string $stDeclaracao
     */
    public function __construct(
        Projeto $projeto, 
        FolhaPagamento $folhaPagamento, 
        SituacaoProjetoFolha $situacao, 
        PessoaPerfil $pessoaPerfil,
        $stParecer = null, 
        $dsJustificativa = null,
        $stDeclaracao = 'S'
    ) {
        $this->projeto = $projeto;
        $this->folhaPagamento = $folhaPagamento;
        $this->situacao = $situacao;        
        $this->pessoaPerfil = $pessoaPerfil;
        $this->stParecer = $stParecer;
        $this->dsJustificativa = $dsJustificativa;
        $this->stDeclaracao = $stDeclaracao;
        $this->dtInclusao = new \DateTime();
        $this->stRegistroAtivo = 'S';
        $this->autorizacaoFolha = new ArrayCollection();
    }
    
    /**
     * Get coSeqProjFolhaPagam
     *
     * @return int
     */
    public function getCoSeqProjFolhaPagam()
    {
        return $this->coSeqProjFolhaPagam;
    }

    /**
     * Get projeto
     *
     * @return Projeto
     */
    public function getProjeto()
    {
        return $this->projeto;
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
     * Get situacao
     *
     * @return SituacaoProjetoFolha
     */
    public function getSituacao()
    {
        return $this->situacao;
    }
    
    /**
     * 
     * @return PessoaPerfil
     */
    public function getPessoaPerfil()
    {
        return $this->pessoaPerfil;
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
     * Get dsJustificativa
     *
     * @return string
     */
    public function getDsJustificativa()
    {
        return $this->dsJustificativa;
    }
    
    /**
     * @param AutorizacaoFolha $autorizacaoFolha
     */
    public function addAutorizacaoFolha(AutorizacaoFolha $autorizacaoFolha)
    {
        $this->autorizacaoFolha->add($autorizacaoFolha);
    }
    
    /**
     * @return ArrayCollection<AutorizacaoFolha>
     */
    public function getAutorizacoesAtivas()
    {
        return $this->autorizacaoFolha->filter(function (AutorizacaoFolha $autorizacao) {
            return $autorizacao->isAtivo() && $autorizacao->getStParecer() == 'S';
        });
    }

    /**
     * @param ProjetoPessoa $projetoPessoa
     * @return AutorizacaoFolha|null
     */
    public function getAutorizacaoByProjetoPessoa(ProjetoPessoa $projetoPessoa)
    {
        return $this->autorizacaoFolha->filter(
            function (AutorizacaoFolha $autorizacaoFolha) use ($projetoPessoa) {
                return $autorizacaoFolha->getProjetoPessoa() == $projetoPessoa;
            }
        )->first();
    }
    
    /**
     * @return float
     */
    public function getValorTotal()
    {
        $sum = 0;
        foreach ($this->getAutorizacoesAtivas() as $autorizacao) {
            $sum += $autorizacao->getVlBolsa();
        }
        return $sum;
    }
        
    /**
     * @param SituacaoProjetoFolha $situacao
     * @return ProjetoFolhaPagamento
     */
    public function setSituacao(SituacaoProjetoFolha $situacao)
    {
        $this->situacao = $situacao;
        return $this;
    }
    
    /**
     * @return ProjetoFolhaPagamento
     */
    public function aprovar()
    {
        $this->stParecer = 'S';
        return $this;
    }
    
    /**
     * @return ProjetoFolhaPagamento
     */
    public function reprovar()
    {
        $this->stParecer = 'N';
        return $this;
    } 
    
    public function declarar()
    {
        $this->stDeclaracao = 'S';
    }
    
    public function naoDeclarar()
    {
        $this->stDeclaracao = 'N';
    }
    
    /**
     * @return boolean
     */
    public function isAutorizada()
    {
        return $this->situacao->getCoSeqSituacaoProjFolha() == SituacaoProjetoFolha::AUTORIZADA;
    }
    
    /**
     * @return boolean
     */
    public function isHomologada()
    {
        return $this->situacao->getCoSeqSituacaoProjFolha() == SituacaoProjetoFolha::HOMOLOGADA;
    } 
    
    /**
     * @return boolean
     */
    public function isAprovada() {
        return $this->stParecer == 'S';
    }
    
    /**
     * @return boolean
     */
    public function isReprovada() {
        return $this->stParecer == 'N';
    }
    
    public function inativaAutorizacoes()
    {
        foreach ($this->autorizacaoFolha as $autorizacaoFolha) {
            $autorizacaoFolha->inativar();
        }
    }
}
