<?php

namespace AppBundle\Validator\Constraints;

use Symfony\Component\Validator\Constraint;

/**
 * Class Sei
 * @package AppBundle\Validator\Constraints
 *
 * @Annotation
 */
class Sei extends Constraint
{
    public $message = 'SEI Inválido.';
}