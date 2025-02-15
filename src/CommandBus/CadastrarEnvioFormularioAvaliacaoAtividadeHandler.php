<?php

namespace App\CommandBus;

use App\CommandBus\CadastrarEnvioFormularioAvaliacaoAtividadeCommand;
use App\Entity\EnvioFormularioAvaliacaoAtividade;
use App\Entity\SituacaoTramiteFormulario;
use App\Event\SendMailNotificacaoFormularioAvaliacaoAtividadeEvent;
use App\Repository\EnvioFormularioAvaliacaoAtividadeRepository;
use App\Repository\ProjetoPessoaRepository;
use App\Repository\SituacaoTramiteFormularioRepository;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

class CadastrarEnvioFormularioAvaliacaoAtividadeHandler
{
    /**
     *
     * @var EnvioFormularioAvaliacaoAtividadeRepository
     */
    private $envioFormularioAvaliacaoAtividadeRepository;
    
    /**
     *
     * @var SituacaoTramiteFormularioRepository 
     */
    private $situacaoTramiteFormularioRepository;
    
    /**
     *
     * @var ProjetoPessoaRepository 
     */
    private $projetoPessoaRepository;
    
    /**
     *
     * @var EventDispatcherInterface 
     */
    private $eventDispatcher;
    
    /**
     * 
     * @param EnvioFormularioAvaliacaoAtividadeRepository $envioFormularioAvaliacaoAtividadeRepository
     * @param SituacaoTramiteFormularioRepository $situacaoTramiteFormularioRepository
     * @param ProjetoPessoaRepository $projetoPessoaRepository
     * @param EventDispatcherInterface $eventDispatcher
     */
    public function __construct(
        EnvioFormularioAvaliacaoAtividadeRepository $envioFormularioAvaliacaoAtividadeRepository,
        SituacaoTramiteFormularioRepository $situacaoTramiteFormularioRepository,
        ProjetoPessoaRepository $projetoPessoaRepository,
        EventDispatcherInterface $eventDispatcher
    ) {
        $this->envioFormularioAvaliacaoAtividadeRepository = $envioFormularioAvaliacaoAtividadeRepository;
        $this->situacaoTramiteFormularioRepository = $situacaoTramiteFormularioRepository;
        $this->projetoPessoaRepository = $projetoPessoaRepository;
        $this->eventDispatcher = $eventDispatcher;
    }

    
    /**
     * 
     * @param CadastrarEnvioFormularioAvaliacaoAtividadeCommand $command
     */
    public function handle(CadastrarEnvioFormularioAvaliacaoAtividadeCommand $command)
    {
        $situacaoTramiteFormulario = $this->situacaoTramiteFormularioRepository->getPendente();        
        $envioFormularioAvaliacaoAtividade = new EnvioFormularioAvaliacaoAtividade(
            $command->getFormularioAvaliacaoAtividade(),
            $command->getDateTimeInicioPeriodo(),
            $command->getDateTimeFimPeriodo()
        );
        
        if ($command->getStEnviaTodos()) {
            $participantes = $this->projetoPessoaRepository->findByProjetosAndPerfis(
                $command->getIdsProjetos(),
                $command->getIdsPerfis()
            );
        } else {        
            $participantes = $command->getToParticipantes();
        }

        foreach ($participantes as $projetoPessoa) {
            $envioFormularioAvaliacaoAtividade->addTramitacaoFormulario($projetoPessoa, $situacaoTramiteFormulario);
        }

        $this->envioFormularioAvaliacaoAtividadeRepository->add($envioFormularioAvaliacaoAtividade);
        $this->eventDispatcher->dispatch(
            SendMailNotificacaoFormularioAvaliacaoAtividadeEvent::NAME,
            new SendMailNotificacaoFormularioAvaliacaoAtividadeEvent($envioFormularioAvaliacaoAtividade)
        );
    }
}
