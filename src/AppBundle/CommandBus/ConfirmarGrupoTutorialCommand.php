<?php

namespace AppBundle\CommandBus;

use AppBundle\Entity\GrupoAtuacao;
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