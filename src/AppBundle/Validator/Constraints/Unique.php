<?php

namespace AppBundle\Validator\Constraints;

use Symfony\Component\Validator\Constraint;

/**
 * @Annotation
 */
class Unique extends Constraint
{
    public $message = 'Valores duplicados: [%duplicates%]';
}
