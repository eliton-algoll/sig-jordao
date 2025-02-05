<?php

namespace AppBundle\Form;

use AppBundle\Entity\Projeto;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Doctrine\ORM\EntityRepository;
use AppBundle\Entity\SituacaoFolha;
use AppBundle\Entity\FolhaPagamento;
use AppBundle\CommandBus\CadastarFolhaSuplementarCommand;

final class CadastarFolhaSuplementarType extends AbstractType
{
    /**
     * 
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $data = current($options['data_submited']);
        $publicacao = isset($data['publicacao']) ? $data['publicacao'] : $options['publicacao'];
        
        $builder->add('publicacao', EntityType::class, [
            'label' => 'Programa',
            'class' => 'AppBundle:Publicacao',
            'query_builder' => function (EntityRepository $repo) {
                return $repo->createQueryBuilder('p')
                    ->where('p.stRegistroAtivo = :stAtivo')
                    ->setParameter('stAtivo', 'S');
            },
            'choice_label' => function ($publicacao) {
                return $publicacao->getDescricaoCompleta();
            },
            'placeholder' => 'Selecione',
            'required' => true,
            'disabled' => $options['edit'],
            'attr' => [
                'title' => 'Selecione o programa/publicação para geração de Folha de Pagamento Complementar.',
            ],
        ])->add('folhaPagamento', EntityType::class, [
            'label' => 'Mês/Ano de Referência',
            'class' => FolhaPagamento::class,
            'query_builder' => function (EntityRepository $repo) use ($publicacao) {
                return $repo->createQueryBuilder('fp')
                    ->where('fp.stRegistroAtivo = :stAtivo')
                    ->andWhere('fp.publicacao = :publicacao')
                    ->andWhere('fp.tpFolhaPagamento = :tpFolha')
                    ->andWhere('fp.situacao IN(:situacao)')
                    ->orderBy('fp.nuAno', 'DESC')
                    ->addOrderBy('fp.nuMes', 'DESC')
                    ->setParameter('stAtivo', 'S')
                    ->setParameter('publicacao', $publicacao)
                    ->setParameter('tpFolha', FolhaPagamento::MENSAL)
                    ->setParameter('situacao', [ SituacaoFolha::ENVIADA_FUNDO, SituacaoFolha::ORDEM_BANCARIA_EMITIDA, SituacaoFolha::HOMOLOGADA ]);
            },
            'choice_label' => function ($folhaPagamento) {
                return ucfirst($folhaPagamento->getReferenciaExtenso());
            },
            'placeholder' => 'Selecione',
            'required' => true,
            'disabled' => $options['edit'],
            'attr' => [
                'title' => 'Selecione o mês/ano de referência da folha de pagamento MENSAL para a qual deseja gerar uma folha complementar.',
            ],
        ])->add('projeto', TextType::class, [
            'label' => 'Nº SEI',
            'attr' => [
                'class' => 'nuSipar',
            ],
            'mapped' => false,
            'required' => false,
        ])->add('cpf', TextType::class, [
            'label' => 'CPF',
            'attr' => [
                'class' => 'nuCpf',
            ],
            'mapped' => false,
            'required' => false,
        ])->add('participantes', HiddenType::class, [
            'error_bubbling' => false,
        ])
        ->add('dsJustificativa', TextareaType::class, [
            'label' => 'Justificativa',
            'attr' => [
                'maxlength' => 4000
            ],
            'required' => false,
        ])
        ->add('salvaEfecha', HiddenType::class, [
              'data' => 'N',
        ]);
    }
    
    /**
     * 
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => CadastarFolhaSuplementarCommand::class,
            'data_submited' => null,
            'publicacao' => null,
            'edit' => false,
        ]);
    }
}
