<?php

namespace AppBundle\Form;

use AppBundle\Entity\FolhaPagamento;
use AppBundle\Entity\Publicacao;
use AppBundle\Entity\SituacaoFolha;
use AppBundle\Facade\YearMonthFacade;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Context\ExecutionContextInterface;

final class FiltroRelatorioFolhaPagamentoType extends AbstractType
{
    private $formData = array();

    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     * @throws \Exception
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $this->formData = $options['filtro_relatorio_folha_pagamento'];
        $publicacao = $this->formData['publicacao'];
        $ano = $this->formData['ano'];
        $mes = $this->formData['mes'];
        $tpFolha = $this->formData['tpFolhaPagamento'];

        $builder->add('publicacao', EntityType::class, [
            'label' => 'Programa',
            'class' => 'AppBundle:Publicacao',
            'query_builder' => function (EntityRepository $repo) {
                return $repo->createQueryBuilder('p')
                    ->where('p.stRegistroAtivo = :stAtivo')
                    ->setParameter('stAtivo', 'S');
            },
            'choice_label' => function (Publicacao $publicacao) {
                return $publicacao->getDescricaoCompleta();
            },
            'placeholder' => 'Selecione',
            'required' => true,
            'constraints' => [
                new Assert\NotBlank(),
            ],
        ])->add('nuSei', TextType::class, [
            'label' => 'Nº SEI',
            'attr' => [
                'class' => 'nuSipar',
            ],
            'required' => false,
        ])->add('tpFolhaPagamento', ChoiceType::class, [
            'label' => 'Tipo de pagamento',
            'choices' => FolhaPagamento::getTiposFolha(),
            'required' => true,
            'constraints' => [
                new Assert\NotBlank(),
            ],
        ])->add('ano', ChoiceType::class, [
            'label' => 'Ano',
            'choices' => YearMonthFacade::getSnapshotYears(2016),
            'required' => true,
            'placeholder' => 'Selecione',
            'constraints' => [
                new Assert\NotBlank(),
            ],
        ])->add('mes', ChoiceType::class, [
            'label' => 'Mês',
            'choices' => YearMonthFacade::getSnapshotMonths(),
            'required' => true,
            'placeholder' => 'Selecione',
            'constraints' => [
                new Assert\NotBlank(),
            ],
        ])->add('folhaPagamento', EntityType::class, [
            'label' => 'Data Folha Suplementar',
            'class' => FolhaPagamento::class,
            'query_builder' => function (EntityRepository $repository) use (
                $publicacao,
                $tpFolha,
                $ano,
                $mes
            ) {
                return $repository->createQueryBuilder('fp')
                    ->where('fp.publicacao = :publicacao')
                    ->andWhere('fp.nuAno = :nuAno')
                    ->andWhere('fp.nuMes = :nuMes')
                    ->andWhere('fp.tpFolhaPagamento = :tpFolha')
                    ->andWhere('fp.situacao IN(:situacao)')
                    ->setParameters([
                        'publicacao' => $publicacao,
                        'nuAno' => $ano,
                        'nuMes' => str_pad($mes, 2, '0', STR_PAD_LEFT),
                        'tpFolha' => $tpFolha,
                        'situacao' => [
                            SituacaoFolha::HOMOLOGADA,
                            SituacaoFolha::ENVIADA_FUNDO,
                            SituacaoFolha::ORDEM_BANCARIA_EMITIDA,
                        ],
                    ])->orderBy('fp.dtInclusao', 'desc');
            },
            'choice_label' => function (FolhaPagamento $folhaPagamento) {
                return $folhaPagamento->getDtInclusao()->format('d/m/Y');
            },
            'placeholder' => 'Selecione',
            'constraints' => [
                new Assert\Callback(['callback' => [$this, 'validateFolhaPagamento']]),
            ],
        ])->add('tpOutput', ChoiceType::class, [
            'label' => 'Tipo de Relatório',
            'choices' => [
                'Excel' => 'xls',
                'PDF' => 'pdf',
            ],
            'constraints' => [
                new Assert\NotBlank(),
            ],
        ]);
    }

    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefault('filtro_relatorio_folha_pagamento', [
            'publicacao' => null,
            'tpFolhaPagamento' => null,
            'ano' => null,
            'mes' => null,
        ]);
    }

    /**
     * @param FolhaPagamento|null $folhaPagamento
     * @param ExecutionContextInterface $context
     */
    public function validateFolhaPagamento(
        FolhaPagamento $folhaPagamento = null,
        ExecutionContextInterface $context
    ) {
        if ($this->formData['tpFolhaPagamento'] === 'S' && $folhaPagamento === null) {
            $context
                ->buildViolation('Este valor não deve ser vazio')
                ->atPath('folhaPagamento')
                ->addViolation();
        }
    }
}