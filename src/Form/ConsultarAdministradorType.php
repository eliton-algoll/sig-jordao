<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\NotBlank;

class ConsultarAdministradorType extends AbstractType
{

    /**
     *
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('nuCpf', TextType::class, [
                'label' => 'CPF',
                'attr' => array('class' => 'nuCpf'),
                'required' => false
            ])
            ->add('noNome', TextType::class, [
                'label' => 'Nome',
                'required' => false
            ])
            ->add('dsLogin', TextType::class, [
                'label' => 'Login',
                'required' => false
            ])
            ->add('stRegistroAtivo', ChoiceType::class, [
                'choices' => [
                    'Todos' => null,
                    'Ativo' => 'S',
                    'Inativo' => 'N',
                ],
                'label' => 'Status',
                'required' => false
            ])->setMethod('GET');
    }

}
