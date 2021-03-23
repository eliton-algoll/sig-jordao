<?php

namespace AppBundle\CommandBus;

use AppBundle\Event\HandleSituacaoGrupoAtuacaoEvent;
use AppBundle\Repository\ProjetoPessoaRepository;
use AppBundle\Repository\UsuarioRepository;
use AppBundle\CommandBus\InativarParticipanteCommand;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

class InativarParticipanteHandler
{
    /**
     * @var ProjetoPessoaRepository
     */
    private $projetoPessoaRepository;
    
    /**
     * @var UsuarioRepository
     */
    private $usuarioRepository;

    /**
     * @var EventDispatcherInterface
     */
    private $eventDispatcher;

    /**
     * InativarParticipanteHandler constructor.
     * @param ProjetoPessoaRepository $projetoPessoaRepository
     * @param UsuarioRepository $usuarioRepository
     * @param EventDispatcherInterface $eventDispatcher
     */
    public function __construct(
        ProjetoPessoaRepository $projetoPessoaRepository,
        UsuarioRepository $usuarioRepository,
        EventDispatcherInterface $eventDispatcher
    ) {
        $this->projetoPessoaRepository = $projetoPessoaRepository;
        $this->usuarioRepository = $usuarioRepository;
        $this->eventDispatcher = $eventDispatcher;
    }
    
    public function handle(InativarParticipanteCommand $command)
    {
        $projetoPessoa = $command->getProjetoPessoa();

        // O evento tem que presceder a ação para não "desconfirmar" os grupos que o profissional não está mais presente
        $this->eventDispatcher->dispatch(
            HandleSituacaoGrupoAtuacaoEvent::NAME,
            new HandleSituacaoGrupoAtuacaoEvent($projetoPessoa)
        );
        
        $projetoPessoa->inativar();        
        $projetoPessoa->getPessoaPerfil()->inativar();
        $projetoPessoa->inativarAllDadosAcademicos();
        $projetoPessoa->inativarAllProjetoPessoaCursoGraduacao();
        $projetoPessoa->inativarAllGruposAtuacao();        
        
        $pessoaFisica = $projetoPessoa->getPessoaPerfil()->getPessoaFisica();
                
        // se tiver apenas um perfil ativo, todas as informações no sistema serão inativadas
        if($pessoaFisica->hasOnePessoaPerfilAtivo()){            
            $nuCpf   = $pessoaFisica->getPessoa()->getNuCpfCnpjPessoa();
            $usuario = $this->usuarioRepository->findOneBy(array('pessoaFisica' => $nuCpf));
            $usuario->inativar();
            $this->usuarioRepository->add($usuario);
        }
        
        $this->projetoPessoaRepository->add($projetoPessoa);
    }
}
