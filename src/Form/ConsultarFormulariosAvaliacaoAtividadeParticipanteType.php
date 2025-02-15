<?php

namespace App\Form;

use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints as Assert;

final class ConsultarFormulariosAvaliacaoAtividadeParticipanteType extends AbstractType
{
    /**
     * 
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $projetoPessoaId = ($options['projetoPessoa']) ? $options['projetoPessoa']->getCoSeqProjetoPessoa() : null;
        
        $builder->add('formularioAvaliacaoAtividade', EntityType::class, [
            'class' => 'App:FormularioAvaliacaoAtividade',
            'query_builder' => function (EntityRepository $repo) use ($projetoPessoaId) {
                return $repo->createQueryBuilder('faa')
                    ->innerJoin('faa.envioFormularioAvaliacaoAtividade', 'efa')
                    ->innerJoin('efa.tramitacaoFormulario', 'tf')
                    ->where('faa.stRegistroAtivo = :stAtivo')
                    ->andWhere('tf.stRegistroAtivo = :stAtivo')
                    ->andWhere('tf.projetoPessoa = :projetoPessoa')
                    ->setParameters([
                        'stAtivo' => 'S',
                        'projetoPessoa' => $projetoPessoaId,
                    ]);
            },
            'choice_label' => 'noFormulario',
            'placeholder' => 'Selecione',
            'label' => 'Título do Formulário',
            'required' => true,
            'constraints' => [
                new Assert\NotBlank(),
            ],
        ])->add('situacaoTramiteFormulario', EntityType::class, [
            'class' => 'App:SituacaoTramiteFormulario',
            'query_builder' => function (EntityRepository $repo) {
                return $repo->createQueryBuilder('stf')
                    ->where('stf.stRegistroAtivo = :stAtivo')
                    ->setParameter('stAtivo', 'S')
                    ->orderBy('stf.noSituacaoTramiteFormulario', 'ASC');
            },
            'choice_label' => 'noSituacaoTramiteFormulario',
            'placeholder' => 'Selecione',
            'label' => 'Situação',
            'required' => true,
            'constraints' => [
                new Assert\NotBlank(),
            ],
        ])->add('anoEnvio', TextType::class, [
            'label' => 'Ano de Envio',
            'attr' => [
                'maxlength' => 4,
            ],
            'required' => false,
            'constraints' => [
                new Assert\Regex(['pattern' => '/[0-9]{4}/x']),
            ]
        ])->setMethod('GET');
    }  
    
    
    /**
     * 
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefault('projetoPessoa', null);
    }
}
