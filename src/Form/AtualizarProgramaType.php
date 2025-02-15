<?php

namespace App\Form;

use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\FormBuilderInterface;
use App\Form\FormAbstract;
use App\Entity\Programa;

class AtualizarProgramaType extends FormAbstract
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('coSeqPrograma', HiddenType::class)
            ->add('dsPrograma', TextType::class, array(
                'label' => 'Nome do programa',
                'attr' => array(
                    'maxlength' => 30
                )                
            ))
            ->add('tpPrograma', ChoiceType::class, [
                'label' => 'Tipo do Programa',
                'choices' => Programa::getTpProgramas(),
                'attr' => [
                    'disabled' => true,
                ]
            ])
            ->add('tpAreaTematica', ChoiceType::class, array(
                'label' => 'Tipo de área de atuação',
                'choices' => array(
                    'Cursos' => 1,
                    'Áreas Temáticas' => '2'
                )
            ))                
            ->add('nuPublicacao', IntegerType::class, array(
                'required' => false,
                'label' => 'Número da publicação',
                'attr' => array(
                    'min' => 1,
                    'max' => 999
                )                
            ))
            ->add('dtPublicacao', TextType::class, array(
                'required' => false,
                'label' => 'Data da publicação'
            ))
            ->add('dtInicioVigencia', TextType::class, array(
                'required' => false,
                'label' => 'Data de início de vigência'
            ))
            ->add('dtFimVigencia', TextType::class, array(
                'required' => false,
                'label' => 'Data de término da vigência'
            ))
            ->add('dsPublicacao', TextareaType::class, array(
                'attr' => array('maxlength' => 1950),
                'required' => false,
                'label' => 'Descrição da publicação'
            ))
            ->add('tpPublicacao', ChoiceType::class, array(
                'required' => false,
                'label' => 'Tipo da publicação',
                'choices' => array(
                    'Edital' => 'E',
                    'Portaria' => 'P',
                    'Memorando' => 'M'
                )
            ))
            ->add('qtdMinimaBolsistasGrupo', IntegerType::class, array(
                'label' => 'Quantidade mínima de bolsistas em cada grupo',
                'required' => false,
                'attr' => array(
                    'min' => 1,
                    'max' => 99999
                )                
            ))
            ->add('qtdMaximaBolsistasGrupo', IntegerType::class, array(
                'label' => 'Quantidade máxima de bolsistas em cada grupo',
                'required' => false,
                'attr' => array(
                    'min' => 1,
                    'max' => 99999
                )                
            ))
        ;                
        
        $builder->get('dtPublicacao')->addModelTransformer($this->getDateTimeTransformer());
        $builder->get('dtInicioVigencia')->addModelTransformer($this->getDateTimeTransformer());
        $builder->get('dtFimVigencia')->addModelTransformer($this->getDateTimeTransformer());
    }
    
    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'App\CommandBus\AtualizarProgramaCommand'
        ));
    }
}