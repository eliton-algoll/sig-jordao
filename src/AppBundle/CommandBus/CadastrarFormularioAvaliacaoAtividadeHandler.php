<?php

namespace AppBundle\CommandBus;

use AppBundle\CommandBus\CadastrarFormularioAvaliacaoAtividadeCommand;
use AppBundle\Entity\FormularioAvaliacaoAtividade;
use AppBundle\Facade\FileNameGeneratorFacade;
use AppBundle\Facade\FileUploaderFacade;
use AppBundle\Repository\FormularioAvaliacaoAtividadeRepository;

final class CadastrarFormularioAvaliacaoAtividadeHandler
{
    /**
     *
     * @var FormularioAvaliacaoAtividadeRepository
     */
    private $formularioAvaliacaoAtividadeRepository;
    
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
     * @param FormularioAvaliacaoAtividadeRepository $formularioAvaliacaoAtividadeRepository
     * @param FileUploaderFacade $fileUploader
     * @param FileNameGeneratorFacade $fileNameGenerator
     */
    public function __construct(
        FormularioAvaliacaoAtividadeRepository $formularioAvaliacaoAtividadeRepository,
        FileUploaderFacade $fileUploader,
        FileNameGeneratorFacade $fileNameGenerator
    ) {
        $this->formularioAvaliacaoAtividadeRepository = $formularioAvaliacaoAtividadeRepository;
        $this->fileUploader = $fileUploader;
        $this->fileNameGenerator = $fileNameGenerator;
    }

    
    /**
     * 
     * @param CadastrarFormularioAvaliacaoAtividadeCommand $command
     */
    public function handle(CadastrarFormularioAvaliacaoAtividadeCommand $command)
    {
        $fileName = null;

        if ($command->getFileFormulario()) {
            $fileName = $this->fileNameGenerator->generate($command->getFileFormulario());
        }
        
        $formularioAvaliacaoAtividade = $command->getFormularioAvaliacaoAtividade();
        
        if (!$formularioAvaliacaoAtividade) {
            $formularioAvaliacaoAtividade = new FormularioAvaliacaoAtividade(
                $command->getTitulo(),
                $command->getPeriodicidade(),
                $command->getUrlFormulario(),
                $fileName,
                $command->getDescricao()
            );
        } else {
            $formularioAvaliacaoAtividade->setNoFormulario($command->getTitulo());
            $formularioAvaliacaoAtividade->setDsAvaliacao($command->getDescricao());
            $formularioAvaliacaoAtividade->setPeriodicidade($command->getPeriodicidade());
            $formularioAvaliacaoAtividade->setDsUrlFormulario($command->getUrlFormulario());
            if (isset($fileName)) {
                $formularioAvaliacaoAtividade->setNoArquivoFormulario($fileName);
            }
        }
        
        $formularioAvaliacaoAtividade->inativarPerfisFormularioAvaliacaoAtividade();        
        
        foreach ($command->getPerfis() as $perfil) {
            $formularioAvaliacaoAtividade->addPerfilFormularioAvaliacaoAtividade($perfil);
        }
        
        $this->formularioAvaliacaoAtividadeRepository->add($formularioAvaliacaoAtividade);
        if ($command->getFileFormulario()) {
            $this->fileUploader->upload($command->getFileFormulario(), $fileName);
        }
    }
}
