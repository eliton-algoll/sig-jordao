<?php

namespace AppBundle\CommandBus;

use AppBundle\Entity\GrupoAtuacao;
use AppBundle\Entity\Perfil;
use AppBundle\Entity\Projeto;
use AppBundle\Exception\ConfirmarGrupoTutorialException;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Context\ExecutionContextInterface;

final class ConfirmarGrupoTutorialCommand
{

    /**
     * @var GrupoAtuacao
     *
     * @Assert\NotBlank()
     */
    private $grupoAtuacao;

    /**
     * @param GrupoAtuacao $grupoAtuacao
     * @return ConfirmarGrupoTutorialCommand
     */
    public static function create(GrupoAtuacao $grupoAtuacao)
    {
        return new self($grupoAtuacao);
    }

    /**
     * ConfirmarGrupoTutorialCommand constructor.
     * @param GrupoAtuacao $grupoAtuacao
     */
    public function __construct(GrupoAtuacao $grupoAtuacao)
    {
        $this->grupoAtuacao = $grupoAtuacao;
    }

    /**
     * @return GrupoAtuacao
     */
    public function getGrupoAtuacao()
    {
        return $this->grupoAtuacao;
    }

    /**
     * @return Projeto
     */
    public function getProjeto()
    {
        return $this->projeto;
    }

    /**
     * @param ExecutionContextInterface $context
     *
     * @Assert\Callback
     */
    public function validate(ExecutionContextInterface $context)
    {
        $errors = $this->validateGrupoTutorial();

        if (0 !== count($errors)) {
            foreach ($errors as $error) {
                $context->buildViolation($error->getMessage())
                    ->addViolation();
            }
        }
    }

    /**
     * @param $projetosPessaGrupoAtuacao
     * @return array
     */
    public function validateComposicaoGrupoTutorial($projetosPessaGrupoAtuacao, $categoriasProfissionais)
    {
        $error = array();
        $coordenadorGrupo = 0;
        $tutor = 0;
        $preceptor = 0;
        $estudante = 0;

        if( count($projetosPessaGrupoAtuacao) < 12) {
            $error[] = ['msg' => 'O '.$this->getGrupoAtuacao()->getNoGrupoAtuacao().' deve ser composto por 12 (doze) participantes.'];
        }
        foreach ($projetosPessaGrupoAtuacao as $pro) {
            if( $pro->getProjetoPessoa()->getPessoaPerfil()->getPerfil()->getCoSeqPerfil() == Perfil::PERFIL_COORDENADOR_GRUPO ) {
                $coordenadorGrupo++;
            }
            if( $pro->getProjetoPessoa()->getPessoaPerfil()->getPerfil()->getCoSeqPerfil() == Perfil::PERFIL_TUTOR ) {
                $tutor++;
            }
            if( $pro->getProjetoPessoa()->getPessoaPerfil()->getPerfil()->getCoSeqPerfil() == Perfil::PERFIL_PRECEPTOR ) {
                $preceptor++;
            }
            if( $pro->getProjetoPessoa()->getPessoaPerfil()->getPerfil()->getCoSeqPerfil() == Perfil::PERFIL_ESTUDANTE ) {
                $estudante++;
            }
        }

        if( $coordenadorGrupo != 1 ) {
            $error[] = ['msg' => 'O '.$this->getGrupoAtuacao()->getNoGrupoAtuacao().' deve possuir 1 (um) coordenador de grupo.'];
        }

        if( $tutor != 1 ) {
            $error[] = ['msg' => 'O '.$this->getGrupoAtuacao()->getNoGrupoAtuacao().' deve possuir 1 (um) tutor.'];
        }

        if( $preceptor != 2 ) {
            $error[] = ['msg' => 'O '.$this->getGrupoAtuacao()->getNoGrupoAtuacao().' deve possuir 2 (dois) preceptores.'];
        }

        if( $estudante != 8 ) {
            $error[] = ['msg' => 'O '.$this->getGrupoAtuacao()->getNoGrupoAtuacao().' deve possuir 8 (oito) estudantes.'];
        }

        $listCategoriasProfissionais = $this->getCategoriaProfissionalPorArea($categoriasProfissionais);
        $estudantesPorArea = $this->countEstudantesPorAreaSaude($listCategoriasProfissionais, $projetosPessaGrupoAtuacao);
        if( $estudantesPorArea['saude'] != 6 ) {
            $error[] = ['msg' => 'O '.$this->getGrupoAtuacao()->getNoGrupoAtuacao().' deve possuir 6 (seis) estudantes da área da Saúde.'];
        }

        return $error;
    }

