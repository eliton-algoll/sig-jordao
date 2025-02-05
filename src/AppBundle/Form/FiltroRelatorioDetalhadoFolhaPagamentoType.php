<?php

namespace AppBundle\Form;

use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\AbstractType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Doctrine\ORM\EntityRepository;
use AppBundle\Entity\SituacaoFolha;

final class FiltroRelatorioDetalhadoFolhaPagamentoType extends AbstractType
{
    /**
     * 
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $data = $options['data'];
        $publicacao = ($data) ? $data['filtro_relatorio_detalhado_folha_pagamento']['publicacao'] : null;        
        $situacao = ($data) ? $data['filtro_relatorio_detalhado_folha_pagamento']['situacao'] : null;
        
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
            'constraints' => [
                new Assert\NotBlank(),
            ],            
        ])->add('nuSei', TextType::class, [
            'label' => 'SEI',
            'attr' => [
                'class' => 'nuSipar',
            ],
            'required' => false,
        ])->add('coordenador', EntityType::class, [
            'label' => 'Coordenador',
            'class' => 'AppBundle:PessoaPerfil',
            'query_builder' => function (EntityRepository $repo) use ($publicacao) {
                return $repo->createQueryBuilder('pp')
                    ->innerJoin('pp.projetosPessoas', 'projp')
                    ->innerJoin('projp.projeto', 'proj')                    
                    ->innerJoin('pp.pessoaFisica', 'pf')
                    ->innerJoin('pf.pessoa', 'p')
                    ->where('proj.publicacao = :publicacao')
                    ->orderBy('p.noPessoa')
                    ->setParameter('publicacao', $publicacao);
            },
            'choice_label' => function ($pessoaPerfil) {
                return $pessoaPerfil->getPessoaFisica()->getPessoa()->getNoPessoa();
            },
            'placeholder' => 'Selecione',
            'required' => false,
        ])->add('situacao', EntityType::class, [
            'label' => 'Situação',
            'class' => 'AppBundle:SituacaoFolha',
            'query_builder' => function (EntityRepository $repo) {
                return $repo->createQueryBuilder('sf')
                    ->where('sf.stRegistroAtivo = :stAtivo')
                    ->andWhere('sf.coSeqSituacaoFolha IN(:situacao)')
                    ->setParameter('stAtivo', 'S')
                    ->setParameter('situacao', [                        
                        SituacaoFolha::HOMOLOGADA,
                        SituacaoFolha::ENVIADA_FUNDO,
                        SituacaoFolha::ORDEM_BANCARIA_EMITIDA,
                    ]);
            },
            'choice_label' => 'dsSituacaoFolha',
            'placeholder' => 'Selecione',
            'required' => true,
            'constraints' => [
                new Assert\NotBlank(),
            ],
        ])->add('folhaPagamento', EntityType::class, [
            'label' => 'Mês/Ano Referência',
            'class' => 'AppBundle:FolhaPagamento',
            'query_builder' => function (EntityRepository $repo) use ($publicacao, $situacao) {
                $qb = $repo->createQueryBuilder('fp')
                    ->where('fp.publicacao = :publicacao')
                    ->andWhere('fp.stRegistroAtivo = :stAtivo')
                    ->andWhere('fp.situacao = :situacao')
                    ->orderBy('fp.nuAno', 'DESC')
                    ->addOrderBy('fp.nuMes', 'DESC')
                    ->addOrderBy('fp.tpFolhaPagamento', 'ASC')
                    ->setParameter('publicacao', $publicacao)
                    ->setParameter('situacao', $situacao)
                    ->setParameter('stAtivo', 'S');
                
                return $qb;
            },
            'choice_label' => function ($folhaPagamento) {
                return ucfirst($folhaPagamento->getDescricaoCompletaFolha());
            },
            'placeholder' => 'Selecione',
            'required' => true,
            'constraints' => [
                new Assert\NotBlank(),
            ],
        ]);
    }
}
