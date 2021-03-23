<?php

namespace AppBundle\Cpb\Exception;

final class ArquivoNotLoadedException extends \Exception
{
    public function __construct()
    {
        parent::__construct('Arquivo não carregado. Para carregar o arquivo deve chamar o método load.');
    }
}
