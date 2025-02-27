<?php

namespace App\CommandBus;

use App\Repository\ProjetoRepository;
use App\Repository\PessoaJuridicaRepository;
use App\Repository\PublicacaoRepository;
use App\Repository\TipoAreaTematicaRepository;
use App\Repository\CampusInstituicaoRepository;
use App\Facade\FileUploaderFacade;
use App\Facade\FileNameGeneratorFacade;
use App\CommandBus\CadastrarProjetoCommand;
use App\Entity\Projeto;

class CadastrarProjetoHandler
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
     * @param CadastrarProjetoCommand $command
     */
    public function handle(CadastrarProjetoCommand $command)
    {
        $publicacao = $this->publicacaoRepository->find($command->getPublicacao());
        
        if ($command->getNoDocumentoProjeto()) {        
            $filename = $this->filenameGenerator->generate($command->getNoDocumentoProjeto());
        }
        
        $projeto = new Projeto(
            $publicacao, 
            $command->getNuSipar(),
            $command->getDsObservacao(),
            $command->getStOrientadorServico(),
            $command->getQtBolsa(),
            (isset($filename)) ? $filename : null
        );
        
        foreach ($command->getAreasTematicas() as $tipoAreaTematicaId) {
            $tipoAreaTematica = $this->tipoAreaTematicaRepository->find($tipoAreaTematicaId);
            $projeto->addAreaTematica($tipoAreaTematica);
        }
        
        foreach ($command->getCampus() as $campusId) {
            $campus = $this->campusInstituicaoRepository->find($campusId);
            $projeto->addCampus($campus);
        }
        
        foreach ($command->getSecretarias() as $secretariaCnpj) {
            $pessoaJuridica = $this->pessoaJuridicaRepository->findOneBy(array(
                'nuCnpj' => $secretariaCnpj,
                'stRegistroAtivo' => 'S'
            ));
            $projeto->addSecretaria($pessoaJuridica);
        }
        
        $this->projetoRepository->add($projeto);
        $this->projetoRepository->flush($projeto);

        if (isset($filename)) {
            $this->fileUploader->upload($command->getNoDocumentoProjeto(), $filename);
        }

        $this->projetoRepository->setAddGrupos($projeto, $command->getQtGrupos());
    }
}
