<?php

namespace App\Form;

use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use App\Repository\AreaTematicaRepository;
use App\Form\FormAbstract;

class CadastrarGrupoAtuacaoType extends FormAbstract
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $qbAreasTematicas = function (AreaTematicaRepository $repository) use ($options) {
            $qb = $repository->createQueryBuilder('at');
            $qb
                ->join('at.tipoAreaTematica', 'tat')
                ->andWhere('at.projeto = :projeto')
                ->setParameter('projeto', $options['coProjeto'])
                ->andWhere("at.stRegistroAtivo = 'S'")
                ->andWhere("tat.stRegistroAtivo = 'S'")
                ->orderBy('tat.dsTipoAreaTematica', 'asc');

            return $qb;
        };
        
        $builder
            ->add('areasTematicas', EntityType::class, array(
                'class' => 'App:AreaTematica',
                'label' => 'Áreas de atuação',
                'choice_label' => function ($areaTematica) {
                    return $areaTematica->getTipoAreaTematica()->getDsTipoAreaTematica();
                },
                'query_builder' => $qbAreasTematicas,
                'multiple' => false,
                'expanded' => true,
                'required' => true
            ))
        ;
    }
    
    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'App\CommandBus\CadastrarGrupoAtuacaoCommand',
            'coProjeto' => null
        ));
    }
}