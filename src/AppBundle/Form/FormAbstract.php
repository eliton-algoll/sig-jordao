<?php

namespace AppBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\DataTransformer\DateTimeToStringTransformer;

abstract class FormAbstract extends AbstractType
{
    protected function getDateTimeTransformer()
    {
        return new DateTimeToStringTransformer(null, null, 'd/m/Y');
    }
}