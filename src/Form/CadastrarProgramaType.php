<?php

namespace App\Form;

use App\Entity\Programa;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use App\Form\FormAbstract;

class CadastrarProgramaType extends FormAbstract
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('dsPrograma', TextType::class, array(
                'label' => 'Nome do programa',
                'attr' => array(
                    'maxlength' => 30
                )
            ))
            ->add('tpPrograma', ChoiceType::class, [
                'label' => 'Tipo do Programa',
                'choices' => Programa::getTpProgramas()
            ])
            ->add('tpAreaTematica', ChoiceType::class, array(
                'label' => 'Tipo de área de atuação',
                'choices' => array(
                    'Cursos' => 1,
                    'Áreas Temáticas' => '2'
                )
            ))                
            ->add('nuPublicacao', IntegerType::class, array(
                'label' => 'Número da publicação',
                'attr' => array(
                    'min' => 1,
                    'max' => 999
                )
            ))
            ->add('dtPublicacao', TextType::class, array(
                'label' => 'Data da publicação'
            ))
            ->add('dtInicioVigencia', TextType::class, array(
                'label' => 'Data de início de vigência'
            ))
            ->add('dtFimVigencia', TextType::class, array(
                'label' => 'Data de término da vigência'
            ))
            ->add('dsPublicacao', TextareaType::class, array(
                'label' => 'Descrição da publicação',
                'required' => false,
                'attr' => array(
                    'maxlength' => 1950
                )
            ))
            ->add('tpPublicacao', ChoiceType::class, array(
                'label' => 'Tipo da publicação',
                'choices' => array(
                    'Edital' => 'E',
                    'Portaria' => 'P',
                    'Memorando' => 'M'
                )
            ))
            ->add('qtdMinimaBolsistasGrupo', IntegerType::class, array(
                'label' => 'Quantidade mínima de bolsistas em cada grupo',
                'attr' => array(
                    'min' => 1,
                    'max' => 99999
                )
            ))
            ->add('qtdMaximaBolsistasGrupo', IntegerType::class, array(
                'label' => 'Quantidade máxima de bolsistas em cada grupo',
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
            'data_class' => 'App\CommandBus\CadastrarProgramaCommand'
        ));
    }
}