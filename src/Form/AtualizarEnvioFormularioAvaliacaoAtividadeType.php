<?php

namespace App\Form;

use App\CommandBus\AtualizarEnvioFormularioAvaliacaoAtividadeCommand;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class AtualizarEnvioFormularioAvaliacaoAtividadeType extends AbstractType
{
    /**
     * 
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('formularioAvaliacaoAtividade', TextType::class, [
            'label' => 'Título do Formulário',
            'attr' => [
                'readonly' => true,
            ],            
        ])->add('dtInicioPeriodo', DateType::class, [            
            'widget' => 'single_text',
            'format' => 'dd/MM/yyyy',
            'label' => 'Período válido para retorno dos participantes',            
            'attr' => [
                'class' => 'dmY',
            ],
        ])->add('dtFimPeriodo', DateType::class, [            
            'widget' => 'single_text',
            'format' => 'dd/MM/yyyy',            
            'attr' => [
                'class' => 'margin-top-25 dmY',
            ],
        ]);
    }

    /**
     * 
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefault('data_class', AtualizarEnvioFormularioAvaliacaoAtividadeCommand::class);
    }
}
