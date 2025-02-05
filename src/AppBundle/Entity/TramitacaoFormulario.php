<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="DBPET.TB_TRAMITACAO_FORMULARIO")
 * @ORM\Entity(repositoryClass="\AppBundle\Repository\TramitacaoFormularioRepository")
 */
class TramitacaoFormulario extends AbstractEntity
{
    use \AppBundle\Traits\DataInclusaoTrait;
    use \AppBundle\Traits\DeleteLogicoTrait;    
    
    /**
     *
     * @var integer
     * 
     * @ORM\Id
     * @ORM\Column(name="CO_SEQ_TRAMITACAO_FORMULARIO", type="integer")
     * @ORM\GeneratedValue(strategy="SEQUENCE")
     * @ORM\SequenceGenerator(sequenceName="DBPET.SQ_TRAMITFORM_COSEQTRAMITFORM", allocationSize=1, initialValue=1)
     */
    private $coSeqTramitacaoFormulario;
    
    /**
     *
     * @var ProjetoPessoa
     * 
     * @ORM\ManyToOne(targetEntity="\AppBundle\Entity\ProjetoPessoa")
     * @ORM\JoinColumn(name="CO_PROJETO_PESSOA", referencedColumnName="CO_SEQ_PROJETO_PESSOA", nullable=false)
     */
    private $projetoPessoa;
    
    /**
     *
     * @var EnvioFormularioAvaliacaoAtividade
     * 
     * @ORM\ManyToOne(targetEntity="\AppBundle\Entity\EnvioFormularioAvaliacaoAtividade")
     * @ORM\JoinColumn(name="CO_ENVIO_FORM_AVAL_ATIVID", referencedColumnName="CO_SEQ_ENVIO_FORM_AVAL_ATIVID", nullable=false)
     */
    private $envioFormularioAvaliacaoAtividade;
    
    /**
     *
     * @var SituacaoTramiteFormulario
     * 
     * @ORM\ManyToOne(targetEntity="\AppBundle\Entity\SituacaoTramiteFormulario")
     * @ORM\JoinColumn(name="CO_SITUACAO_TRAMITE_FORM", referencedColumnName="CO_SEQ_SITUACAO_TRAMITE_FORM", nullable=false)
     */
    private $situacaoTramiteFormulario;
    
    /**
     *
     * @var string
     * 
     * @ORM\Column(name="DS_JUSTIFICATIVA", type="string", length=2000, nullable=true)
     */
    private $dsJustificativa;
    
    /**
     *
     * @var string
     * 
     * @ORM\Column(name="NO_ARQUIVO_RETORNO_FORM", type="string", length=200, nullable=true)
     */
    private $noArquivoRetornoFormulario;
    
    /**
     *
     * @var string
     * 
     * @ORM\Column(name="NU_PROTOCOLO_FORMSUS", type="string", length=25, nullable=true)
     */
    private $nuProtocoloFormsus;
    
    /**
     * 
     * @param \AppBundle\Entity\ProjetoPessoa $projetoPessoa
     * @param \AppBundle\Entity\EnvioFormularioAvaliacaoAtividade $envioFormularioAvaliacaoAtividade
     * @param \AppBundle\Entity\SituacaoTramiteFormulario $situacaoTramiteFormulario
     */
    public function __construct(
        ProjetoPessoa $projetoPessoa,
        EnvioFormularioAvaliacaoAtividade $envioFormularioAvaliacaoAtividade,
        SituacaoTramiteFormulario $situacaoTramiteFormulario
    ) {
        $this->projetoPessoa = $projetoPessoa;
        $this->envioFormularioAvaliacaoAtividade = $envioFormularioAvaliacaoAtividade;
        $this->situacaoTramiteFormulario = $situacaoTramiteFormulario;
        $this->stRegistroAtivo = 'S';
        $this->dtInclusao = new \DateTime();
    }
    
    /**
     * 
     * @return integer
     */
    public function getCoSeqTramitacaoFormulario()
    {
        return $this->coSeqTramitacaoFormulario;
    }

    /**
     * 
     * @return ProjetoPessoa
     */
    public function getProjetoPessoa()
    {
        return $this->projetoPessoa;
    }

    /**
     * 
     * @return EnvioFormularioAvaliacaoAtividade
     */
    public function getEnvioFormularioAvaliacaoAtividade()
    {
        return $this->envioFormularioAvaliacaoAtividade;
    }

    /**
     * 
     * @return SituacaoTramiteFormulario
     */
    public function getSituacaoTramiteFormulario()
    {
        return $this->situacaoTramiteFormulario;
    }

    /**
     * 
     * @return string
     */
    public function getDsJustificativa()
    {
        return $this->dsJustificativa;
    }

    /**
     * 
     * @return string
     */
    public function getNoArquivoRetornoFormulario()
    {
        return $this->noArquivoRetornoFormulario;
    }

    /**
     * 
     * @return string
     */
    public function getNuProtocoloFormsus()
    {
        return $this->nuProtocoloFormsus;
    }
    
    /**
     * 
     * @param \AppBundle\Entity\SituacaoTramiteFormulario $situacaoTramiteFormulario
     */
    public function setSituacaoTramiteFormulario(SituacaoTramiteFormulario $situacaoTramiteFormulario)
    {
        $this->situacaoTramiteFormulario = $situacaoTramiteFormulario;
    }
    
    /**
     * 
     * @param string $dsJustificativa
     */
    public function setDsJustificativa($dsJustificativa)
    {
        $this->dsJustificativa = $dsJustificativa;
    }

    /**
     * 
     * @param string $noArquivoRetornoFormulario
     */
    public function setNoArquivoRetornoFormulario($noArquivoRetornoFormulario)
    {
        $this->noArquivoRetornoFormulario = $noArquivoRetornoFormulario;
    }

    /**
     * 
     * @param string $nuProtocoloFormsus
     */
    public function setNuProtocoloFormsus($nuProtocoloFormsus)
    {
        $this->nuProtocoloFormsus = $nuProtocoloFormsus;
    }
    
    public function __clone()
    {
        $this->dtInclusao = new \DateTime();
        $this->noArquivoRetornoFormulario = null;
    }
}
