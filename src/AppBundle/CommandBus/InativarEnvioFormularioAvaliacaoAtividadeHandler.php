<?php

namespace AppBundle\CommandBus;

use AppBundle\Event\SendMailNotificacaoExcusaoEnvioFormularioAvaliacaoAtividadeEvent;
use AppBundle\Repository\EnvioFormularioAvaliacaoAtividadeRepository;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

final class InativarEnvioFormularioAvaliacaoAtividadeHandler
{
    /**
     *
     * @var EnvioFormularioAvaliacaoAtividadeRepository 
     */    
    private $envioFormularioAvaliacaoAtividade;
    
    /**
     *
     * @var EventDispatcherInterface
     */
    private $eventDispatcher;
    
    /**
     * 
     * @param EnvioFormularioAvaliacaoAtividadeRepository $envioFormularioAvaliacaoAtividade
     * @param EventDispatcherInterface $eventDispatcher
     */
    public function __construct(
        EnvioFormularioAvaliacaoAtividadeRepository $envioFormularioAvaliacaoAtividade,
        EventDispatcherInterface $eventDispatcher
    ) {
        $this->envioFormularioAvaliacaoAtividade = $envioFormularioAvaliacaoAtividade;
        $this->eventDispatcher = $eventDispatcher;
    }

    /**
     * 
     * @param InativarEnvioFormularioAvaliacaoAtividadeCommand $command
     */
    public function handle(InativarEnvioFormularioAvaliacaoAtividadeCommand $command)
    {
        $envioFormularioAvaliacaoAtividade = $command->getEnvioFormularioAvaliacaoAtividade();
        $countPendentes = 0;        
        $tramitacoesFormularioAtivas = $envioFormularioAvaliacaoAtividade->getTramitacaoFormularioAtivos();
            
        foreach ($tramitacoesFormularioAtivas as $tramitacaoFormulario) {            
            $tramitacaoFormulario->inativar();
            if ($tramitacaoFormulario->getSituacaoTramiteFormulario()->isPendente()) {
                $countPendentes++;
            }
        }

        if ($tramitacoesFormularioAtivas->count() != $countPendentes) {
            throw new \Exception();
        }

        $envioFormularioAvaliacaoAtividade->inativar();
        $this->envioFormularioAvaliacaoAtividade->add($envioFormularioAvaliacaoAtividade);
        
        $event = new SendMailNotificacaoExcusaoEnvioFormularioAvaliacaoAtividadeEvent($tramitacoesFormularioAtivas);        
        $this->eventDispatcher->dispatch(
            SendMailNotificacaoExcusaoEnvioFormularioAvaliacaoAtividadeEvent::NAME, $event);
    }
}
