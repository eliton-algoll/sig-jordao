<?php

namespace App\Validator\Constraints;

use Symfony\Component\Validator\Constraint;

/**
 * Class Sei
 * @package App\Validator\Constraints
 *
 * @Annotation
 */
class Sei extends Constraint
{
    public $message = 'SEI Inválido.';
}