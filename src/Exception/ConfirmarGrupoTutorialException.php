<?php

namespace App\Exception;

final class ConfirmarGrupoTutorialException extends \Exception
{
    /**
     * @return ConfirmarGrupoTutorialException
     */
    public static function onGrupoExceedTotalTutor()
    {
        return new static('É permitido ter 1 (um) Tutor bolsista.');
    }

    /**
     * @return ConfirmarGrupoTutorialException
     */
    public static function onGrupoNotHasTutor()
    {
        return new static('É necessário ter 1 (um) Tutor bolsista.');
    }

    /**
     * @return ConfirmarGrupoTutorialException
     */
    public static function onGrupoExceedTotalCoordenadorDeGrupo()
    {
        return new static('É permitido ter 1 (um) Coordenador de Grupo bolsista.');
    }

    /**
     * @return ConfirmarGrupoTutorialException
     */
    public static function onGrupoNotHasCoordenadorDeGrupo()
    {
        return new static('É necessário ter 1 (um) Coordenador de Grupo bolsista cadastrado.');
    }

    /**
     * @return ConfirmarGrupoTutorialException
     */
    public static function onAreaDeAtuacaoTutorEqualCoordenadoDeGrupo()
    {
        return new static('O Tutor e Coordenador de Grupo devem pertencer a categoria profissional distintas.');
    }

    /**
     * @return ConfirmarGrupoTutorialException
     */
    public static function onGrupoNotHasMinimumCursoGraduacaoDistincts()
    {
        return new static('É necessário ter Estudantes bolsistas distribuídos, em no mínimo, 3 (três) cursos de graduação distintos.');
    }

    /**
     * @return ConfirmarGrupoTutorialException
     */
    public static function onMaximumEstudantesExceeded()
    {
        return new static('É permitido ter no máximo 6 (seis) Estudantes bolsistas cadastrados.');
    }

    /**
     * @return ConfirmarGrupoTutorialException
     */
    public static function onMinimumEstudantesNotReached()
    {
        return new static('É necessário ter no mínimo 4 (quatro) Estudantes bolsistas cadastrados.');
    }

    /**
     * @return ConfirmarGrupoTutorialException
     */
    public static function onMaximumPreceptoresExceeded()
    {
        return new static('É permitido ter no máximo 4 (quatro) Preceptores bolsistas cadastrados.');
    }

    /**
     * @return ConfirmarGrupoTutorialException
     */
    public static function onMinimumPreceptoresNotReached()
    {
        return new static('É necessário ter no mínimo 2 (dois) Preceptores bolsistas cadastrados.');
    }

    /**
     * @return ConfirmarGrupoTutorialException
     */
    public static function onProfissoesPreceptoresAreEquals()
    {
        return new static('É necessário ter Preceptores bolsistas distribuídos, em no mínimo, 2 (duas) categorias profissionais distintas.');
    }
}