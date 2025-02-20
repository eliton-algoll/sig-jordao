<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="DBPETINFOSD.TB_ENVIO_FORM_AVALIACAO_ATIVID")
 * @ORM\Entity(repositoryClass="\App\Repository\EnvioFormularioAvaliacaoAtividadeRepository")
 */
class EnvioFormularioAvaliacaoAtividade extends AbstractEntity
{
    use \App\Traits\DataInclusaoTrait;
    use \App\Traits\DeleteLogicoTrait;
    
    /**
     *
     * @var integer
     * 
     * @ORM\Id
     * @ORM\Column(name="CO_SEQ_ENVIO_FORM_AVAL_ATIVID", type="integer")
     * @ORM\GeneratedValue(strategy="SEQUENCE")
     * @ORM\SequenceGenerator(sequenceName="DBPETINFOSD.SQ_ENVFRMAVAT_COSEQENVFRMAVAT", allocationSize=1, initialValue=1)
     */    
    private $coSeqEnvioFormAvalAtivid;
    
    /**
     *
     * @var FormularioAvaliacaoAtividade
     * 
     * @ORM\ManyToOne(targetEntity="\App\Entity\FormularioAvaliacaoAtividade")
     * @ORM\JoinColumn(name="CO_FORM_AVALIACAO_ATIVD", referencedColumnName="CO_SEQ_FORM_AVALIACAO_ATIVD", nullable=false)
     */
    private $formularioAvaliacaoAtividade;
    
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
     * @var string
     * 
     * @ORM\Column(name="ST_FINALIZADO", type="string", length=1, nullable=false)
     */
    private $stFinalizado;
    
    /**
     *
     * @var ArrayCollection<\App\Entity\TramitacaoFormulario>
     * 
     * @ORM\OneToMany(targetEntity="App\Entity\TramitacaoFormulario", mappedBy="envioFormularioAvaliacaoAtividade", cascade={"persist", "persist"}, orphanRemoval=true)
     */
    private $tramitacaoFormulario;
    
    /**
     * 
     * @param \App\Entity\FormularioAvaliacaoAtividade $formularioAvaliacaoAtividade
     * @param \DateTime $dtInicioPeriodo
     * @param \DateTime $dtFimPeriodo
     */
    public function __construct(
        FormularioAvaliacaoAtividade $formularioAvaliacaoAtividade,
        \DateTime $dtInicioPeriodo,
        \DateTime $dtFimPeriodo
    ) {
        $this->formularioAvaliacaoAtividade = $formularioAvaliacaoAtividade;
        $this->dtInicioPeriodo = $dtInicioPeriodo;
        $this->dtFimPeriodo = $dtFimPeriodo;
        $this->dtInclusao = new \DateTime();
        $this->stRegistroAtivo = 'S';
        $this->stFinalizado = 'N';
        $this->tramitacaoFormulario = new ArrayCollection();
    }

    /**
     * 
     * @return integer
     */
    public function getCoSeqEnvioFormAvalAtivid()
    {
        return $this->coSeqEnvioFormAvalAtivid;
    }

    /**
     * 
     * @return FormularioAvaliacaoAtividade
     */
    public function getFormularioAvaliacaoAtividade()
    {
        return $this->formularioAvaliacaoAtividade;
    }
    
    /**
     * 
     * @return ArrayCollection<\App\Entity\TramitacaoFormulario>
     */
    public function getTramitacaoFormulario()
    {
        return $this->tramitacaoFormulario;
    }
    
    /**
     * 
     * @return ArrayCollection<\App\Entity\TramitacaoFormulario>
     */
    public function getTramitacaoFormularioAtivos()
    {
        return $this->tramitacaoFormulario->filter(function ($tramitacaoFormulario) {
           return $tramitacaoFormulario->isAtivo();
        });
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
     */
    public function setDtInicioPeriodo(\DateTime $dtInicioPeriodo)
    {
        $this->dtInicioPeriodo = $dtInicioPeriodo;
    }

    /**
     * 
     * @param \DateTime $dtFimPeriodo
     */
    public function setDtFimPeriodo(\DateTime $dtFimPeriodo)
    {
        $this->dtFimPeriodo = $dtFimPeriodo;
    }
    
    /**
     * 
     * @param \App\Entity\ProjetoPessoa $projetoPessoa
     * @param \App\Entity\SituacaoTramiteFormulario $situacaoTramiteFormulario
     */
    public function addTramitacaoFormulario(ProjetoPessoa $projetoPessoa, SituacaoTramiteFormulario $situacaoTramiteFormulario)
    {
        $this->tramitacaoFormulario->add(new TramitacaoFormulario($projetoPessoa, $this, $situacaoTramiteFormulario));
    }
    
    /**
     * 
     * @return array
     */
    public function getDetailedInfo()
    {
        $data = [];
        
        $data['participantes'] = $this->getTramitacaoFormulario()->map(function ($tramitacaoFormulario) use (&$data) {
                        
            $projetoPessoa = $tramitacaoFormulario->getProjetoPessoa();
            $projeto = $projetoPessoa->getProjeto();
            $publicacao = $projeto->getPublicacao();
            $perfil = $projetoPessoa->getPessoaPerfil()->getPerfil();
            
            if (!isset($data['publicacoes'][$publicacao->getCoSeqPublicacao()])) {
                $data['publicacoes'][$publicacao->getCoSeqPublicacao()] = $publicacao;
            }
            if (!isset($data['projetos'][$projeto->getCoSeqProjeto()])) {
                $data['projetos'][$projeto->getCoSeqProjeto()] = $projeto;
            }
            if (!isset($data['perfis'][$projeto->getCoSeqProjeto()])) {
                $data['perfis'][$perfil->getCoSeqPerfil()] = $perfil;
            }
            
            return $projetoPessoa;
        })->toArray();  
        
        return $data;
    }
}