    private function countEstudantesPorAreaSaude($listCategoriasProfissionais, $projetosPessaGrupoAtuacao)
    {
        $categoriasSaude           = $listCategoriasProfissionais['categoriasSaude'];
        $categoriasCienciasHumanas = $listCategoriasProfissionais['categoriasCienciasHumanas'];
        $categoriasCienciasSociais = $listCategoriasProfissionais['categoriasCienciasSociais'];

        $estudantesSaudeEncontradosGrupo = 0;
        $estudantesCienciasHumanasEncontradosGrupo = 0;
        $estudantesCienciasSociaisEncontradosGrupo = 0;

        foreach ($projetosPessaGrupoAtuacao as $ppga) {
            if( $ppga->getProjetoPessoa()->getPessoaPerfil()->getPerfil()->getCoSeqPerfil() == Perfil::PERFIL_ESTUDANTE ) {

                foreach ($categoriasSaude as $cat) {
                    if( $ppga->getDescricaoCursoGraducao() == $cat->getDsCategoriaProfissional() ) {
                        $estudantesSaudeEncontradosGrupo++;
                    }
                }
//
//                foreach ($categoriasCienciasHumanas as $cat) {
//                    if( $ppga->getDescricaoCursoGraducao() == $cat->getDsCategoriaProfissional() ) {
//                        $estudantesCienciasHumanasEncontradosGrupo++;
//                    }
//                }
//
//                foreach ($categoriasCienciasSociais as $cat) {
//                    if( $ppga->getDescricaoCursoGraducao() == $cat->getDsCategoriaProfissional() ) {
//                        $estudantesCienciasSociaisEncontradosGrupo++;
//                    }
//                }
            }
        }

        return ['saude' => $estudantesSaudeEncontradosGrupo,
                'cienciasHumanas' => $estudantesCienciasHumanasEncontradosGrupo,
                'CienciasSociais' => $estudantesCienciasSociaisEncontradosGrupo ];

    }

    private function getCategoriaProfissionalPorArea($categoriasProfissionais_)
    {

        $categoriasSaude = [];
        $categoriasCienciasHumanas = [];
        $categoriasCienciasSociais  = [];
        foreach ($categoriasProfissionais_ as $categ) {
            switch ($categ->getTpAreaFormacao()) {
                case '1':
                    $categoriasSaude[] = $categ;
                    break;
                case '2':
                    $categoriasCienciasHumanas[] = $categ;
                    break;
                case '3':
                    $categoriasCienciasSociais[] = $categ;
                    break;
            }
        }

        return ['categoriasSaude' => $categoriasSaude,
                'categoriasCienciasHumanas' => $categoriasCienciasHumanas,
                'categoriasCienciasSociais' => $categoriasCienciasSociais ];

    }

