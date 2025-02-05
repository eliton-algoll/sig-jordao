<?php

namespace AppBundle\CommandBus;

use AppBundle\Entity\ProjetoPessoa;
use AppBundle\Entity\ProjetoPessoaGrupoAtuacaoAreaTematica;
use Symfony\Component\Validator\Constraints as Assert;

class AtualizarParticipanteCommand extends CadastrarParticipanteCommand
{
    /**
     * @var integer
     * @Assert\NotBlank()
     */
    private $coSeqProjetoPessoa;

    public function __construct(ProjetoPessoa $projetoPessoa = null)
    {
        if ($projetoPessoa) {
            $this->setValuesByEntity($projetoPessoa);
        }
    }

    /**
     * @return integer
     */
    public function getCoSeqProjetoPessoa()
    {
        return $this->coSeqProjetoPessoa;
    }

    /**
     * @param integer $coSeqProjetoPessoa
     * @return AtualizarParticipanteCommand
     */
    public function setCoSeqProjetoPessoa($coSeqProjetoPessoa)
    {
        $this->coSeqProjetoPessoa = $coSeqProjetoPessoa;
        return $this;
    }

    /**
     * @param ProjetoPessoa $projetoPessoa
     */
    public function setValuesByEntity(ProjetoPessoa $projetoPessoa)
    {
        $pessoaFisica = $projetoPessoa->getPessoaPerfil()->getPessoaFisica();
        $this->coSeqProjetoPessoa = $projetoPessoa->getCoSeqProjetoPessoa();
        $this->projeto = $projetoPessoa->getProjeto();
        $this->perfil = $projetoPessoa->getPessoaPerfil()->getPerfil();
        $this->stVoluntarioProjeto = $projetoPessoa->getStVoluntarioProjeto();
        $this->nuCpf = $pessoaFisica->getNuCpf();
        $this->genero = $projetoPessoa->getIdentidadeGenero()->getCoIdentidadeGenero();
        $this->noPessoa = $pessoaFisica->getPessoa()->getNoPessoa();
        $this->dtNascimento = $pessoaFisica->getDtNascimento();

        $dadoPessoal = $pessoaFisica->getDadoPessoal();
        $this->noMae = $dadoPessoal->getPessoaFisica()->getNoMae();
        $this->coBanco = $dadoPessoal->getBanco();//->getCoBanco();
        $this->coAgenciaBancaria = $dadoPessoal->getAgencia();
        $this->coConta = $dadoPessoal->getConta();

        $endereco = $pessoaFisica->getPessoa()->getEnderecoAtivo();
        if (is_object($endereco)) {
            $this->noLogradouro = $endereco->getNoLogradouro();
            $this->nuLogradouro = $endereco->getNuLogradouro();
            $this->dsComplemento = $endereco->getDsComplemento();
            $this->noBairro = $endereco->getNoBairro();
            $this->coUf = $endereco->getMunicipio()->getCoUfIbge();
            $this->coMunicipioIbge = $endereco->getMunicipio();
            $this->dsEnderecoWeb = $pessoaFisica->getPessoa()->getEnderecoWebAtivo();
            $this->coCep = $endereco->getCep()->getNuCep();
        }

        $this->coEixoAtuacao = $projetoPessoa->getCoEixoAtuacao();

        $dadoAcademico = $projetoPessoa->getDadoAcademicoAtivo();
        $this->categoriaProfissional = $dadoAcademico ? $dadoAcademico->getCategoriaProfissional() : null;
        $this->coCnes = $dadoAcademico ? $dadoAcademico->getCoCnes() : null;
        $this->titulacao = $dadoAcademico ? $dadoAcademico->getTitulacao() : null;
        $this->cursoGraduacao = $projetoPessoa->getCursoGraduacaoEstudanteOuPreceptor();
        $this->nuAnoIngresso = $dadoAcademico ? $dadoAcademico->getNuAnoIngresso() : null;
        $this->nuMatriculaIES = $dadoAcademico ? $dadoAcademico->getNuMatricula() : null;
        $this->nuSemestreAtual = $dadoAcademico ? $dadoAcademico->getNuSemestre() : null;
        $this->stAlunoRegular = $dadoAcademico ? $dadoAcademico->getStAlunoRegular() : null;

        $stDeclaracaoCursoPenultimo = null;
        if (($dadoAcademico) && (!is_null($dadoAcademico->getStDeclaracaoCursoPenultimo()))) {
            $stDeclaracaoCursoPenultimo = ($dadoAcademico->getStDeclaracaoCursoPenultimo() === 'S');
        }

        $this->stDeclaracaoCursoPenultimo = $stDeclaracaoCursoPenultimo;

        $projetosPessoaGrupoAtuacao = $projetoPessoa->getProjetoPessoaGrupoAtuacaoAtivo();

        if (
            (0 !== $projetosPessoaGrupoAtuacao->count()) &&
            ($projetoPessoa->getProjeto()->getPublicacao()->getPrograma()->isGrupoTutorial())
        ) {
            $this->grupoTutorial = $projetosPessoaGrupoAtuacao->first()->getGrupoAtuacao();
        }

        $this->telefones = array();
        foreach ($pessoaFisica->getPessoa()->getTelefonesAtivos() as $telefone) {
            $this->telefones[] = $telefone;
        }

        $this->cursosLecionados = array();
        foreach ($projetoPessoa->getCursosLecionados() as $cursoGraduacao) {
            $this->cursosLecionados[] = $cursoGraduacao;
        }

        $this->areaTematica = array();
        if ($projetoPessoa->getProjeto()->getPublicacao()->getPrograma()->isAreaAtuacao()) {
            foreach ($projetoPessoa->getProjetoPessoaGrupoAtuacaoAtivo() as $projetoPessoaGrupoAtuacao) {
                $this->areaTematica[] = $projetoPessoaGrupoAtuacao->getGrupoAtuacao()->getCoSeqGrupoAtuacao();
            }
        } else {
            foreach ($projetoPessoa->getProjetoPessoaGrupoAtuacaoAtivo() as $projetoPessoaGrupoAtuacao) {
                $this->areaTematica = $projetoPessoaGrupoAtuacao->getProjetosPessoasGrupoAtuacaoAreasTematicasAtivos()->map(
                    function (ProjetoPessoaGrupoAtuacaoAreaTematica $projetoPessoaGrupoAtuacaoAreaTematica) {
                        return $projetoPessoaGrupoAtuacaoAreaTematica->getAreaTematica();
                    }
                )->toArray();
            }
        }
    }

}
