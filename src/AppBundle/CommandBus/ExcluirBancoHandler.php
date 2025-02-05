<?php

namespace AppBundle\CommandBus;

use AppBundle\CommandBus\ExcluirBancoCommand;
use AppBundle\Repository\BancoRepository;
use Symfony\Component\Validator\Constraints as Assert;

final class ExcluirBancoHandler
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
     * @param ExcluirBancoCommand $command
     */
    public function handle(ExcluirBancoCommand $command)
    {
        $banco = $command->getBanco();

        $banco->setDtRemocao(new \DateTime());
        $this->bancoRepository->add($banco);
    }

}
