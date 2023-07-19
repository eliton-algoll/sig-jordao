<?php

namespace AppBundle\CommandBus;

use AppBundle\Entity\ProjetoPessoa;
use AppBundle\Entity\Perfil;
use AppBundle\Event\HandleSituacaoGrupoAtuacaoEvent;
use AppBundle\Repository\AgenciaBancariaRepository;
use AppBundle\Repository\AreaTematicaRepository;
use AppBundle\Repository\BancoRepository;
use AppBundle\Repository\CategoriaProfissionalRepository;
use AppBundle\Repository\CepRepository;
use AppBundle\Repository\CursoGraduacaoRepository;
use AppBundle\Repository\DadoPessoalRepository;
use AppBundle\Repository\EnderecoRepository;
use AppBundle\Repository\EnderecoWebRepository;
use AppBundle\Repository\GrupoAtuacaoRepository;
use AppBundle\Repository\IdentidadeGeneroRepository;
use AppBundle\Repository\MunicipioRepository;
use AppBundle\Repository\PerfilRepository;
use AppBundle\Repository\PessoaFisicaRepository;
use AppBundle\Repository\ProjetoPessoaGrupoAtuacaoRepository;
use AppBundle\Repository\ProjetoPessoaRepository;
use AppBundle\Repository\ProjetoRepository;
use AppBundle\Repository\TelefoneRepository;
use AppBundle\Repository\TitulacaoRepository;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

class CadastrarParticipanteHandler extends ParticipanteHandlerAbstract
{

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
        EventDispatcherInterface            $eventDispatcher
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

        $projetoPessoa = $pessoaPerfil->addProjetoPessoa($projeto, $command->getStVoluntarioProjeto(),
            $command->getCoEixoAtuacao(), $genero);

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
