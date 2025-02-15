<?php

namespace App\CommandBus;

use App\Entity\ModeloCertificado;
use App\Exception\UnexpectedCommandBehaviorException;
use App\Facade\FileNameGeneratorFacade;
use App\Facade\FileUploaderFacade;
use App\Repository\ModeloCertificadoRepository;

final class SalvarModeloCertificadoHandler
{
    /**
     * @var ModeloCertificadoRepository
     */
    private $modeloCertificadoRepository;
    /**
     * @var FileUploaderFacade
     */
    private $fileUploader;
    /**
     * @var FileNameGeneratorFacade
     */
    private $filenameGenerator;

    /**
     * SalvarModeloCertificadoHandler constructor.
     * @param ModeloCertificadoRepository $modeloCertificadoRepository
     * @param FileUploaderFacade $fileUploader
     * @param FileNameGeneratorFacade $filenameGenerator
     */
    public function __construct(ModeloCertificadoRepository $modeloCertificadoRepository,
                                FileUploaderFacade $fileUploader,
                                FileNameGeneratorFacade $filenameGenerator)
    {
        $this->modeloCertificadoRepository = $modeloCertificadoRepository;
        $this->fileUploader = $fileUploader;
        $this->filenameGenerator = $filenameGenerator;
    }

    /**
     * @param SalvarModeloCertificadoCommand $command
     * @throws UnexpectedCommandBehaviorException
     */
    public function handle(SalvarModeloCertificadoCommand $command)
    {
        if ($command->getId()) {
            $modeloCertificado = $this->modeloCertificadoRepository->find($command->getId());
        } else {
            $modeloCertificado = new ModeloCertificado();
        }

        $modeloCertificado->setPrograma($command->getPrograma());
        $modeloCertificado->setTpDocumento($command->getTipo());
        $modeloCertificado->setNoModeloCertificado($command->getNome());
        $modeloCertificado->setDsModeloCertificado($command->getDescricao());
        $modeloCertificado->setStRegistroAtivo('S');

        $filenameCertificado = null;
        if ($command->getImagem()) {
            $filenameCertificado = $this->filenameGenerator->generate($command->getImagem());
            $modeloCertificado->setNoImagemCertificado($filenameCertificado);
        }

        $filenameRodape = null;
        if ($command->getImagemRodape()) {
            $filenameRodape = $this->filenameGenerator->generate($command->getImagemRodape());
            $modeloCertificado->setNoImagemRodape($filenameRodape);
        }

        if ($this->modeloCertificadoRepository->verificaNomeDuplicado($modeloCertificado)) {
            throw UnexpectedCommandBehaviorException::onHandle('Modelo já cadastrado com o nome informado.');
        }

        $this->modeloCertificadoRepository->add($modeloCertificado);

        $this->modeloCertificadoRepository->inativaOutrosModelos($modeloCertificado);

        if ($filenameCertificado) {
            $this->fileUploader->upload($command->getImagem(), $filenameCertificado);
        }
        if ($filenameRodape) {
            $this->fileUploader->upload($command->getImagemRodape(), $filenameRodape);
        }
    }
}