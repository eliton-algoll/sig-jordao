<?php

namespace App\Form;

use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;

class PesquisarProjetoType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('nuSipar', TextType::class, array(
                'label' => 'Nº SEI',
                'required' => false,
                'attr' => array(
                    'maxlength' => 20
                )
            ))
            ->add('publicacao', EntityType::class, array(
                'label' => 'Publicação',
                'class' => 'App:Publicacao',
                'required' => false,
                'choice_label' => function ($publicacao) {
                    return $publicacao->getDescricaoCompleta();
                }
            ))
            ->add('instituicaoEnsino', TextType::class, array(
                'label' => 'Instituição de Ensino Superior',
                'required' => false
            ))
            ->add('secretariaSaude', TextType::class, array(
                'label' => 'Secretaria de Saúde',
                'required' => false
            ))
        ;
    }

    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(
            array(
                'csrf_protection' => false
            )
        );
    }
}