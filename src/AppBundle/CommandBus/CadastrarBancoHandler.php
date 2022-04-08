<?php

namespace AppBundle\CommandBus;

use AppBundle\Entity\Banco;
use AppBundle\Repository\BancoRepository;
use Symfony\Component\Validator\Constraints as Assert;

final class CadastrarBancoHandler
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
    )
    {
        $this->bancoRepository = $bancoRepository;
    }

    /**
     *
     * @param CadastrarBancoCommand $command
     */
    public function handle(CadastrarBancoCommand $command)
    {
        if ($command->getBanco()) {
            $banco = $command->getBanco();
            $banco->setCoBanco($command->getCoBanco());
            $banco->setNoBanco($command->getNoBanco());
//            $banco->setSgBanco($command->getSgBanco());
//            $banco->setStConvenioFns($command->getStConvenioFns());
            $banco->setStRegistroAtivo($command->getStRegistroAtivo());
//            $banco->setDsSite($command->getDsSite());
            // $this->bancoRepository->merge($banco);
        } else {
            $banco = new Banco();
            $banco->setCoBanco($command->getCoBanco());
            $banco->setNoBanco($command->getNoBanco());
//            $banco->setSgBanco($command->getSgBanco());
//            $banco->setStConvenioFns($command->getStConvenioFns());
            $banco->setStRegistroAtivo($command->getStRegistroAtivo());
//            $banco->setDsSite($command->getDsSite());
            // $this->bancoRepository->add($banco);
        }

        $this->bancoRepository->merge($banco);
    }

}
