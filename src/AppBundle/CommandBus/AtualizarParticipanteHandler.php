<?php

namespace AppBundle\CommandBus;

use AppBundle\Event\HandleSituacaoGrupoAtuacaoEvent;
use AppBundle\Facade\FileNameGeneratorFacade;
use AppBundle\Facade\FileUploaderFacade;
use AppBundle\Repository\AreaTematicaRepository;
use AppBundle\Repository\IdentidadeGeneroRepository;
use AppBundle\WebServices\Cnes;
use AppBundle\CommandBus\AtualizarParticipanteCommand;
use AppBundle\CommandBus\CadastrarParticipanteCommand;
use AppBundle\Repository\ProjetoPessoaRepository;
use AppBundle\Repository\PessoaFisicaRepository;
use AppBundle\Repository\BancoRepository;
use AppBundle\Repository\AgenciaBancariaRepository;
use AppBundle\Repository\ProjetoRepository;
use AppBundle\Repository\PerfilRepository;
use AppBundle\Repository\MunicipioRepository;
use AppBundle\Repository\CursoGraduacaoRepository;
use AppBundle\Repository\TitulacaoRepository;
use AppBundle\Repository\GrupoAtuacaoRepository;
use AppBundle\Repository\CategoriaProfissionalRepository;
use AppBundle\Repository\CepRepository;
use AppBundle\Repository\ProjetoPessoaGrupoAtuacaoRepository;
use AppBundle\Entity\Perfil;
use AppBundle\Entity\ProjetoPessoa;
use AppBundle\Entity\Projeto;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

class AtualizarParticipanteHandler extends ParticipanteHandlerAbstract
{

    /**
     * AtualizarParticipanteHandler constructor.
     * @param PerfilRepository $perfilRepository
     * @param IdentidadeGeneroRepository $identidadeRepository
     * @param ProjetoPessoaRepository $projetoPessoaRepository
     * @param ProjetoRepository $projetoRepository
     * @param PessoaFisicaRepository $pessoaFisicaRepository
     * @param GrupoAtuacaoRepository $grupoAtuacaoRepository
     * @param CursoGraduacaoRepository $cursoGraduacaoRepository
     * @param TitulacaoRepository $titulacaoRepository
     * @param BancoRepository $bancoRepository
     * @param AgenciaBancariaRepository $agenciaBancariaRepository
     * @param MunicipioRepository $municipioRepository
     * @param CategoriaProfissionalRepository $categoriaProfissionalRepository
     * @param CepRepository $cepRepository
     * @param ProjetoPessoaGrupoAtuacaoRepository $projetoPessoaGrupoAtuacaoRepository
     * @param AreaTematicaRepository $areaTematicaRepository
     * @param FileUploaderFacade $fileUploader
     * @param FileNameGeneratorFacade $filenameGenerator
     */
    public function __construct(
        PerfilRepository $perfilRepository,
        IdentidadeGeneroRepository $identidadeRepository,
        ProjetoPessoaRepository $projetoPessoaRepository,
        ProjetoRepository $projetoRepository,
        PessoaFisicaRepository $pessoaFisicaRepository,
        GrupoAtuacaoRepository $grupoAtuacaoRepository,
        CursoGraduacaoRepository $cursoGraduacaoRepository,
        TitulacaoRepository $titulacaoRepository,
        BancoRepository $bancoRepository,
        AgenciaBancariaRepository $agenciaBancariaRepository,
        MunicipioRepository $municipioRepository,
        CategoriaProfissionalRepository $categoriaProfissionalRepository,
        CepRepository $cepRepository,
        ProjetoPessoaGrupoAtuacaoRepository $projetoPessoaGrupoAtuacaoRepository,
        AreaTematicaRepository $areaTematicaRepository,
        EventDispatcherInterface $eventDispatcher,
        FileUploaderFacade $fileUploader,
        FileNameGeneratorFacade $filenameGenerator
    ) {
//        $this->wsCnes = $wsCnes;
        $this->perfilRepository = $perfilRepository;
        $this->identidadeGeneroRepository = $identidadeRepository;
        $this->projetoPessoaRepository = $projetoPessoaRepository;
        $this->projetoRepository = $projetoRepository;
        $this->pessoaFisicaRepository = $pessoaFisicaRepository;
        $this->grupoAtuacaoRepository = $grupoAtuacaoRepository;
        $this->cursoGraduacaoRepository = $cursoGraduacaoRepository;
        $this->titulacaoRepository = $titulacaoRepository;
        $this->bancoRepository = $bancoRepository;
        $this->agenciaBancariaRepository = $agenciaBancariaRepository;
        $this->municipioRepository = $municipioRepository;
        $this->categoriaProfissionalRepository = $categoriaProfissionalRepository;
        $this->cepRepository = $cepRepository;
        $this->projetoPessoaGrupoAtuacaoRepository = $projetoPessoaGrupoAtuacaoRepository;
        $this->areaTematicaRepository = $areaTematicaRepository;
        $this->eventDispatcher = $eventDispatcher;
        $this->fileUploader = $fileUploader;
        $this->filenameGenerator = $filenameGenerator;
    }
    
