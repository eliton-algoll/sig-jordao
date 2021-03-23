<?php

namespace AppBundle\Form;

use AppBundle\CommandBus\SalvarEmissaoCertificadoCommand;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

final class DadosEmissaoCertificadoType extends AbstractType
{
    /**
     * 
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('qtCargaHoraria', TextType::class, [
            'label' => 'Informe a carga horária semanal de participação no programa',
            'attr' => [
                'maxlength' => 5,
            ],            
        ])->add('uf', EntityType::class, [
            'class' => 'AppBundle:Uf',
            'query_builder' => function(EntityRepository $repo) {
                return $repo->createQueryBuilder('u')
                    ->where('u.stRegistroAtivo = :stAtivo')
                    ->setParameter('stAtivo', 'S')
                    ->orderBy('u.sgUf');
            },
            'choice_label' => 'sgUf',
            'label' => 'UF',
            'placeholder' => 'Selecione',            
        ])->add('municipio', EntityType::class, [
            'class' => 'AppBundle:Municipio',
            'query_builder' => function(EntityRepository $repo) use ($options) {
                return $repo->createQueryBuilder('m')
                    ->where('m.stRegistroAtivo = :stAtivo')
                    ->andWhere('m.coUfIbge = :coUf')
                    ->orderBy('m.noMunicipio')
                    ->setParameter('stAtivo', 'S')
                    ->setParameter('coUf', $options['dados_emissao_certificado']['uf']);
            },
            'choice_label' => 'noMunicipio',
            'label' => 'Município',
            'placeholder' => 'Selecione',            
        ])->add('stFinalizacaoContrato', ChoiceType::class, [
            'label' => 'É finalização de contrato?',
            'choices' => [
                'Sim' => true,
                'Não' => false,
            ], 
        ])->add('participantes', HiddenType::class);
    }
    
    /**
     * 
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'dados_emissao_certificado' => [
                'uf' => null,
            ],
            'data_class' => SalvarEmissaoCertificadoCommand::class,
        ]);
    }
}
