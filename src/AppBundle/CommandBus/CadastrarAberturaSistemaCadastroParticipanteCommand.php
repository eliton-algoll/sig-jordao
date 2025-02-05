<?php

namespace AppBundle\CommandBus;

use AppBundle\Entity\AutorizaCadastroParticipante;
use AppBundle\Entity\Projeto;
use AppBundle\Entity\Publicacao;
use AppBundle\Validator\Constraints as AppAssert;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Context\ExecutionContextInterface;

final class CadastrarAberturaSistemaCadastroParticipanteCommand
{
    /**
     *
     * @var AutorizaCadastroParticipante
     */
    private $autorizaCadastroParticipante;
    
    /**
     *
     * @var Publicacao
     * 
     * @Assert\NotBlank() 
     */
    private $publicacao;
    
    /**
     *
     * @var Projeto
     * 
     * @Assert\NotBlank()
     */
    private $projeto;
    
    /**
     *
     * @var string
     * 
     * @Assert\NotBlank()
     */
    private $noMesAnoReferencia;
    
    /**
     *
     * @var \DateTime
     * 
     * @Assert\NotBlank()
     * @AppAssert\DateTime(format = "d/m/Y")
     */
    private $dtInicioPeriodo;
    
    /**
     *
     * @var \DateTime
     * 
     * @Assert\NotBlank()
     * @AppAssert\DateTime(format = "d/m/Y")
     */
    private $dtFimPeriodo;
    
    /**
     * 
     * @return AutorizaCadastroParticipante
     */
    public function getAutorizaCadastroParticipante()
    {
        return $this->autorizaCadastroParticipante;
    }
        
    /**
     * 
     * @return Publicacao
     */
    public function getPublicacao()
    {
        return $this->publicacao;
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
     * @return string
     */
    public function getNoMesAnoReferencia()
    {
        return $this->noMesAnoReferencia;
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
     * @param AutorizaCadastroParticipante $autorizaCadastroParticipante
     */
    public function setAutorizaCadastroParticipante(AutorizaCadastroParticipante $autorizaCadastroParticipante)
    {
        $this->autorizaCadastroParticipante = $autorizaCadastroParticipante;
        $this->projeto = $autorizaCadastroParticipante->getProjeto();
        $this->publicacao = $this->projeto->getPublicacao();
        $this->dtInicioPeriodo = $autorizaCadastroParticipante->getDtInicioPeriodo()->format('d/m/Y');
        $this->dtFimPeriodo = $autorizaCadastroParticipante->getDtFimPeriodo()->format('d/m/Y');
        $this->noMesAnoReferencia = $autorizaCadastroParticipante->getFolhaPagamento()->getReferenciaExtenso();
    }
    
    /**
     * 
     * @param Publicacao $publicacao
     */
    public function setPublicacao(Publicacao $publicacao)
    {
        $this->publicacao = $publicacao;
    }

    /**
     * 
     * @param Projeto $projeto
     */
    public function setProjeto(Projeto $projeto)
    {
        $this->projeto = $projeto;
    }

    /**
     * 
     * @param string $noMesAnoReferencia
     */
    public function setNoMesAnoReferencia($noMesAnoReferencia)
    {
        $this->noMesAnoReferencia = $noMesAnoReferencia;
    }

    /**
     * 
     * @param string $dtInicioPeriodo
     */
    public function setDtInicioPeriodo($dtInicioPeriodo = null)
    {
        $this->dtInicioPeriodo = $dtInicioPeriodo;
    }

    /**
     * 
     * @param string $dtFimPeriodo
     */
    public function setDtFimPeriodo($dtFimPeriodo = null)
    {
        $this->dtFimPeriodo = $dtFimPeriodo;
    }
    
    /**
     * 
     * @return \DateTime
     */
    public function getDateTimeDtInicioPeriodo()
    {
        return \DateTime::createFromFormat('d/m/Y', $this->dtInicioPeriodo);
    }
    
    /**
     * 
     * @return \DateTime
     */
    public function getDateTimeDtFimPeriodo()
    {
        return \DateTime::createFromFormat('d/m/Y', $this->dtFimPeriodo);
    }
 
    /**
     * 
     * @param ExecutionContextInterface $context
     * 
     * @Assert\Callback()
     */
    public function validateDatas(ExecutionContextInterface $context)
    {
        $now = new \DateTime();
        $now->setTime(0, 0, 0);
        
        $dtIni = ($this->dtInicioPeriodo instanceof \DateTime) ? $this->dtInicioPeriodo : \DateTime::createFromFormat('d/m/Y', $this->dtInicioPeriodo);
        $dtFim = ($this->dtFimPeriodo instanceof \DateTime) ? $this->dtFimPeriodo : \DateTime::createFromFormat('d/m/Y', $this->dtFimPeriodo);
                
        if ((!$dtIni || !$dtFim) || 
            ($dtIni->format('d/m/Y') !== $this->dtInicioPeriodo || $dtFim->format('d/m/Y') !== $this->dtFimPeriodo)
        ) {
            return;
        }
        
        if ($dtIni && $dtIni < $now &&
            (!$this->autorizaCadastroParticipante || 
            ($this->autorizaCadastroParticipante && $dtIni->format('Ymd') != $this->autorizaCadastroParticipante->getDtInicioPeriodo()->format('Ymd')))
        ) {
            $context->buildViolation('A data de início do período não poderá ser menor que a data corrente')
                ->atPath('dtInicioPeriodo')
                ->addViolation();
        }
        
        if (($dtIni && $dtFim) && ($dtIni > $dtFim)) {
            $context->buildViolation('A data de término do período não poderá ser menor que a data de início do período')
                ->atPath('dtFimPeriodo')
                ->addViolation();
        }
        
        $lastDayofMonth = clone $now;
        $lastDayofMonth->modify('last day of this month');
        $lastDayofMonth->setTime(23, 59, 59);
        
        if ($dtFim && $dtFim > $lastDayofMonth) {
            $context->buildViolation('A data de término do período não poderá ser maior que o último dia do mês corrente')
                ->atPath('dtFimPeriodo')
                ->addViolation();
        }
    }    
}
