<?php

namespace App\Form;

use App\Repository\UfRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\NotBlank;

class CadastrarCampusInstituicaoType extends AbstractType
{

    /**
     *
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('uf', EntityType::class, array(
                'label' => 'UF',
                'class' => 'App:Uf',
                'required' => true,
                'choice_label' => 'sgUf',
                'placeholder' => '',
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
                'required' => true,
                'choices' => array()
            ))
            ->add('instituicao', ChoiceType::class, array(
                'label' => 'Instituição',
                'required' => true,
                'choices' => array()
            ))
            ->add('noCampus', TextType::class, array(
                'label' => 'Campus',
                'required' => true
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
