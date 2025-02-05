<?php

namespace AppBundle\CommandBus;

use AppBundle\Repository\ProjetoRepository;
use AppBundle\Repository\PessoaJuridicaRepository;
use AppBundle\Repository\PublicacaoRepository;
use AppBundle\Repository\TipoAreaTematicaRepository;
use AppBundle\Repository\CampusInstituicaoRepository;
use AppBundle\Facade\FileUploaderFacade;
use AppBundle\Facade\FileNameGeneratorFacade;
use AppBundle\CommandBus\AtualizarProjetoCommand;

class AtualizarProjetoHandler
{
    /**
     * @var ProjetoRepository
     */
    private $projetoRepository;
    
    /**
     * @var PessoaJuridicaRepository
     */
    private $pessoaJuridicaRepository;
    
    /**
     * @var PublicacaoRepository
     */
    private $publicacaoRepository;
    
    /**
     * @var TipoAreaTematicaRepository
     */
    private $tipoAreaTematicaRepository;
    
    /**
     * @var CampusInstituicaoRepository
     */
    private $campusInstituicaoRepository;
    
    /**
     *
     * @var FileUploaderFacade
     */
    private $fileUploader;
    
    /**
     *
     * @var FileNameGeneratorFacade 
     */
    private $filenameGenerator;
    
    /**
     * @param ProjetoRepository $projetoRepository
     * @param PessoaJuridicaRepository $pessoaJuridicaRepository
     * @param PublicacaoRepository $publicacaoRepository
     * @param TipoAreaTematicaRepository $tipoAreaTematicaRepository
     * @param CampusInstituicaoRepository $campusInstituicaoRepository
     */
    public function __construct(
        ProjetoRepository $projetoRepository,
        PessoaJuridicaRepository $pessoaJuridicaRepository,
        PublicacaoRepository $publicacaoRepository,
        TipoAreaTematicaRepository $tipoAreaTematicaRepository,
        CampusInstituicaoRepository $campusInstituicaoRepository,
        FileUploaderFacade $fileUploader,
        FileNameGeneratorFacade $filenameGenerator
    ) {
        $this->projetoRepository = $projetoRepository;
        $this->pessoaJuridicaRepository = $pessoaJuridicaRepository;
        $this->publicacaoRepository = $publicacaoRepository;
        $this->tipoAreaTematicaRepository = $tipoAreaTematicaRepository;
        $this->campusInstituicaoRepository = $campusInstituicaoRepository;
        $this->fileUploader = $fileUploader;
        $this->filenameGenerator = $filenameGenerator;
    }
    
    /**
     * @param AtualizarProjetoCommand $command
     */
    public function handle(AtualizarProjetoCommand $command)
    {

        $publicacao = $this->publicacaoRepository->find($command->getPublicacao());
        
        $projeto = $this->projetoRepository->find($command->getCoSeqProjeto());

        if ($command->getNoDocumentoProjeto()) {            
            $filename = $this->filenameGenerator->generate($command->getNoDocumentoProjeto());
            $projeto->setNoDocumentoProjeto($filename);
        }
        
        $projeto
            ->setNuSipar($command->getNuSipar())
            ->setDsObservacao($command->getDsObservacao())
            ->setStOrientadorServico($command->getStOrientadorServico())
            ->setQtBolsa($command->getQtBolsa())
            ->setPublicacao($publicacao);
        
        $projeto
            ->removerTodosCampus()
            ->removerTodasSecretarias()
            ->removerTodasAreasTematicas();
        
        foreach ($command->getCampus() as $campusId) {
            $campus = $this->campusInstituicaoRepository->find($campusId);
            $projeto->addCampus($campus);
        }
        
        foreach ($command->getAreasTematicas() as $tipoAreaTematicaId) {
            $tipoAreaTematica = $this->tipoAreaTematicaRepository->find($tipoAreaTematicaId);
            $projeto->addAreaTematica($tipoAreaTematica);
        }
        
        foreach ($command->getSecretarias() as $secretariaCnpj) {
            $pessoaJuridica = $this->pessoaJuridicaRepository->findOneBy(array(
                'nuCnpj' => $secretariaCnpj,
                'stRegistroAtivo' => 'S'
            ));
            $projeto->addSecretaria($pessoaJuridica);
        }
        
        $this->projetoRepository->add($projeto);

        if ( $command->getQtGrupos() != $command->getNrGruposInicio() ) {
            $this->projetoRepository->deletarGrupos($projeto->getCoSeqProjeto());
            $this->projetoRepository->setAddGrupos($projeto, $command->getQtGrupos());
        }

        if (isset($filename)) {
            $this->fileUploader->upload($command->getNoDocumentoProjeto(), $filename);
        }
    }
}
