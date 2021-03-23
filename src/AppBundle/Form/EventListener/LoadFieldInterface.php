<?php

namespace AppBundle\Form\EventListener;

use Symfony\Component\Form\Form;

interface LoadFieldInterface
{
    /**
     * Adiciona um campo ao form
     */
    public function addField(Form $form, $param);
}
