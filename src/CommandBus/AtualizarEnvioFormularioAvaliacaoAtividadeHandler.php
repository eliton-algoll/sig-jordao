<?php

namespace App\CommandBus;

use App\Event\SendMailNotificacaoFormularioAvaliacaoAtividadeEvent;
use App\Repository\EnvioFormularioAvaliacaoAtividadeRepository;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

final class AtualizarEnvioFormularioAvaliacaoAtividadeHandler
{
    /**
     *
     * @var EnvioFormularioAvaliacaoAtividadeRepository 
     */
    private $envioFormularioAvaliacaoAtividadeRepository;
    
    /**
     *
     * @var EventDispatcherInterface
     */
    private $eventDispatcher;
    
    /**
     * 
     * @param EnvioFormularioAvaliacaoAtividadeRepository $envioFormularioAvaliacaoAtividadeRepository
     * @param EventDispatcherInterface $eventDispatcher
     */
    public function __construct(
        EnvioFormularioAvaliacaoAtividadeRepository $envioFormularioAvaliacaoAtividadeRepository,
        EventDispatcherInterface $eventDispatcher
    ) {
        $this->envioFormularioAvaliacaoAtividadeRepository = $envioFormularioAvaliacaoAtividadeRepository;
        $this->eventDispatcher = $eventDispatcher;
    }

    /**
     * 
     * @param AtualizarEnvioFormularioAvaliacaoAtividadeCommand $command
     */
    public function handle(AtualizarEnvioFormularioAvaliacaoAtividadeCommand $command)
    {
        $envioFormularioAvaliacaoAtividade = $command->getEnvioFormularioAvaliacaoAtividade();
        $envioFormularioAvaliacaoAtividade->setDtInicioPeriodo($command->getDtInicioPeriodo());
        $envioFormularioAvaliacaoAtividade->setDtFimPeriodo($command->getDtFimPeriodo());
        
        $this->envioFormularioAvaliacaoAtividadeRepository->add($envioFormularioAvaliacaoAtividade);
        
        $event = new SendMailNotificacaoFormularioAvaliacaoAtividadeEvent($envioFormularioAvaliacaoAtividade, true);
        
        $this->eventDispatcher->dispatch(
            SendMailNotificacaoFormularioAvaliacaoAtividadeEvent::NAME, $event
        );
    }
}
