<?php

namespace AppBundle\Form;

use Symfony\Component\Form\AbstractType;
use \Symfony\Component\Form\FormBuilderInterface;
use \Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use AppBundle\Entity\Telefone;
use \Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\OptionsResolver\OptionsResolver;

class TelefoneType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('tpTelefone', ChoiceType::class, array(
                'label' => 'Tipo',
                'choices' => array_flip(Telefone::$arrTpTelefone)
            ))
            ->add('nuDdd', TextType::class, array(
                'label' => 'DDD',
                'attr' => array('class' => 'nuDdd')
            ))
            ->add('nuTelefone', TextType::class, array(
                'label' => 'Número',
                'attr' => array('class' => 'nuTelefone')
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