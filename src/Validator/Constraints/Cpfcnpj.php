<?php

namespace App\Validator\Constraints;

use Symfony\Component\Validator\Constraint;

/**
 * @Annotation
 */
class Cpfcnpj extends Constraint
{
    public $message_cpfcnpj = 'Este CPF/CPNJ é inválido';
    public $message_cpf = 'Este CPF é inválido';
    public $message_cnpj = 'Este CPNJ é inválido';
    public $aceitar = 'cpfcnpj';
    public $aceitar_formatado = true;
}
