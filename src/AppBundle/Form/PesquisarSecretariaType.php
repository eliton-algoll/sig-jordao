<?php

namespace AppBundle\Form;

use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use AppBundle\Repository\UfRepository;

class PesquisarSecretariaType extends FormAbstract
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('nuCnpj', TextType::class, array(
                'label' => 'CNPJ',
                'required' => false
            ))
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
            ->add('secretaria', ChoiceType::class, array(
                'label' => 'Secretaria de Saúde',
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