<?php

namespace AppBundle\Form;

use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;

class EsqueciMinhaSenhaType extends FormAbstract
{
    public function buildForm(\Symfony\Component\Form\FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('cpf', TextType::class, array('label' => 'CPF'))
            ->add('email', EmailType::class, array('label' => 'E-mail'));        
    }    
}
