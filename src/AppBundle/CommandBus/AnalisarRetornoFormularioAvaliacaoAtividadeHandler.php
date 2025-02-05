<?php

namespace AppBundle\CommandBus;

use AppBundle\Event\SendMailNotificacaoAnaliseRetornoFormularioAvaliacaoAtividadeEvent;
use AppBundle\Repository\TramitacaoFormularioRepository;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

final class AnalisarRetornoFormularioAvaliacaoAtividadeHandler
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
     * @param AnalisarRetornoFormularioAvaliacaoAtividadeCommand $command
     */
    public function handle(AnalisarRetornoFormularioAvaliacaoAtividadeCommand $command)
    {
        $tramitacaoFormulario = $command->getTramitacaoFormulario();
        
        if (!$tramitacaoFormulario->getSituacaoTramiteFormulario()->isAguardandoAnalise() ||
            $tramitacaoFormulario->isInativo()
        ) {
            throw new \Exception();
        }
        
        $tramitacaoFormularioNew = clone $tramitacaoFormulario;
        
        $tramitacaoFormulario->inativar();
        $tramitacaoFormularioNew->setSituacaoTramiteFormulario($command->getSituacaoTramiteFormulario());
        $tramitacaoFormularioNew->setDsJustificativa($command->getDsJustificativa());
        
        $this->tramitacaoFormularioRepository->add($tramitacaoFormulario);
        $this->tramitacaoFormularioRepository->add($tramitacaoFormularioNew);
        
        $event = new SendMailNotificacaoAnaliseRetornoFormularioAvaliacaoAtividadeEvent($tramitacaoFormularioNew);
        
        $this->eventDispatcher->dispatch(
            SendMailNotificacaoAnaliseRetornoFormularioAvaliacaoAtividadeEvent::NAME,
            $event
        );
    }
}
