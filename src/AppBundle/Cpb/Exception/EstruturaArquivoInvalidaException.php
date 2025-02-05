<?php

namespace AppBundle\Cpb\Exception;

final class EstruturaArquivoInvalidaException extends \Exception
{
    public function __construct()
    {
        parent::__construct('O arquivo selecionado tem sua estrutura diferente do estabelecido pelo banco para esse tipo de arquivo. Selecione novo arquivo e refaça a operação.');
    }
}
