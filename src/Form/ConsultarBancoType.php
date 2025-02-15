<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\NotBlank;

class ConsultarBancoType extends AbstractType
{

    /**
     *
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('coBanco', TextType::class, [
                'label' => 'Código',
                'required' => false
            ])
            ->add('noBanco', TextType::class, [
                'label' => 'Descrição',
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
