<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="DBPETINFOSD.TB_AUTORIZA_CADASTRO_PARTICIP")
 * @ORM\Entity(repositoryClass="App\Repository\AutorizaCadastroParticipanteRepository")
 */
class AutorizaCadastroParticipante extends AbstractEntity
{
    use \App\Traits\DataInclusaoTrait;
    use \App\Traits\DeleteLogicoTrait;
    
    /**
     *
     * @var integer
     * 
     * @ORM\Id
     * @ORM\Column(name="CO_SEQ_AUTOR_CAD_PARTICIPANTE", type="integer")
     * @ORM\GeneratedValue(strategy="SEQUENCE")
     * @ORM\SequenceGenerator(sequenceName="DBPETINFOSD.SQ_AUTCADPART_COSEQAUTCADPART", allocationSize=1, initialValue=1)
     */
    private $coSeqAutorCadParticipante;
    
    /**
     *
     * @var Projeto
     * 
     * @ORM\ManyToOne(targetEntity="\App\Entity\Projeto")
     * @ORM\JoinColumn(name="CO_PROJETO", referencedColumnName="CO_SEQ_PROJETO", nullable=false)
     */
    private $projeto;
    
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
     * @var \DateTime
     * 
     * @ORM\Column(name="DT_INICIO_PERIODO", type="datetime", nullable=false)
     */
    private $dtInicioPeriodo;
    
    /**
     *
     * @var \DateTime
     * 
     * @ORM\Column(name="DT_FIM_PERIODO", type="datetime", nullable=false)
     */
    private $dtFimPeriodo;

    /**
     * 
     * @param \App\Entity\Projeto $projeto
     * @param \App\Entity\FolhaPagamento $folhaPagamento
     * @param \DateTime $dtInicioPeriodo
     * @param \DateTime $dtFimPeriodo
     */
    public function __construct(
        Projeto $projeto,
        FolhaPagamento $folhaPagamento,
        \DateTime $dtInicioPeriodo,
        \DateTime $dtFimPeriodo
    ) {
        $this->projeto = $projeto;
        $this->folhaPagamento = $folhaPagamento;
        $this->dtInicioPeriodo = $dtInicioPeriodo;
        $this->dtFimPeriodo = $dtFimPeriodo;
        $this->stRegistroAtivo = 'S';
        $this->dtInclusao = new \DateTime();
    }

    /**
     * 
     * @return integer
     */
    public function getCoSeqAutorCadParticipante()
    {
        return $this->coSeqAutorCadParticipante;
    }

    /**
     * 
     * @return Projeto
     */
    public function getProjeto()
    {
        return $this->projeto;
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
     * @return \DateTime
     */
    public function getDtInicioPeriodo()
    {
        return $this->dtInicioPeriodo;
    }

    /**
     * 
     * @return \DateTime
     */
    public function getDtFimPeriodo()
    {
        return $this->dtFimPeriodo;
    }
    
    /**
     * 
     * @param \DateTime $dtInicioPeriodo
     * @param \DateTime $dtFimPeriodo
     */
    public function atualizaPeriodo(\DateTime $dtInicioPeriodo, \DateTime $dtFimPeriodo)
    {
        $this->dtInicioPeriodo = $dtInicioPeriodo;
        $this->dtFimPeriodo = $dtFimPeriodo;
    }
    
    /**
     * 
     * @return boolean
     */
    public function isPeriodoVigente()
    {
        $now = new \DateTime();
        return $now >= $this->dtInicioPeriodo && $now <= $this->dtFimPeriodo;
    }
}
