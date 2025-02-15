<?php

namespace App\CommandBus;

use App\CommandBus\InativarUsuarioCommand;
use App\Repository\CampusInstituicaoRepository;
use App\Repository\InstituicaoRepository;
use App\Repository\UsuarioRepository;
use Symfony\Component\Validator\Constraints as Assert;

final class InativarCampusInstituicaoHandler
{
    /**
     *
     * @var CampusInstituicaoRepository
     */
    private $campusInstituicaoRepository;
    
    /**
     * 
     * @param CampusInstituicaoRepository $campusInstituicaoRepository
     */
    public function __construct(
        CampusInstituicaoRepository $campusInstituicaoRepository
    ) {
        $this->campusInstituicaoRepository = $campusInstituicaoRepository;
    }
    
    /**
     * 
     * @param InativarCampusInstituicaoCommand $command
     */
    public function handle(InativarCampusInstituicaoCommand $command)
    {
        $campusInstituicao = $command->getCampusInstituicao();
        $campusInstituicao->setStRegistroAtivo(($campusInstituicao->getStRegistroAtivo() === 'S') ? 'N' : 'S');
        $this->campusInstituicaoRepository->add($campusInstituicao);
    }

}
