<?php

namespace App\CommandBus;

use App\Entity\ProjetoPessoa;
use App\Entity\Perfil;
use App\Event\HandleSituacaoGrupoAtuacaoEvent;
use App\Facade\FileNameGeneratorFacade;
use App\Facade\FileUploaderFacade;
use App\Repository\AgenciaBancariaRepository;
use App\Repository\AreaTematicaRepository;
use App\Repository\BancoRepository;
use App\Repository\CategoriaProfissionalRepository;
use App\Repository\CepRepository;
use App\Repository\CursoGraduacaoRepository;
use App\Repository\DadoPessoalRepository;
use App\Repository\EnderecoRepository;
use App\Repository\EnderecoWebRepository;
use App\Repository\GrupoAtuacaoRepository;
use App\Repository\IdentidadeGeneroRepository;
use App\Repository\MunicipioRepository;
use App\Repository\PerfilRepository;
use App\Repository\PessoaFisicaRepository;
use App\Repository\ProjetoPessoaGrupoAtuacaoRepository;
use App\Repository\ProjetoPessoaRepository;
use App\Repository\ProjetoRepository;
use App\Repository\TelefoneRepository;
use App\Repository\TitulacaoRepository;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

class CadastrarParticipanteHandler extends ParticipanteHandlerAbstract
{

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
     * CadastrarParticipanteHandler constructor.
     * @param PerfilRepository $perfilRepository
     * @param IdentidadeGeneroRepository $identidadeGeneroRepository
     * @param ProjetoPessoaRepository $projetoPessoaRepository
     * @param ProjetoRepository $projetoRepository
     * @param PessoaFisicaRepository $pessoaFisicaRepository
     * @param GrupoAtuacaoRepository $grupoAtuacaoRepository
     * @param EnderecoRepository $enderecoRepository
     * @param CursoGraduacaoRepository $cursoGraduacaoRepository
     * @param TitulacaoRepository $titulacaoRepository
     * @param BancoRepository $bancoRepository
     * @param AgenciaBancariaRepository $agenciaBancariaRepository
     * @param MunicipioRepository $municipioRepository
     * @param CategoriaProfissionalRepository $categoriaProfissionalRepository
     * @param CepRepository $cepRepository
     * @param DadoPessoalRepository $dadoPessoalRepository
     * @param EnderecoWebRepository $enderecoWebRepository
     * @param TelefoneRepository $telefoneRepository
     * @param ProjetoPessoaGrupoAtuacaoRepository $projetoPessoaGrupoAtuacaoRepository
     * @param AreaTematicaRepository $areaTematicaRepository
     */
    public function __construct(
        PerfilRepository                    $perfilRepository,
        IdentidadeGeneroRepository          $identidadeGeneroRepository,
        ProjetoPessoaRepository             $projetoPessoaRepository,
        ProjetoRepository                   $projetoRepository,
        PessoaFisicaRepository              $pessoaFisicaRepository,
        GrupoAtuacaoRepository              $grupoAtuacaoRepository,
        EnderecoRepository                  $enderecoRepository,
        CursoGraduacaoRepository            $cursoGraduacaoRepository,
        TitulacaoRepository                 $titulacaoRepository,
        BancoRepository                     $bancoRepository,
        AgenciaBancariaRepository           $agenciaBancariaRepository,
        MunicipioRepository                 $municipioRepository,
        CategoriaProfissionalRepository     $categoriaProfissionalRepository,
        CepRepository                       $cepRepository,
        DadoPessoalRepository               $dadoPessoalRepository,
        EnderecoWebRepository               $enderecoWebRepository,
        TelefoneRepository                  $telefoneRepository,
        ProjetoPessoaGrupoAtuacaoRepository $projetoPessoaGrupoAtuacaoRepository,
        AreaTematicaRepository              $areaTematicaRepository,
        EventDispatcherInterface            $eventDispatcher,
        FileUploaderFacade                  $fileUploader,
        FileNameGeneratorFacade             $filenameGenerator
    )
    {
//        $this->wsCnes = $wsCnes;
        $this->perfilRepository = $perfilRepository;
        $this->identidadeGeneroRepository = $identidadeGeneroRepository;
        $this->projetoPessoaRepository = $projetoPessoaRepository;
        $this->projetoRepository = $projetoRepository;
        $this->pessoaFisicaRepository = $pessoaFisicaRepository;
        $this->grupoAtuacaoRepository = $grupoAtuacaoRepository;
        $this->enderecoRepository = $enderecoRepository;
        $this->cursoGraduacaoRepository = $cursoGraduacaoRepository;
        $this->titulacaoRepository = $titulacaoRepository;
        $this->bancoRepository = $bancoRepository;
        $this->agenciaBancariaRepository = $agenciaBancariaRepository;
        $this->municipioRepository = $municipioRepository;
        $this->categoriaProfissionalRepository = $categoriaProfissionalRepository;
        $this->cepRepository = $cepRepository;
        $this->dadoPessoalRepository = $dadoPessoalRepository;
        $this->enderecoWebRepository = $enderecoWebRepository;
        $this->telefoneRepository = $telefoneRepository;
        $this->projetoPessoaGrupoAtuacaoRepository = $projetoPessoaGrupoAtuacaoRepository;
        $this->areaTematicaRepository = $areaTematicaRepository;
        $this->fileUploader = $fileUploader;
        $this->filenameGenerator = $filenameGenerator;
        $this->eventDispatcher = $eventDispatcher;
    }

