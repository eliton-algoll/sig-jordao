<?php

namespace App\CommandBus;

use App\CommandBus\ExcluirBancoCommand;
use App\Repository\BancoRepository;
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
