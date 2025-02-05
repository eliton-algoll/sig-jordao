<?php

namespace AppBundle\CommandBus;

use AppBundle\CommandBus\CadastrarEnvioFormularioAvaliacaoAtividadeCommand;
use AppBundle\Entity\EnvioFormularioAvaliacaoAtividade;
use AppBundle\Entity\SituacaoTramiteFormulario;
use AppBundle\Event\SendMailNotificacaoFormularioAvaliacaoAtividadeEvent;
use AppBundle\Repository\EnvioFormularioAvaliacaoAtividadeRepository;
use AppBundle\Repository\ProjetoPessoaRepository;
use AppBundle\Repository\SituacaoTramiteFormularioRepository;
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
