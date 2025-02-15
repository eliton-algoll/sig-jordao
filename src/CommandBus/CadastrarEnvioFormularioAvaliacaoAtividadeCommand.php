<?php

namespace App\CommandBus;

use App\Validator\Constraints as AppAssert;
use App\Entity\FormularioAvaliacaoAtividade;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Context\ExecutionContextInterface;

class CadastrarEnvioFormularioAvaliacaoAtividadeCommand
{
    /**
     *
     * @var FormularioAvaliacaoAtividade
     * 
     * @Assert\NotBlank()
     */
    private $formularioAvaliacaoAtividade;
    
    /**
     *
     * @var \DateTime
     * 
     * @Assert\NotBlank()
     * @AppAssert\DateTime(format="d/m/Y")
     */
    private $dtInicioPeriodo;
    
    /**
     *
     * @var \DateTime
     * 
     * @Assert\NotBlank()
     * @AppAssert\DateTime(format="d/m/Y")
     */
    private $dtFimPeriodo;
    
    /**
     *
     * @var ArrayCollection<\App\Entity\Publicacao>
     * 
     * @Assert\NotBlank()
     */
    private $publicacoes;
    
    /**
     *
     * @var ArrayCollection<\App\Entity\Projeto>
     * 
     * @Assert\NotBlank(
     *      message = "É obrigatório informar pelo menos um PROJETO de cada um dos Programas/Publicações selecionados para envio do formulário. Verifique e repita a operação."
     * )
     */
    private $projetos;
    
    /**
     *
     * @var ArrayCollection<\App\Entity\Perfil>
     * 
     * @Assert\NotBlank()
     */
    private $perfis;    
    
    private $stEnviaTodos;
    
    private $projetoSelecionado;
    
    private $from_participantes;
    
    private $to_participantes;
    
    public function getFormularioAvaliacaoAtividade()
    {
        return $this->formularioAvaliacaoAtividade;
    }

    public function getDtInicioPeriodo()
    {
        return $this->dtInicioPeriodo;
    }

    public function getDtFimPeriodo()
    {
        return $this->dtFimPeriodo;
    }

    public function getPublicacoes()
    {
        return $this->publicacoes;
    }

    public function getProjetos()
    {
        return $this->projetos;
    }

    public function getPerfis()
    {
        return $this->perfis;
    }

    public function getStEnviaTodos()
    {
        return $this->stEnviaTodos;
    }

    public function getProjetoSelecionado()
    {
        return $this->projetoSelecionado;
    }

    public function getFromParticipantes()
    {
        return $this->from_participantes;
    }

    public function getToParticipantes()
    {
        return $this->to_participantes;
    }
    
    /**
     * 
     * @return array
     */
    public function getIdsProjetos()
    {
        return $this->getProjetos()->map(function ($projeto) {
            return $projeto->getCoSeqProjeto();
        })->toArray();
    }
    
    /**
     * 
     * @return array
     */
    public function getIdsPerfis()
    {
        return $this->getPerfis()->map(function ($perfil) {
            return $perfil->getCoSeqPerfil();
        })->toArray();
    }

    public function setFormularioAvaliacaoAtividade(FormularioAvaliacaoAtividade $formularioAvaliacaoAtividade)
    {
        $this->formularioAvaliacaoAtividade = $formularioAvaliacaoAtividade;
    }

    public function setDtInicioPeriodo($dtInicioPeriodo = null)
    {
        $this->dtInicioPeriodo = $dtInicioPeriodo;
    }

    public function setDtFimPeriodo($dtFimPeriodo = null)
    {
        $this->dtFimPeriodo = $dtFimPeriodo;
    }

    public function setPublicacoes($publicacoes)
    {
        $this->publicacoes = $publicacoes;
    }

    public function setProjetos($projetos)
    {
        $this->projetos = $projetos;
    }

    public function setPerfis($perfis)
    {
        $this->perfis = $perfis;
    }

    public function setStEnviaTodos($stEnviaTodos)
    {
        $this->stEnviaTodos = $stEnviaTodos;
    }

    public function setProjetoSelecionado($projetoSelecionado)
    {
        $this->projetoSelecionado = $projetoSelecionado;
    }

