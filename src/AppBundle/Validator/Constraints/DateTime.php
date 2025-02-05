<?php

namespace AppBundle\Validator\Constraints;

use Symfony\Component\Validator\Constraints\DateTime as Dt;

/**
 * @Annotation
 */
class DateTime extends Dt
{
    public $message = 'Data inválida.';

    public $format = 'Y-m-d H:i:s';
}
