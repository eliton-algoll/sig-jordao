<?php

namespace App\CommandBus;

use App\Entity\EnvioFormularioAvaliacaoAtividade;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Context\ExecutionContextInterface;

final class AtualizarEnvioFormularioAvaliacaoAtividadeCommand
{
    /**
     *
     * @var EnvioFormularioAvaliacaoAtividade 
     */
    private $envioFormularioAvaliacaoAtividade;
    
    /**
     *
     * @var string
     */
    private $formularioAvaliacaoAtividade;
    
    /**
     *
     * @var \DateTime
     * 
     * @Assert\NotBlank()
     * @Assert\Date()
     */
    private $dtInicioPeriodo;
    
    /**
     *
     * @var \DateTime
     * 
     * @Assert\NotBlank()
     * @Assert\Date()
     */
    private $dtFimPeriodo;
    
    /**
     * 
     * @param EnvioFormularioAvaliacaoAtividade $envioFormularioAvaliacaoAtividade
     */
    public function __construct(EnvioFormularioAvaliacaoAtividade $envioFormularioAvaliacaoAtividade)
    {
        $this->envioFormularioAvaliacaoAtividade = $envioFormularioAvaliacaoAtividade;
        $this->formularioAvaliacaoAtividade = $envioFormularioAvaliacaoAtividade->getFormularioAvaliacaoAtividade()->getNoFormulario();
        $this->dtInicioPeriodo = $envioFormularioAvaliacaoAtividade->getDtInicioPeriodo();
        $this->dtFimPeriodo = $envioFormularioAvaliacaoAtividade->getDtFimPeriodo();
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
     * @return string
     */
    public function getFormularioAvaliacaoAtividade()
    {
        return $this->formularioAvaliacaoAtividade;
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
     * @param string $formularioAvaliacaoAtividade
     */
    public function setFormularioAvaliacaoAtividade($formularioAvaliacaoAtividade)
    {
        $this->formularioAvaliacaoAtividade = $formularioAvaliacaoAtividade;
    }
    
    /**
     * 
     * @param \DateTime $dtInicioPeriodo
     */
    public function setDtInicioPeriodo(\DateTime $dtInicioPeriodo = null)
    {
        $this->dtInicioPeriodo = $dtInicioPeriodo;
    }

    /**
     * 
     * @param \DateTime $dtFimPeriodo
     */
    public function setDtFimPeriodo(\DateTime $dtFimPeriodo = null)
    {
        $this->dtFimPeriodo = $dtFimPeriodo;
    }
    
    /**
     * 
     * @param ExecutionContextInterface $context
     * 
     * @Assert\Callback
     */
    public function validateDatas(ExecutionContextInterface $context)
    {
        $now = new \DateTime();
        $now->setTime(0, 0, 0);
        
        if ($this->getDtInicioPeriodo() && $now > $this->getDtInicioPeriodo() &&
            $this->getEnvioFormularioAvaliacaoAtividade()->getDtInicioPeriodo() != $this->getDtInicioPeriodo()
        ) {
            $context->buildViolation('A data de início do período não poderá ser menor que a data corrente.')
                ->atPath('dtInicioPeriodo')
                ->addViolation();            
        }
        if ($this->getDtFimPeriodo() && $this->getDtInicioPeriodo() > $this->getDtFimPeriodo()) {
            $context->buildViolation('A data de término do período não poderá ser menor que a data de início do período.')
                ->atPath('dtFimPeriodo')
                ->addViolation();
        }
    }
}
