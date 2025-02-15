<?php

namespace App\Form;

use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use App\Entity\Publicacao;
use App\Entity\FolhaPagamento;

class PesquisarFolhaPagamentoType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('publicacao', EntityType::class, [
                'label' => 'Programa',
                'class' => 'App:Publicacao',
                'choice_label' => function (Publicacao $publicacao) {
                    return $publicacao->getDescricaoCompleta(true);
                },
                'placeholder' => ''
            ])
            ->add('situacao', EntityType::class, [
                'label' => 'Situação',
                'class' => 'App:SituacaoFolha',
                'choice_label' => 'dsSituacaoFolha',
                'placeholder' => ''
            ])
            ->add('tpFolha', ChoiceType::class, [
                'label' => 'Tipo de Folha',
                'choices' => FolhaPagamento::getTiposFolha(),
                'placeholder' => '',
            ])->setMethod('GET')
        ;
    }

    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'csrf_protection' => false
        ]);
    }
}
