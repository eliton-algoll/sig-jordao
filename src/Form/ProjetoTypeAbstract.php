<?php

namespace App\Form;

use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use App\Form\FormAbstract;

abstract class ProjetoTypeAbstract extends FormAbstract
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
                'attr' => array(
                    'maxlength' => 20
                )
            ))
            ->add('dsObservacao', TextareaType::class, array(
                'label' => 'Descrição do projeto',
                'required' => false,
                'attr' => array(
                    'maxlength' => 3950
                )
            ))
            ->add('stOrientadorServico', ChoiceType::class, array(
                'label' => 'Orientador de Serviço',
                'choices'  => [
                    'Sim' => 'S',
                    'Não' => 'N',
                ],
            ))
            ->add('qtBolsa', IntegerType::class, array(
                'label' => 'Quantidade total de bolsas',
                'attr' => array(
                    'min' => 1,
                    'max' => 99999
                )
            ))
            ->add('qtGrupos', ChoiceType::class, array(
                'label' => 'Quantidade total de grupos',
                'choices' => array(
                    '2' => '2',
                    '3' => '3',
                    '4' => '4',
                    '5' => '5',
                ),
            ))
            ->add('areasTematicasSaude', EntityType::class, array(
                'class' => 'App:TipoAreaTematica',
                'label' => 'Área Temática',
                'query_builder' => function ($repo) {
                    return $repo->createQueryBuilder('tat')
                                ->where("tat.stRegistroAtivo = 'S' AND tat.tpAreaFormacao = '1'")
                                ->orderBy('tat.tpAreaFormacao', 'ASC')
                                ->addOrderBy('tat.dsTipoAreaTematica', 'ASC');
                },
                'choice_label' => 'dsTipoAreaTematica',
                'choice_attr' => function ($tipoAreaTematica) {
                    return array(
                        'data-tp-area-tematica' => $tipoAreaTematica->getTpAreaTematica(),
                        'data-tp-area-sub'      => $tipoAreaTematica->getTpAreaFormacao()
                    );
                },
                'multiple' => true,
                'expanded' => true,
                'required' => true
            ))
            ->add('areasTematicasCienciasHumanas', EntityType::class, array(
                'class' => 'App:TipoAreaTematica',
                'label' => 'Área Temática',
                'query_builder' => function ($repo) {
                    return $repo->createQueryBuilder('tat')
                                ->where("tat.stRegistroAtivo = 'S' AND tat.tpAreaFormacao = '2'")
                                ->orderBy('tat.tpAreaFormacao', 'ASC')
                                ->addOrderBy('tat.dsTipoAreaTematica', 'ASC');
                },
                'choice_label' => 'dsTipoAreaTematica',
                'choice_attr' => function ($tipoAreaTematica) {
                    return array(
                        'data-tp-area-tematica' => $tipoAreaTematica->getTpAreaTematica(),
                        'data-tp-area-sub'      => $tipoAreaTematica->getTpAreaFormacao()
                    );
                },
                'multiple' => true,
                'expanded' => true,
                'required' => true
            ))
            ->add('areasTematicasCienciasSociais', EntityType::class, array(
                'class' => 'App:TipoAreaTematica',
                'label' => 'Área Temática',
                'query_builder' => function ($repo) {
                    return $repo->createQueryBuilder('tat')
                                ->where("tat.stRegistroAtivo = 'S' AND tat.tpAreaFormacao = '3'")
                                ->orderBy('tat.tpAreaFormacao', 'ASC')
                                ->addOrderBy('tat.dsTipoAreaTematica', 'ASC');
                },
                'choice_label' => 'dsTipoAreaTematica',
                'choice_attr' => function ($tipoAreaTematica) {
                    return array(
                        'data-tp-area-tematica' => $tipoAreaTematica->getTpAreaTematica(),
                        'data-tp-area-sub'      => $tipoAreaTematica->getTpAreaFormacao()
                    );
                },
                'multiple' => true,
                'expanded' => true,
                'required' => true
            ))
//            ->add('areasTematicas', EntityType::class, array(
//                'class' => 'App:TipoAreaTematica',
//                'label' => 'Área Temática',
//                'query_builder' => function ($repo) {
//                    return $repo->createQueryBuilder('tat')
//                        ->where("tat.stRegistroAtivo = 'S'")
//                        ->orderBy('tat.tpAreaFormacao', 'ASC')
//                        ->addOrderBy('tat.dsTipoAreaTematica', 'ASC');
//                },
//                'choice_label' => 'dsTipoAreaTematica',
//                'choice_attr' => function ($tipoAreaTematica) {
//                    return array(
//                        'data-tp-area-tematica' => $tipoAreaTematica->getTpAreaTematica()
//                    );
//                },
//                'multiple' => true,
//                'expanded' => true,
//                'required' => true
//            ))
            ->add('noDocumentoProjeto', FileType::class, array(
                'label' => 'Anexar documento',                
            ))
        ;
    }
}
