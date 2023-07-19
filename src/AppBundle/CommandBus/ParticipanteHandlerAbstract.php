<?php

namespace AppBundle\CommandBus;

use AppBundle\Entity\GrupoAtuacao;
use AppBundle\Entity\Perfil;
use AppBundle\Entity\IdentidadeGenero;
use AppBundle\Entity\ProjetoPessoa;
use AppBundle\Entity\Cep;
use AppBundle\Entity\Projeto;
use AppBundle\Entity\PessoaFisica;
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
use AppBundle\Repository\MunicipioRepository;
use AppBundle\Repository\PerfilRepository;
use AppBundle\Repository\IdentidadeGeneroRepository;
use AppBundle\Repository\PessoaFisicaRepository;
use AppBundle\Repository\ProjetoPessoaGrupoAtuacaoRepository;
use AppBundle\Repository\ProjetoPessoaRepository;
use AppBundle\Repository\ProjetoRepository;
use AppBundle\Repository\TelefoneRepository;
use AppBundle\Repository\TitulacaoRepository;
use Symfony\Component\EventDispatcher\EventDispatcher;

class ParticipanteHandlerAbstract
{
    /**
     * @var Cnes
     */
    protected $wsCnes;
    
    /**
     * @var PerfilRepository
     */
    protected $perfilRepository;

    /**
     * @var IdentidadeGeneroRepository
     */
    protected $identidadeGeneroRepository;
    
    /**
     * @var ProjetoPessoaRepository
     */
    protected $projetoPessoaRepository;
    
    /**
     * @var ProjetoRepository
     */
    protected $projetoRepository;
    
    /**
     * @var PessoaFisicaRepository
     */
    protected $pessoaFisicaRepository;
    
    /**
     * @var GrupoAtuacaoRepository
     */
    protected $grupoAtuacaoRepository;
    
    /**
     * @var EnderecoRepository
     */
    protected $enderecoRepository;
    
    /**
     * @var CursoGraduacaoRepository
     */
    protected $cursoGraduacaoRepository;
    
    /**
     * @var TitulacaoRepository
     */
    protected $titulacaoRepository;
    
    /**
     * @var BancoRepository
     */
    protected $bancoRepository;
    
    /**
     * @var AgenciaBancariaRepository
     */
    protected $agenciaBancariaRepository;
    
    /**
     * @var MunicipioRepository
     */
    protected $municipioRepository;
    
    /**
     * @var CategoriaProfissionalRepository
     */
    protected $categoriaProfissionalRepository;
    
    /**
     * @var CepRepository
     */
    protected $cepRepository;
    
    /**
     * @var DadoPessoalRepository
     */
    protected $dadoPessoalRepository;
    
    /**
     * @var EnderecoWebRepository
     */
    protected $enderecoWebRepository;
    
    /**
     * @var TelefoneRepository
     */
    protected $telefoneRepository;
    
    /**
     * @var ProjetoPessoaGrupoAtuacaoRepository
     */
    protected $projetoPessoaGrupoAtuacaoRepository;

    /**
     * @var AreaTematicaRepository
     */
    protected $areaTematicaRepository;

    /**
     * @var EventDispatcher
     */
    protected $eventDispatcher;
    
    /**
     * @param CadastrarParticipanteCommand $command
     * @throws \InvalidArgumentException
     */
    protected function constraintCNES(CadastrarParticipanteCommand $command)
    {
        if (!$command->getCoCnes()) {
            throw new \InvalidArgumentException('Número do CNES é obrigatório para o perfil Preceptor.');
        }

        if ($command->getCoCnes() && !$this->isCnesValido($command->getCoCnes())) {
            throw new \InvalidArgumentException('Número do CNES inválido ou inexistente.');
        }
    }
      
    /**
     * @param string $coCnes
     * @return boolean
     */
    protected function isCnesValido($coCnes)
    {
        try {
            return (bool) $this->wsCnes->consultarEstabelecimentoSaude($coCnes);
        } catch (\SoapFault $e) {
            return false;
        }
    }
    
