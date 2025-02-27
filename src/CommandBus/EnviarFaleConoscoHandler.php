<?php

namespace App\CommandBus;

use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use App\Entity\FaleConosco;
use App\Event\SendMailFaleConoscoEvent;
use App\Repository\FaleConoscoRepository;

final class EnviarFaleConoscoHandler
{
    /**
     *
     * @var FaleConoscoRepository
     */
    private $faleConoscoRepository;
    
    /**
     *
     * @var EventDispatcherInterface
     */
    private $eventDispatcher;
    
    /**
     *
     * @param FaleConoscoRepository $faleConoscoRepository
     */
    public function __construct(
        FaleConoscoRepository $faleConoscoRepository,
        EventDispatcherInterface $eventDispatcher
    ) {
        $this->faleConoscoRepository = $faleConoscoRepository;
        $this->eventDispatcher = $eventDispatcher;
    }
    
    /**
     *
     * @param \App\CommandBus\EnviarFaleConoscoCommand $command
     */
    public function handle(EnviarFaleConoscoCommand $command)
    {
        $faleConosco = new FaleConosco(
            $command->getNome(),
            $command->getEmail(),
            $command->getTipoAssunto(),
            $command->getAssunto(),
            $command->getMenssagem()
        );
        
        $this->faleConoscoRepository->add($faleConosco);
        
        $this->eventDispatcher->dispatch(
            SendMailFaleConoscoEvent::NAME,
            new SendMailFaleConoscoEvent($faleConosco)
        );
    }
}