    /**
     * @param AtualizarParticipanteCommand $command
     * @throws \UnexpectedValueException
     */
    public function handle(AtualizarParticipanteCommand $command)
    {
        $projetoPessoa = $this->projetoPessoaRepository->find($command->getCoSeqProjetoPessoa());
        $projeto       = $this->projetoRepository->find($command->getProjeto());

        $pessoaFisica    = $this->getPessoaFisicaIfCPFExists($command->getNuCpf());
        $banco           = $command->getCoBanco() ? $this->getBancoIfExists($command->getCoBanco()) : $command->getCoBanco();
        $banco           = !($banco) ? null : $banco;
        $cep             = $this->getCEPIfExists($command->getCoCep());
        $agenciaBancaria = $command->getCoAgenciaBancaria();
        $conta           = $command->getCoConta();
        $conta           = (!$conta) ? ' ' : $conta;
        $genero          = $this->getGeneroValid($command->getGenero());
        $perfil          = $this->getPerfilIfNonViolatedConstraints($command);

        if ($perfil->getCoSeqPerfil() == Perfil::PERFIL_PRECEPTOR) {
            $this->constraintCNES($command);
        }

        if ($command->getNoDocumentoBancario()) {
            $filename = $this->filenameGenerator->generate($command->getNoDocumentoBancario());
            $projetoPessoa->setNoDocumentoBancario($filename);
        }

        if ($command->getNoDocumentoMatricula()) {
            $filenameMatricula = $this->filenameGenerator->generate($command->getNoDocumentoMatricula());
            $projetoPessoa->setNoDocumentoMatricula($filenameMatricula);
        }

        if ($pessoaFisica->getDadoPessoal()) {
            $pessoaFisica->getDadoPessoal()->setBanco($banco);
            $pessoaFisica->getDadoPessoal()->setAgencia($agenciaBancaria);
            $pessoaFisica->getDadoPessoal()->setConta($conta);
        } else {
            $pessoaFisica->setDadoPessoal($banco, $agenciaBancaria, $conta);
        }

        if (isset($filename)) {
            $this->fileUploader->upload($command->getNoDocumentoBancario(), $filename);
        }

        if (isset($filenameMatricula)) {
            $this->fileUploader->upload($command->getNoDocumentoBancario(), $filenameMatricula);
        }
        
        $pessoaFisica->getPessoa()->addEnderecoWeb($command->getDsEnderecoWeb());
        
        $projetoPessoa->setStVoluntarioProjeto($command->getStVoluntarioProjeto());

        $projetoPessoa->setIdentidadeGenero($genero);

        $this->addEndereco($pessoaFisica, $cep, $command);
        $this->addDadosAcademicos($projetoPessoa, $command);
        $this->addTelefones($pessoaFisica, $command);
        
        if ($command->getCursoGraduacao() && $perfil!= '4') {
            $projetoPessoa->inativarAllCursosGraduacao();

            $this->addCursoGraduacao($projetoPessoa, $command);
        }

        $this->eventDispatcher->dispatch(
            HandleSituacaoGrupoAtuacaoEvent::NAME,
            new HandleSituacaoGrupoAtuacaoEvent($projetoPessoa)
        );

        $projetoPessoa->inativarAllGruposAtuacao();

        if ($projeto->getPublicacao()->getPrograma()->isAreaAtuacao()) {
            $this->addGrupoAtuacao($projetoPessoa, $projeto, $perfil, $command);
            $this->addCursoLecionados($projetoPessoa, $command);
        } else {
            $this->addGrupoTutorial($projetoPessoa, $command);
        }
        
        $this->projetoPessoaRepository->add($projetoPessoa);

        $this->eventDispatcher->dispatch(
            HandleSituacaoGrupoAtuacaoEvent::NAME,
            new HandleSituacaoGrupoAtuacaoEvent($projetoPessoa)
        );
    }

    /**
     * @param ProjetoPessoa $projetoPessoa
     * @param Projeto $projeto
     * @param Perfil $perfil
     * @param CadastrarParticipanteCommand $command
     */
    protected function addGrupoAtuacao(ProjetoPessoa $projetoPessoa, Projeto $projeto, Perfil $perfil, CadastrarParticipanteCommand $command)
    {
        if (count($command->getAreaTematica())) {
            foreach($command->getAreaTematica() as $coGrupoAtuacao) {
                $grupoAtuacao = $this->grupoAtuacaoRepository->find($coGrupoAtuacao);
                $projetoPessoa->addGrupoAtuacao($grupoAtuacao);
            }
        }
    }
}
