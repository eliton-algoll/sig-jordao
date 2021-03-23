<?php

namespace AppBundle\Cpb\Exception;

final class ArquivoLoadedException extends \Exception
{
    public function __construct()
    {
        parent::__construct('Arquivo já está carregado.');
    }
}
