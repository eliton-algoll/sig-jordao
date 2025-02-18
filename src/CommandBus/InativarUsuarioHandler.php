<?php

namespace App\CommandBus;

use App\CommandBus\InativarUsuarioCommand;
use App\Repository\UsuarioRepository;
use Symfony\Component\Validator\Constraints as Assert;

final class InativarUsuarioHandler
{
    /**
     *
     * @var UsuarioRepository
     */
    private $usuarioRepository;
    
    /**
     * 
     * @param UsuarioRepository $usuarioRepository
     */
    public function __construct(
        UsuarioRepository $usuarioRepository
    ) {
        $this->usuarioRepository = $usuarioRepository;
    }
    
    /**
     * 
     * @param InativarUsuarioCommand $command
     */
    public function handle(InativarUsuarioCommand $command)
    {
        $usuario = $command->getUsuario();

        $usuario->setStRegistroAtivo(($usuario->getStRegistroAtivo() === 'S') ? 'N' : 'S');
        $this->usuarioRepository->add($usuario);
    }

}