    public function setFromParticipantes($from_participantes)
    {
        $this->from_participantes = $from_participantes;
    }

    public function setToParticipantes($to_participantes)
    {
        $this->to_participantes = $to_participantes;
    }
    
    /**
     * 
     * @return \DateTime
     */
    public function getDateTimeInicioPeriodo()
    {
        return \DateTime::createFromFormat('d/m/Y', $this->dtInicioPeriodo);
    }
    
    /**
     * 
     * @return \DateTime
     */
    public function getDateTimeFimPeriodo()
    {
        return \DateTime::createFromFormat('d/m/Y', $this->dtFimPeriodo);
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
        
        $dtIni = ($this->dtInicioPeriodo instanceof \DateTime) ? $this->dtInicioPeriodo : \DateTime::createFromFormat('d/m/Y', $this->dtInicioPeriodo);
        $dtFim = ($this->dtFimPeriodo instanceof \DateTime) ? $this->dtFimPeriodo : \DateTime::createFromFormat('d/m/Y', $this->dtFimPeriodo);
                
        if ((!$dtIni || !$dtFim) || 
            ($dtIni->format('d/m/Y') !== $this->dtInicioPeriodo ||
            $dtFim->format('d/m/Y') !== $this->dtFimPeriodo)
        ) {
            return;
        }
        
        if ($dtIni && $now > $dtIni) {
            $context->buildViolation('A data de início do período não poderá ser menor que a data corrente.')
                ->atPath('dtInicioPeriodo')
                ->addViolation();
        }
        
        if ($dtIni > $dtFim) {
            $context->buildViolation('A data de término do período não poderá ser menor que a data de início do período.')
                ->atPath('dtFimPeriodo')
                ->addViolation();
        }
    }
    
    /**
     * 
     * @param ExecutionContextInterface $context
     * 
     * @Assert\Callback
     */
    public function validatePublicacoes(ExecutionContextInterface $context)
    {
        if (!$this->getPublicacoes() || $this->getPublicacoes()->isEmpty()) {
            $context->buildViolation('Este valor não deve ser vazio.')
                ->atPath('publicacoes')
                ->addViolation();
        }
    }
    
    /**
     * 
     * @param ExecutionContextInterface $context
     * 
     * @Assert\Callback
     */
    public function validateProjetos(ExecutionContextInterface $context)
    {
        if (!$this->getProjetos() || $this->getProjetos()->isEmpty()) {
            $context->buildViolation('Este valor não deve ser vazio.')
                ->atPath('projetos')
                ->addViolation();
        }
    }
    
    /**
     * 
     * @param ExecutionContextInterface $context
     * 
     * @Assert\Callback
     */
    public function validatePerfis(ExecutionContextInterface $context)
    {
        if (!$this->getPerfis() || $this->getPerfis()->isEmpty()) {
            $context->buildViolation('Este valor não deve ser vazio.')
                ->atPath('perfis')
                ->addViolation();
        }
    }
        
    /**
     * 
     * @param ExecutionContextInterface $context     
     * 
     * @Assert\Callback
     */
    public function validateParticipantes(ExecutionContextInterface $context)
    {
        if (false === $this->stEnviaTodos && $this->to_participantes->isEmpty()) {
            $context->buildViolation('É obrigatório informar pelo menos um PARTICIPANTE de cada um dos Projetos selecionados para envio do formulário. Verifique e repita a operação.')
                ->atPath('to_participantes')
                ->addViolation();
        } elseif (false === $this->stEnviaTodos) {        
            $participanteProjetoAdd = [];

            foreach ($this->getProjetos() as $projeto) {
                $participanteProjetoAdd[] = $this
                    ->getToParticipantes()
                    ->exists(function ($index, $projetoPessoa) use ($projeto) {
                        return $projeto == $projetoPessoa->getProjeto();
                });
            }

            if (in_array(false, $participanteProjetoAdd)) {
                $context->buildViolation('É obrigatório informar pelo menos um PARTICIPANTE de cada um dos Projetos selecionados para envio do formulário. Verifique e repita a operação.')
                    ->atPath('to_participantes')
                    ->addViolation();
            }
        }
    }
}