    /**
     * @param Projeto $projeto
     * @param integer $coGrupoAtuacao
     * @param Perfil $perfil
     * @param string $stVoluntarioProjeto
     * @param ProjetoPessoa $projetoPessoa
     * @throws \InvalidArgumentException
     */
    protected function constraintCoordenadorDeGrupo($projeto, $coGrupoAtuacao, $perfil, $stVoluntarioProjeto, $projetoPessoa)
    {
        if($perfil->getNoRole() == Perfil::ROLE_COORDENADOR_GRUPO) {
            $projetoPessoaGrupoAtuacao = $this->projetoPessoaGrupoAtuacaoRepository->hasPerfilNaoVoluntario($projeto, $coGrupoAtuacao, $perfil);
            $grupoAtuacao = $this->grupoAtuacaoRepository->find($coGrupoAtuacao);
            if ($projetoPessoaGrupoAtuacao && $stVoluntarioProjeto == 'N' && !$projetoPessoa->hasGrupoAtuacao($grupoAtuacao)) {
                throw new \InvalidArgumentException('O participante ' . $projetoPessoaGrupoAtuacao['noPessoa'] . ' já está cadastrado como Coordenador de Grupo não voluntário neste Grupo de atuação!');
            }
        }
    }
    
    /**
     * @param string $nuCpf
     * @return PessoaFisica
     * @throws \UnexpectedValueException
     */
    protected function getPessoaFisicaIfCPFExists($nuCpf)
    {
        $pessoaFisica = $this->pessoaFisicaRepository->find($nuCpf);
        
        if (!$pessoaFisica) {
            throw new \UnexpectedValueException('CPF inexistente');
        }
        
        return $pessoaFisica;
    }
    
    /**
     * @param string $nuCep
     * @return Cep $cep
     * @throws \UnexpectedValueException
     */
    protected function getCEPIfExists($nuCep)
    {
        $cep = $this->cepRepository->findOneBy(array(
            'nuCep' => $nuCep,
            'stRegistroAtivo' => 'S'
        ));
        
        if (!$cep) {
            throw new \UnexpectedValueException('CEP inválido');
        }
        
        return $cep;
    }
    
    /**
     * @param integer $coBanco
     * @return Banco $banco
     * @throws \UnexpectedValueException
     */
    protected function getBancoIfExists($coBanco)
    {
        $banco = $this->bancoRepository->find($coBanco);
        
        if (!$banco) {
            throw new \UnexpectedValueException('Banco não localizado');
        }
        
        return $banco;
    }
    
//    /**
//     * @param CadastrarParticipanteCommand $command
//     * @return AgenciaBancaria $agenciaBancaria
//     * @throws \UnexpectedValueException
//     */
//    protected function getAgenciaBancariaIfExists(CadastrarParticipanteCommand $command)
//    {
//        $agenciaBancaria = $this->agenciaBancariaRepository->findOneBy(array(
//            'coAgenciaBancaria' => str_pad($command->getCoAgenciaBancaria(), 6, 0, STR_PAD_LEFT),
//            'coBanco'           => $command->getCoBanco(),
//            'stRegistroAtivo'   => 'S'
//        ));
//
//        if (!$agenciaBancaria) {
//            throw new \UnexpectedValueException('Agência Bancária não pertencente ao Banco do Brasil');
//        }
//
//        return $agenciaBancaria;
//    }
    
    /**
     * @param PessoaFisica $pessoaFisica
     * @param CadastrarParticipanteCommand $command
     * @return Projeto $projeto
     * @throws \DomainException
     */
    protected function getProjetoIfNotExistsProjetoVinculado($pessoaFisica, CadastrarParticipanteCommand $command)
    {
        if (!$projeto = $command->getProjeto()) {
            $projeto = $this->projetoRepository->getBySipar($command->getNuSei());
        }

        if (!$projeto || !$projeto->getPublicacao()->isVigente()) {
            throw new \DomainException('SEI inválido.');
        }

        if ($pessoaFisica->isProjetoVinculado($projeto)) {
            throw new \DomainException('O participante só pode participar do projeto com um único perfil.');
        }
        
        return $projeto;
    }