    /**
     * @param CadastrarParticipanteCommand $command
     * @return ProjetoPessoa
     * @throws \UnexpectedValueException
     */
    public function handle(CadastrarParticipanteCommand $command)
    {
        $pessoaFisica = $this->getPessoaFisicaIfCPFExists($command->getNuCpf());
        $cep   = $this->getCEPIfExists($command->getCoCep());
        $banco = $command->getCoBanco() ? $this->getBancoIfExists($command->getCoBanco()) : $command->getCoBanco();
        $banco = !($banco) ? null : $banco;
        // $agenciaBancaria = $this->getAgenciaBancariaIfExists($command);
        $agenciaBancaria = $command->getCoAgenciaBancaria();
        $conta           = $command->getCoConta();
        $conta           = !($conta) ? ' ' : $conta;
        $genero          = $this->getGeneroValid($command->getGenero());
        $projeto = $this->getProjetoIfNotExistsProjetoVinculado($pessoaFisica, $command);
        $perfil = $this->getPerfilIfNonViolatedConstraints($command);

        if ($perfil->getCoSeqPerfil() == Perfil::PERFIL_PRECEPTOR) {
            $this->constraintCNES($command);
        }

        $pessoaPerfil = $pessoaFisica->addPerfil($perfil);

        if( is_null($command->getNoDocumentoBancario()) ) {
            throw new \InvalidArgumentException('É obrigatório anexar o comprovante bancário.');
        }

        if ($command->getNoDocumentoBancario()) {
            $filename = $this->filenameGenerator->generate($command->getNoDocumentoBancario());
        }

        if ($perfil->getCoSeqPerfil() == Perfil::PERFIL_ESTUDANTE && is_null($command->getNoDocumentoMatricula())) {
            throw new \InvalidArgumentException('É obrigatório anexar o comprovante de matrícula para estudantes.');
        }

        if ($command->getNoDocumentoMatricula()) {
            $filenameMatricula = $this->filenameGenerator->generate($command->getNoDocumentoMatricula());
        }

        $projetoPessoa = $pessoaPerfil->addProjetoPessoa($projeto,
                                                         $command->getStVoluntarioProjeto(),
                                                         $command->getCoEixoAtuacao(),
                                                         $genero,
                                                         (isset($filename)) ? $filename : null,
                                                         (isset($filenameMatricula)) ? $filenameMatricula : null );

        if (isset($filename)) {
            $this->fileUploader->upload($command->getNoDocumentoBancario(), $filename);
        }

        if (isset($filenameMatricula)) {
            $this->fileUploader->upload($command->getNoDocumentoMatricula(), $filenameMatricula);
        }

        $this->addDadosAcademicos($projetoPessoa, $command);

        $this->addCursoGraduacao($projetoPessoa, $command);

        $this->addCursoLecionados($projetoPessoa, $command);

        $this->addGrupoAtuacao($projetoPessoa, $projeto, $perfil, $command);

        $this->addGrupoTutorial($projetoPessoa, $command);

        $this->addEndereco($pessoaFisica, $cep, $command);

        $this->addTelefones($pessoaFisica, $command);

        $pessoaFisica->getPessoa()->addEnderecoWeb($command->getDsEnderecoWeb());

        $dadoPessoal = $this->dadoPessoalRepository->findOneBy(array(
            'pessoaFisica' => $command->getNuCpf()
        ));

        if (!$dadoPessoal) {
            $pessoaFisica->setDadoPessoal($banco, $agenciaBancaria, $conta);
        } else {
            $pessoaFisica->getDadoPessoal()->setBanco($banco);
            $pessoaFisica->getDadoPessoal()->setAgencia($agenciaBancaria);
            $pessoaFisica->getDadoPessoal()->setConta($conta);
        }

        $this->projetoPessoaRepository->add($projetoPessoa);

        $this->eventDispatcher->dispatch(
            HandleSituacaoGrupoAtuacaoEvent::NAME,
            new HandleSituacaoGrupoAtuacaoEvent($projetoPessoa)
        );

        return $projetoPessoa;
    }

}
