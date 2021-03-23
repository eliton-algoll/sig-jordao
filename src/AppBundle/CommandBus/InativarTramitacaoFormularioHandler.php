<?php

namespace AppBundle\CommandBus;

use AppBundle\Event\SendMailNotificacaoExcusaoEnvioFormularioAvaliacaoAtividadeEvent;
use AppBundle\Repository\TramitacaoFormularioRepository;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

final class InativarTramitacaoFormularioHandler
{
    /**
     *
     * @var TramitacaoFormularioRepository 
     */
    private $tramitacaoFormularioRepository;
    
    /**
     *
     * @var EventDispatcherInterface
     */
    private $eventDispatcher;
    
    /**
     * 
     * @param TramitacaoFormularioRepository $tramitacaoFormularioRepository
     * @param EventDispatcherInterface $eventDispatcher
     */
    public function __construct(
        TramitacaoFormularioRepository $tramitacaoFormularioRepository,
        EventDispatcherInterface $eventDispatcher
    ) {
        $this->tramitacaoFormularioRepository = $tramitacaoFormularioRepository;
        $this->eventDispatcher = $eventDispatcher;
    }

    /**
     * 
     * @param \AppBundle\CommandBus\InativarTramitacaoFormularioCommand $command
     * @throws \Exception
     */
    public function handle(InativarTramitacaoFormularioCommand $command)
    {
        $tramitacaoFormulario = $command->getTramitacaoFormulario();
        
        if (!$tramitacaoFormulario->getSituacaoTramiteFormulario()->isPendente()) {
            throw new \Exception();
        }
        
        $tramitacaoFormulario->inativar();        
        $this->tramitacaoFormularioRepository->add($tramitacaoFormulario);
        
        $event = new SendMailNotificacaoExcusaoEnvioFormularioAvaliacaoAtividadeEvent($tramitacaoFormulario);        
        $this->eventDispatcher->dispatch(
            SendMailNotificacaoExcusaoEnvioFormularioAvaliacaoAtividadeEvent::NAME, $event);
    }
}
