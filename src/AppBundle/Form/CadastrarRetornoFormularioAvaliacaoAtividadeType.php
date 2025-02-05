<?php

namespace AppBundle\Form;

use AppBundle\CommandBus\CadastrarRetornoFormularioAvaliacaoAtividadeCommand;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

final class CadastrarRetornoFormularioAvaliacaoAtividadeType extends AbstractType
{
    /**
     * 
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('noFormulario', TextType::class, [
            'label' => 'Título do Formulário',
            'attr' => [
                'readonly' => true,
            ],
        ])->add('nuProtocoloFormSUS', TextType::class, [
            'label' => 'Protocolo FormSUS',
            'attr' => [
                'maxlength' => 25,
            ],
        ])->add('fileFormulario', FileType::class, [
            'label' => 'Anexar formulário preenchido',
        ]);
    }
    
    /**
     * 
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefault('data_class', CadastrarRetornoFormularioAvaliacaoAtividadeCommand::class);
    }
}
