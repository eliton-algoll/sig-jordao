<?php

namespace AppBundle\CommandBus;

use AppBundle\Entity\SituacaoTramiteFormulario;
use AppBundle\Entity\TramitacaoFormulario;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Context\ExecutionContextInterface;

final class AnalisarRetornoFormularioAvaliacaoAtividadeCommand
{
    /**
     *
     * @var TramitacaoFormulario 
     */
    private $tramitacaoFormulario;
    
    /**
     *
     * @var string
     */
    private $noFormulario;
    
    /**
     *
     * @var string
     */
    private $participante;
    
    /**
     *
     * @var SituacaoTramiteFormulario
     * 
     * @Assert\NotBlank()
     */
    private $situacaoTramiteFormulario;
    
    /**
     *
     * @var string
     */
    private $dsJustificativa;
    
    /**
     * 
     * @param TramitacaoFormulario $tramitacaoFormulario
     */
    public function __construct(TramitacaoFormulario $tramitacaoFormulario)
    {
        $this->tramitacaoFormulario = $tramitacaoFormulario;
        $this->noFormulario = $tramitacaoFormulario->getEnvioFormularioAvaliacaoAtividade()->getFormularioAvaliacaoAtividade()->getNoFormulario();
        $this->participante = $tramitacaoFormulario->getProjetoPessoa()->getDescricaoParticipante();
    }
    
    /**
     * 
     * @return TramitacaoFormulario
     */
    public function getTramitacaoFormulario()
    {
        return $this->tramitacaoFormulario;
    }
    
    /**
     * 
     * @return string
     */
    public function getNoFormulario()
    {
        return $this->noFormulario;
    }

    /**
     * 
     * @return string
     */
    public function getParticipante()
    {
        return $this->participante;
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
     * @param string $noFormulario
     */
    public function setNoFormulario($noFormulario)
    {
        $this->noFormulario = $noFormulario;
    }

    /**
     * 
     * @param string $participante
     */
    public function setParticipante($participante)
    {
        $this->participante = $participante;
    }

    /**
     * 
     * @param SituacaoTramiteFormulario $situacaoTramiteFormulario
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
        $this->dsJustificativa = trim($dsJustificativa);
    }
    
    /**
     * 
     * @param ExecutionContextInterface $context
     * 
     * @Assert\Callback
     */
    public function validateJustificativa(ExecutionContextInterface $context)
    {
        if ($this->getSituacaoTramiteFormulario() &&
            $this->getSituacaoTramiteFormulario()->isDevolvido() &&
            $this->getDsJustificativa() == ''
        ) {
            $context->buildViolation('Este valor não deve ser vazio')
                ->atPath('dsJustificativa')
                ->addViolation();
        }
    }
}
