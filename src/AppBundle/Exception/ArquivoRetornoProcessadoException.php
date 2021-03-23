<?php

namespace AppBundle\Exception;

final class ArquivoRetornoProcessadoException extends \Exception
{
    public function __construct()
    {
        parent::__construct('O arquivo selecionado já foi recepcionado pelo SIGPET. Selecione novo arquivo e refaça a operação ou faça a exclusão da recepção realizada anteriormente e refaça a operação.');
    }
}
