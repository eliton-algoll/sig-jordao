<?php

namespace App\Form;

use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;

class AtualizarProjetoType extends ProjetoTypeAbstract
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        parent::buildForm($builder, $options);
        
        $builder
            ->add('coSeqProjeto', HiddenType::class)
            ->add('publicacao', EntityType::class, array(
                'label' => 'Publicação',
                'class' => 'App:Publicacao',
                'required' => true,
                'choice_label' => function ($publicacao) {
                    return $publicacao->getDescricaoCompleta();
                },
                'choice_attr' => function ($publicacao) {
                    return array(
                        'data-tp-area-tematica' => $publicacao->getPrograma()->getTpAreaTematica()
                    );
                }
            ))
        ;
    }
    
    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'App\CommandBus\AtualizarProjetoCommand'
        ));
    }
}