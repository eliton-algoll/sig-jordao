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

class CadastrarTipoAreaTematicaType extends AbstractType
{

    /**
     *
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('dsTipoAreaTematica', TextType::class, array(
                'label' => 'Curso de Formação',
                'required' => true
            ))
            ->add('tpAreaFormacao', ChoiceType::class, [
                'choices' => [
                    'Saúde' => 1,
                    'Ciências Humanas' => 2,
                    'Ciências Sociais Aplicadas' => 3
                ],
                'label' => 'Área formação',
                'required' => true
            ]);
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
