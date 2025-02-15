<?php

namespace App\Exception;

final class ValorBolsaHasInFolhaException extends \Exception
{
    public function __construct()
    {
        parent::__construct('Já existe folha de pagamento processada para o início de vigência (mês/ano) informado. Nesse caso, só será permitido atualizar os valores de bolsa para a próxima referência (mês/ano) de folha de pagamento.');
    }
}
