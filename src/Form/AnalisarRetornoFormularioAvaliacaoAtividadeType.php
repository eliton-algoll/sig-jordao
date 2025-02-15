<?php

namespace App\Form;

use App\CommandBus\AnalisarRetornoFormularioAvaliacaoAtividadeCommand;
use App\Entity\SituacaoTramiteFormulario;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

final class AnalisarRetornoFormularioAvaliacaoAtividadeType extends AbstractType
{
    /**
     * 
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('noFormulario', TextType::class, [
            'label' => 'Formulário',
            'attr' => [
                'readonly' => true,
            ],
        ])->add('participante', TextType::class, [
            'label' => 'Participante',
            'attr' => [
                'readonly' => true,
            ],
        ])->add('situacaoTramiteFormulario', EntityType::class, [
            'class' => 'App:SituacaoTramiteFormulario',
            'query_builder' => function (EntityRepository $repo) {
                return $repo->createQueryBuilder('stf')
                    ->where('stf.coSeqSituacaoTramiteForm IN (:situacoes)')
                    ->setParameter('situacoes', SituacaoTramiteFormulario::getSituacoesRetorno());
            },
            'choice_label' => function ($situacaoTramiteFormulario) {
                return $situacaoTramiteFormulario->getDescricaoRetorno();
            },
            'placeholder' => 'Selecione',
            'label' => 'Situação',
        ])->add('dsJustificativa', TextareaType::class, [
            'label' => 'Justificativa',
            'attr' => [
                'maxlength' => 2000,
            ],
            'required' => false,
        ]);
    }
    
    /**
     * 
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefault('data_class', AnalisarRetornoFormularioAvaliacaoAtividadeCommand::class);
    }
}
