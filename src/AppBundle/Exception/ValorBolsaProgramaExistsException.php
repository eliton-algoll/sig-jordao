<?php

namespace AppBundle\Exception;

final class ValorBolsaProgramaExistsException extends \Exception
{
    public function __construct()
    {
        parent::__construct('Já existe valor de bolsa cadastrado para o início de vigência (mês/ano) informado. O valor poderá ser alterado até que a folha de pagamento com mesma referência seja aberta.');
    }
}
