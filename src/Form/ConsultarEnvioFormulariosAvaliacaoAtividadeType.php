<?php

namespace App\Form;

use App\Entity\Perfil;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Context\ExecutionContextInterface;

final class ConsultarEnvioFormulariosAvaliacaoAtividadeType extends AbstractType
{
    /**
     * 
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('formularioAvaliacaoAtividade', EntityType::class, [
            'class' => 'App:FormularioAvaliacaoAtividade',
            'query_builder' => function (EntityRepository $repo) {
                return $repo->createQueryBuilder('faa')
                    ->where('faa.stRegistroAtivo = :stAtivo')
                    ->setParameter('stAtivo', 'S')
                    ->orderBy('faa.noFormulario', 'ASC');
            },
            'placeholder' => 'Selecione',
            'choice_label' => 'noFormulario',
            'label' => 'Título do Formulário',
            'required' => false,
        ])->add('tpTramiteFormulario', ChoiceType::class, [
            'choices' => [
                'Envio' => 'E',
                'Retorno' => 'R',
            ],
            'expanded' => true,
            'label' => 'Tipo de Consulta',
            'data' => 'E',
            'constraints' => [
                new Assert\NotBlank(),
            ],
        ])->add('stFinalizado', ChoiceType::class, [
            'choices' => [
                'Com Pendência' => 'N',
                'Finalizado' => 'S'
            ],
            'placeholder' => 'Selecione',
            'label' => 'Situação',
            'required' => true,
            'constraints' => [
                new Assert\Callback([$this, 'validateSituacao']),
            ],
        ])->add('situacaoTramiteFormulario', EntityType::class, [
            'class' => 'App:SituacaoTramiteFormulario',
            'query_builder' => function (EntityRepository $repo) {
                return $repo->createQueryBuilder('stf')                    
                    ->andWhere('stf.stRegistroAtivo = :stAtivo')
                    ->orderBy('stf.dsSituacaoTramiteFormulario', 'ASC')
                    ->setParameter('stAtivo','S');
            },
            'placeholder' => 'Selecione',
            'choice_label' => 'noSituacaoTramiteFormulario',
            'label' => 'Situação',
            'required' => true,            
        ])->add('perfil', EntityType::class, [
            'class' => 'App:Perfil',
            'query_builder' => function (EntityRepository $repo) {
                return $repo->createQueryBuilder('p')
                    ->where('p.coSeqPerfil <> :perfilAdministrador')
                    ->setParameter('perfilAdministrador', Perfil::PERFIL_ADMINISTRADOR)
                    ->orderBy('p.dsPerfil', 'ASC');
            },
            'placeholder' => 'Selecione',
            'choice_label' => 'dsPerfil',
            'label' => 'Perfil Responsável',
            'required' => false,
        ])->add('publicacao', EntityType::class, [
            'class' => 'App:Publicacao',
            'query_builder' => function (EntityRepository $repo) {
                return $repo->createQueryBuilder('p')
                    ->innerJoin('p.programa', 'prg')
                    ->where('p.stRegistroAtivo = :stAtivo')
                    ->andWhere('prg.stRegistroAtivo = :stAtivo')
                    ->setParameter('stAtivo', 'S')
                    ->orderBy('prg.dsPrograma', 'ASC')
                    ->addOrderBy('p.dtPublicacao', 'DESC');
            },
            'placeholder' => 'Selecione',
            'choice_label' => function ($publicacao) {
                return $publicacao->getCoSeqPublicacao() . ' - ' . $publicacao->getDescricaoCompleta();
            },            
            'label' => 'Programa/Publicação',
            'required' => false,                    
        ])->add('nuSipar', TextType::class, [
            'label' => 'Número SEI',
            'attr' => [
                'class' => 'nuSipar',
            ],
            'required' => false,
        ])->add('noPessoa', TextType::class, [
            'label' => 'Nome do Participante',
            'attr' => [
                'maxlength' => 100,
            ],
            'required' => false,
        ])->add('nuCpf', TextType::class, [
            'label' => 'CPF',
            'attr' => [
                'class' => 'nuCpf',
            ],
            'required' => false,
        ])->setMethod('GET');
    }
    
    /**
     * 
     * @param mixed $value
     * @param ExecutionContextInterface $context
     */
    public function validateSituacao($value, ExecutionContextInterface $context)
    {
        $form = $context->getRoot();
        $data = $form->getData();
        
        if (!$data['formularioAvaliacaoAtividade']) {
            $context->buildViolation('Este valor não deve ser vazio.')
                ->atPath('stFinalizado')
                ->addViolation();
        }
    }
}