    /**
     * @param $genero
     * @return IdentidadeGenero $generoObj
     * @throws \UnexpectedValueException
     */
    protected function getGeneroValid($genero)
    {
        $generoObj = $this->identidadeGeneroRepository->find($genero);
        if (!$generoObj) {
            throw new \UnexpectedValueException('Favor informar, o campo genêro!');
        }
        return $generoObj;
    }

    /**
     * @param CadastrarParticipanteCommand $command
     * @return Perfil $perfil
     * @throws \UnexpectedValueException
     */    
    protected function getPerfilIfNonViolatedConstraints(CadastrarParticipanteCommand $command)
    {
        $perfil = $this->perfilRepository->find($command->getPerfil());

        if (!$projeto = $command->getProjeto()) {
            $projeto = $this->projetoRepository->getBySipar($command->getNuSei());
        }
        
        if (
            ($perfil->isTutor() || $perfil->isCoordenadorGrupo()) &&
            !$command->getCursosLecionados() &&
            !$projeto->getPublicacao()->getPrograma()->isGrupoTutorial()
        ) {
            throw new \UnexpectedValueException('Favor informar, pelo menos, um curso lecionado!');
        }
        
        return $perfil;
    }
    
    /**
     * @param ProjetoPessoa $projetoPessoa
     * @param CadastrarParticipanteCommand $command
     */
    protected function addDadosAcademicos(ProjetoPessoa $projetoPessoa, CadastrarParticipanteCommand $command)
    {
        $projetoPessoa->addDadosAcademicos(
            $command->getTitulacao() ? $this->titulacaoRepository->find($command->getTitulacao()) : null, 
            $command->getCategoriaProfissional() ? $this->categoriaProfissionalRepository->find($command->getCategoriaProfissional()) : null,
            $command->getNuAnoIngresso(), 
            $command->getNuMatriculaIES(), 
            $command->getNuSemestreAtual(), 
            $command->getCoCnes(),
            $command->getStAlunoRegular(),
            $command->getStDeclaracaoCursoPenultimo()
        );
    }
    
    /**
     * @param ProjetoPessoa $projetoPessoa
     * @param CadastrarParticipanteCommand $command
     */
    protected function addCursoGraduacao(ProjetoPessoa $projetoPessoa, CadastrarParticipanteCommand $command)
    {
        if ($command->getCursoGraduacao()) {
            $cursoGraduacao = $this->cursoGraduacaoRepository->find($command->getCursoGraduacao());
            $projetoPessoa->addCursoGraduacao($cursoGraduacao);
        }
    }
    
    /**
     * @param ProjetoPessoa $projetoPessoa
     * @param CadastrarParticipanteCommand $command
     */
    protected function addCursoLecionados(ProjetoPessoa $projetoPessoa, CadastrarParticipanteCommand $command)
    {
        if (count($command->getCursosLecionados())) {
            foreach ($command->getCursosLecionados() as $coCursoGraduacao) {
                $cursoGraduacaoLecionado = $this->cursoGraduacaoRepository->find($coCursoGraduacao);
                $projetoPessoa->addCursoGraduacao($cursoGraduacaoLecionado);
            }
        }
    }
    
