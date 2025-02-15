<?php

namespace App\CommandBus;

use App\Repository\ProjetoRepository;
use App\Repository\PessoaJuridicaRepository;
use App\Repository\PublicacaoRepository;
use App\Repository\TipoAreaTematicaRepository;
use App\Repository\CampusInstituicaoRepository;
use App\Facade\FileUploaderFacade;
use App\Facade\FileNameGeneratorFacade;
use App\CommandBus\AtualizarProjetoCommand;
use League\Tactician\Bundle\Middleware\InvalidCommandException;

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

        if ( $command->getQtGrupos() != $command->getNrGruposInicio() ) {
            $hasParticipante = $this->projetoRepository->hasParticipantesByGroup($projeto);
            if ( isset($hasParticipante[0]) && $hasParticipante[0]['NUMERO'] > 0 ) {
                throw new \Exception('Não é possível editar o número de grupos do Projeto com participantes já cadastros.');
            }
        }

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
