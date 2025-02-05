<?php

namespace AppBundle\Exception;

final class SiparInvalidoException extends \Exception
{
    public function __construct($message = 'SEI inválido.')
    {
        parent::__construct($message);
    }

    /**
     * @return SiparInvalidoException
     */
    public static function onSiparBelongsToExpiredPrograma()
    {
        return new static('O número SEI informado não pertence a um programa vigente.');
    }

    public static function onSiparNotBelongsToPrograma()
    {
        return new static('O número SEI informado não pertence ao programa selecionado.');
    }
}