    /**
     * @param PessoaFisica $pessoaFisica
     * @param Cep $cep
     * @param CadastrarParticipanteCommand $command
     */
    protected function addEndereco(PessoaFisica $pessoaFisica, Cep $cep, CadastrarParticipanteCommand $command)
    {
        $pessoa = $pessoaFisica->getPessoa();
        
        if (!$pessoa->getEnderecoAtivo()) {
            $pessoaFisica->getPessoa()
                ->addEndereco(
                    $cep, 
                    $this->municipioRepository->find($command->getCoMunicipioIbge()), 
                    $command->getNoLogradouro(), 
                    $command->getDsComplemento(), 
                    $command->getNoBairro(), 
                    $command->getNuLogradouro()
            );
        } else {
            $endereco = $pessoa->getEnderecoAtivo();
            $endereco->setCep($cep);
            $endereco->setMunicipio($this->municipioRepository->find($command->getCoMunicipioIbge()));
            $endereco->setNoLogradouro($command->getNoLogradouro());
            $endereco->setDsComplemento($command->getDsComplemento());
            $endereco->setNoBairro($command->getNoBairro());
            $endereco->setNuLogradouro($command->getNuLogradouro());
        }
    }
    
    /**
     * @param ProjetoPessoa $projetoPessoa
     * @param Projeto $projeto
     * @param Perfil $perfil
     * @param CadastrarParticipanteCommand $command
     */
    protected function addGrupoAtuacao(ProjetoPessoa $projetoPessoa, Projeto $projeto, Perfil $perfil, CadastrarParticipanteCommand $command)
    {
        if (count($command->getAreaTematica()) && $projeto->getPublicacao()->getPrograma()->isAreaAtuacao()) {
            foreach($command->getAreaTematica() as $coGrupoAtuacao) {
                $this->constraintCoordenadorDeGrupo($projeto, $coGrupoAtuacao, $perfil, $command->getStVoluntarioProjeto(), $projetoPessoa);
                $grupoAtuacao = $this->grupoAtuacaoRepository->find($coGrupoAtuacao);
                $projetoPessoa->addGrupoAtuacao($grupoAtuacao);
            }
        }
    }
    
    /**
     * @param PessoaFisica $pessoaFisica
     * @param CadastrarParticipanteCommand $command
     */
    protected function addTelefones(PessoaFisica $pessoaFisica, CadastrarParticipanteCommand $command)
    {
        $telefones = $command->getTelefones();
        $qtdTelefones = count($telefones['tpTelefone']);
        
        $this->constraintTelefones($telefones);
        
        for ($i = 0; $i < $qtdTelefones; $i++) {
            $pessoaFisica->getPessoa()->addTelefone(
                $telefones['nuDdd'][$i],
                $telefones['nuTelefone'][$i],
                $telefones['tpTelefone'][$i]
            );
        }
    }
    
    /**
     * @param array $telefones
     * @throws \UnexpectedValueException
     */
    protected function constraintTelefones($telefones)
    {
        if (!count($telefones['tpTelefone'])) {
            throw new \UnexpectedValueException('É necessário cadastrar algum telefone');
        }
    }

    /**
     * @param ProjetoPessoa $projetoPessoa
     * @param CadastrarParticipanteCommand $command
     * @throws \Exception
     */
    protected function addGrupoTutorial(ProjetoPessoa $projetoPessoa, CadastrarParticipanteCommand $command)
    {
        if (
            $projetoPessoa->getProjeto()->getPublicacao()->getPrograma()->isGrupoTutorial() &&
            !$projetoPessoa->getPessoaPerfil()->getPerfil()->isCoordenadorProjeto()
        ) {
            $projetoPessoa->addGrupoTutorial($command->getGrupoTutorial(), $command->getCoEixoAtuacao());

            $projetoPessoa->getProjetoPessoaGrupoAtuacaoByGrupoAtuacao($command->getGrupoTutorial())
                ->inativarAllProjetoPessoaGrupoAtuacaoAreaTematica();

            foreach ((array) $command->getAreaTematica() as $areaTematica) {
                $areaTematica = $this->areaTematicaRepository->find($areaTematica);
                $projetoPessoa->getProjetoPessoaGrupoAtuacaoByGrupoAtuacao($command->getGrupoTutorial())
                    ->addProjetoPessoaGrupoAtuacaoAreaTematica($areaTematica);
            }
        }
    }
}
