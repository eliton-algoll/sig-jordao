<?php

namespace AppBundle\Form;

use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;

class SelecionarPerfilType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options) 
    {
        $usuario = $options['usuario'];
        
        $builder->add('coPessoaPerfil', EntityType::class, array(
            'class' => 'AppBundle:PessoaPerfil',
            'label' => 'Perfil',
            'placeholder' => '',
            'choices' => $usuario->getPessoaFisica()->getPessoasPerfisAtivos(),
            'choice_label' => function ($pessoaPerfil) {
                return $pessoaPerfil->getPerfil()->getDsPerfil();
            },
            'choice_attr' => function ($pessoaPerfil) {
                return array('data-admin' => (int) $pessoaPerfil->getPerfil()->isAdministrador());
            },
            'choice_value' => 'coSeqPessoaPerfil',
            'required' => true
        ));
    }
    
    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'usuario' => null
        ));
    }
}