<?php

namespace AppBundle\CommandBus;

use AppBundle\CommandBus\InativarBancoCommand;
use AppBundle\Repository\BancoRepository;
use Symfony\Component\Validator\Constraints as Assert;

final class InativarBancoHandler
{
    /**
     *
     * @var BancoRepository
     */
    private $bancoRepository;
    
    /**
     * 
     * @param BancoRepository $bancoRepository
     */
    public function __construct(
        BancoRepository $bancoRepository
    ) {
        $this->bancoRepository = $bancoRepository;
    }
    
    /**
     * 
     * @param InativarBancoCommand $command
     */
    public function handle(InativarBancoCommand $command)
    {
        $banco = $command->getBanco();

        $banco->setStRegistroAtivo(($banco->getStRegistroAtivo() === 'S') ? 'N' : 'S');
        $this->bancoRepository->add($banco);
    }

}