    /**
     * @return array
     */
    private function validateGrupoTutorial()
    {
        $errors = [];
        $participantes = $this->grupoAtuacao->getProjetoPessoaGrupoAtuacaoBolsistas();

        if (6 < $this->grupoAtuacao->qtdEstudantesBolsistas()) {
            $errors[] = ConfirmarGrupoTutorialException::onMaximumEstudantesExceeded();
        }
        if (4 > $this->grupoAtuacao->qtdEstudantesBolsistas()) {
            $errors[] = ConfirmarGrupoTutorialException::onMinimumEstudantesNotReached();
        }
        if (0 === $this->grupoAtuacao->qtdTutoresBolsistas()) {
            $errors[] = ConfirmarGrupoTutorialException::onGrupoNotHasTutor();
        }
        if (1 < $this->grupoAtuacao->qtdTutoresBolsistas()) {
            $errors[] = ConfirmarGrupoTutorialException::onGrupoExceedTotalTutor();
        }
        if (0 === $this->grupoAtuacao->qtdCoordenadoresGrupoBolsistas()) {
            $errors[] = ConfirmarGrupoTutorialException::onGrupoNotHasCoordenadorDeGrupo();
        }
        if (1 < $this->grupoAtuacao->qtdCoordenadoresGrupoBolsistas()) {
            $errors[] = ConfirmarGrupoTutorialException::onGrupoExceedTotalCoordenadorDeGrupo();
        }
        if (4 < $this->grupoAtuacao->qtdPreceptoresBolsistas()) {
            $errors[] = ConfirmarGrupoTutorialException::onMaximumPreceptoresExceeded();
        }
        if (2 > $this->grupoAtuacao->qtdPreceptoresBolsistas()) {
            $errors[] = ConfirmarGrupoTutorialException::onMinimumPreceptoresNotReached();
        }
        if (!$this->isEstudantesHasMinimumCursoGraduacaoDistincts()) {
            $errors[] = ConfirmarGrupoTutorialException::onGrupoNotHasMinimumCursoGraduacaoDistincts();
        }
        if (!$this->isPreceptoresHasMinimumCategoriaProfissionalDistincts()) {
            $errors[] = ConfirmarGrupoTutorialException::onProfissoesPreceptoresAreEquals();
        }
        if (!$this->isCoordenadorAndTutorHasCategoriaProfissionalNotEquals()) {
            $errors[] = ConfirmarGrupoTutorialException::onAreaDeAtuacaoTutorEqualCoordenadoDeGrupo();
        }

        return $errors;
    }

    /**
     * @return bool
     */
    private function isEstudantesHasMinimumCursoGraduacaoDistincts()
    {
        $cursos = [];

        foreach ($this->grupoAtuacao->getEstudantesBolsistas() as $projetoPessoaGrupoAtuacao) {
            if ($cursoGraduacao = $projetoPessoaGrupoAtuacao->getProjetoPessoa()->getProjetoPessoaCursoGraduacaoAtivo()->first()->getCursoGraduacao()) {
                $cursos[] = $cursoGraduacao->getCoSeqCursoGraduacao();
            }
        }

        return (2 < count(array_unique($cursos)));
    }

    /**
     * @return bool
     */
    private function isPreceptoresHasMinimumCategoriaProfissionalDistincts()
    {
        $categorias = [];
        $preceptores = $this->grupoAtuacao->getPreceptoresBolsistas();

        foreach ($preceptores as $projetoPessoaGrupoAtuacao) {
            $categorias[] = $projetoPessoaGrupoAtuacao
                ->getProjetoPessoa()
                ->getDadoAcademicoAtivo()
                ->getCategoriaProfissional()
                ->getCoSeqCategoriaProfissional();
        }

        return (1 === $preceptores->count() || (1 < $preceptores->count() && 1 < count(array_unique($categorias))));
    }

    /**
     * @return bool
     */
    private function isCoordenadorAndTutorHasCategoriaProfissionalNotEquals()
    {
        $tutores = $this->grupoAtuacao->getTutoresBolsistas();
        $coordenadores = $this->grupoAtuacao->getCoordenadoresGrupoBolsistas();

        if (1 !== $tutores->count() || 1 !== $coordenadores->count()) {
            return true;
        }

        $categoriaTutor = $tutores->first()->getProjetoPessoa()->getDadoAcademicoAtivo()->getCategoriaProfissional();
        $categoriaCoordenador = $coordenadores->first()->getProjetoPessoa()->getDadoAcademicoAtivo()->getCategoriaProfissional();

        return $categoriaCoordenador != $categoriaTutor;
    }

}