<?php

namespace AppBundle\Form;

use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use AppBundle\Repository\UfRepository;

class PesquisarCampusType extends FormAbstract
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('uf', EntityType::class, array(
                'label' => 'UF',
                'class' => 'AppBundle:Uf',
                'required' => false,
                'choice_label' => 'sgUf',
                'query_builder' => function (UfRepository $repository) {
                    $qb = $repository->createQueryBuilder('uf');
                    $qb
                        ->where("uf.stRegistroAtivo = 'S'")
                        ->orderBy('uf.sgUf', 'asc');
                    return $qb;
                }
                
            ))
            ->add('municipio', ChoiceType::class, array(
                'label' => 'Município',
                'required' => false,
                'choices' => array()
            ))
            ->add('instituicao', ChoiceType::class, array(
                'label' => 'Instituição',
                'required' => false,
                'choices' => array()
            ))
            ->add('campus', ChoiceType::class, array(
                'label' => 'Campus',
                'required' => false,
                'choices' => array()
            ));
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