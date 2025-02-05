<?php

namespace AppBundle\CommandBus;

use AppBundle\CommandBus\InativarUsuarioCommand;
use AppBundle\Repository\InstituicaoRepository;
use AppBundle\Repository\UsuarioRepository;
use Symfony\Component\Validator\Constraints as Assert;

final class InativarInstituicaoHandler
{
    /**
     *
     * @var InstituicaoRepository
     */
    private $instituicaoRepository;
    
    /**
     * 
     * @param InstituicaoRepository $instituicaoRepository
     */
    public function __construct(
        InstituicaoRepository $instituicaoRepository
    ) {
        $this->instituicaoRepository = $instituicaoRepository;
    }
    
    /**
     * 
     * @param InativarInstituicaoCommand $command
     */
    public function handle(InativarInstituicaoCommand $command)
    {
        $instituicao = $command->getInstituicao();
        $instituicao->setStRegistroAtivo(($instituicao->getStRegistroAtivo() === 'S') ? 'N' : 'S');
        $this->instituicaoRepository->add($instituicao);
    }

}
