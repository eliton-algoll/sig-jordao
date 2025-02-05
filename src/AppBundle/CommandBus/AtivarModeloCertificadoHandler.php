<?php

namespace AppBundle\CommandBus;

use AppBundle\Repository\ModeloCertificadoRepository;

final class AtivarModeloCertificadoHandler
{
    /**
     * @var ModeloCertificadoRepository
     */
    private $modeloCertificadoRepository;

    /**
     * AtivarModeloCertificadoHandler constructor.
     * @param ModeloCertificadoRepository $modeloCertificadoRepository
     */
    public function __construct(ModeloCertificadoRepository $modeloCertificadoRepository)
    {
        $this->modeloCertificadoRepository = $modeloCertificadoRepository;
    }

    /**
     * @param AtivarModeloCertificadoCommand $command
     */
    public function handle(AtivarModeloCertificadoCommand $command)
    {
        $modeloCertificado = $command->getModeloCertificado();
        $modeloCertificado->setStRegistroAtivo('S');
        $this->modeloCertificadoRepository->add($modeloCertificado);
        $this->modeloCertificadoRepository->inativaOutrosModelos($modeloCertificado);
    }

}