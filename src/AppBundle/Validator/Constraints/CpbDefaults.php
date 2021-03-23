<?php

namespace AppBundle\Validator\Constraints;

use Symfony\Component\Validator\Constraint;

/**
 * @Annotation
 */
class CpbDefaults extends Constraint
{
    public $message = "Valor inválido para o campo {{ campo }}: {{ value }}. Linha {{ linha }}. Deverá ser informado um valor válido definido pelo Sistema CPB do Banco do Brasil. Selecione novo arquivo e refaça a operação.";
    
    public $campo;
    
    public $linha;
}
