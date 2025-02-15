<?php

namespace App\CommandBus;

use App\Entity\TramitacaoFormulario;
use App\Facade\FileNameGeneratorFacade;
use App\Facade\FileUploaderFacade;
use App\Repository\SituacaoTramiteFormularioRepository;
use App\Repository\TramitacaoFormularioRepository;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

final class CadastrarRetornoFormularioAvaliacaoAtividadeHandler
{
    /**
     *
     * @var TramitacaoFormularioRepository 
     */
    private $tramitarFormularioRepository;
    
    /**
     *
     * @var SituacaoTramiteFormularioRepository 
     */
    private $situacaoTramiteFormularioRepository;
    
    /**
     *
     * @var EventDispatcherInterface 
     */    
    private $eventDispatcher;
    
    /**
     *
     * @var FileUploaderFacade 
     */
    private $fileUploader;
    
    /**
     *
     * @var FileNameGeneratorFacade 
     */
    private $fileNameGenerator;
    
    /**
     * 
     * @param TramitacaoFormularioRepository $tramitarFormularioRepository
     * @param SituacaoTramiteFormularioRepository $situacaoTramiteFormularioRepository
     * @param EventDispatcherInterface $eventDispatcher
     * @param FileUploaderFacade $fileUploader
     * @param FileNameGeneratorFacade $fileNameGenerator
     */
    public function __construct(
        TramitacaoFormularioRepository $tramitarFormularioRepository,
        SituacaoTramiteFormularioRepository $situacaoTramiteFormularioRepository,
        EventDispatcherInterface $eventDispatcher,
        FileUploaderFacade $fileUploader,
        FileNameGeneratorFacade $fileNameGenerator
    ) {
        $this->tramitarFormularioRepository = $tramitarFormularioRepository;
        $this->situacaoTramiteFormularioRepository = $situacaoTramiteFormularioRepository;
        $this->eventDispatcher = $eventDispatcher;
        $this->fileUploader = $fileUploader;
        $this->fileNameGenerator = $fileNameGenerator;
    }

    /**
     * 
     * @param CadastrarRetornoFormularioAvaliacaoAtividadeCommand $command
     */
    public function handle(CadastrarRetornoFormularioAvaliacaoAtividadeCommand $command)
    {
        $this->validateTramitacaoFormulario($command->getTramitacaoFormulario());
        
        $tramitacaoFormulario = $command->getTramitacaoFormulario();
        $tramitacaoFormularioNew = clone $tramitacaoFormulario;
        
        $fileName = $this->fileNameGenerator->generate($command->getFileFormulario());           
        
        $tramitacaoFormulario->inativar();
        $tramitacaoFormularioNew->setNoArquivoRetornoFormulario($fileName);
        $tramitacaoFormularioNew->setNuProtocoloFormsus($command->getNuProtocoloFormSUS());
        $tramitacaoFormularioNew->setSituacaoTramiteFormulario(
            $this->situacaoTramiteFormularioRepository->getAguardandoAnalise()
        );
        
        $this->tramitarFormularioRepository->add($tramitacaoFormulario);
        $this->tramitarFormularioRepository->add($tramitacaoFormularioNew);
        $this->fileUploader->upload($command->getFileFormulario(), $fileName);
    }
    
    /**
     * 
     * @param TramitacaoFormulario $tramitacaoFormulario
     * @throws \Exception
     */
    private function validateTramitacaoFormulario(TramitacaoFormulario $tramitacaoFormulario)
    {
        if ((!$tramitacaoFormulario->getSituacaoTramiteFormulario()->isDevolvido() &&
            !$tramitacaoFormulario->getSituacaoTramiteFormulario()->isPendente()) ||
            $tramitacaoFormulario->isInativo()
        ) {
            throw new \Exception();
        }
    }
}
