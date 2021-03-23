<?php

namespace AppBundle\Form;

use AppBundle\Report\GerencialPagamentoFilter;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

final class FiltroRelatorioGerencialPagamentoType extends AbstractType
{
    private $customizacaoData = [];
    
    public function __construct()
    {
        $this->customizacaoData = GerencialPagamentoFilter::getCustomizacaoData();
    }
    
    /**
     * 
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $form = $options['filtro_relatorio_gerencial_pagamento'];
        $toCustomizacao = (isset($form['to_customizacao'])) ? $form['to_customizacao'] : [];
        
        $builder->add('publicacao', EntityType::class, [
            'class' => 'AppBundle:Publicacao',
            'query_builder' => function (EntityRepository $repo) {
                return $repo->createQueryBuilder('p')
                    ->innerJoin('p.programa', 'prg')
                    ->orderBy('prg.dsPrograma', 'ASC')
                    ->addOrderBy('p.dtPublicacao', 'DESC');
            },
            'choice_label' => function ($publicacao) {
                return $publicacao->getDescricaoCompleta();
            },
            'label' => 'Programa / Publicação',
            'required' => false,
            'placeholder' => 'Selecione',
        ])->add('nuSipar', TextType::class, [
            'label' => 'Nº SEI',
            'required' => false,
            'attr' => [
                'class' => 'nuSipar',
            ],
        ])->add('nuMes', ChoiceType::class, [
            'choices' => [
                'Janeiro' => '01',
                'Fevereiro' => '02',
                'Março' => '03',
                'Abril' => '04',
                'Maio' => '05',
                'Junho' => '06',
                'Julho' => '07',
                'Agosto' => '08',
                'Setembro' => '09',
                'Outubro' => '10',
                'Novembro' => '11',
                'Dezembro' => '12',
            ],
            'label' => 'Mês',
            'required' => false,
            'placeholder' => 'Selecione',
        ])->add('nuAno', TextType::class, [
            'label' => 'Ano',
            'required' => false,
            'attr' => [
                'maxlength' => 4,
            ],
        ])->add('from_customizacao', ChoiceType::class, [
            'choices' => $this->buildFromCustomizacao($toCustomizacao),
            'multiple' => true,
            'label' => 'Customização',
            'required' => false,
            'attr' => [
                'class' => 'customizacao-report-field',
            ],
        ])->add('to_customizacao', ChoiceType::class, [
            'choices' => $this->buildToCustomizacao($toCustomizacao),
            'multiple' => true,
            'label' => ' ',
            'required' => false,
            'attr' => [
                'class' => 'customizacao-report-field',
            ],
        ]);
    }
    
    /**
     * 
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'filtro_relatorio_gerencial_pagamento' => null,
            'data_class' => GerencialPagamentoFilter::class,
        ]);
    }
    
    /**
     *
     * @param mixed $customizacaoTo
     * @return array
     */
    private function buildToCustomizacao($customizacaoTo = [])
    {
        $build = [];
        
        foreach ($customizacaoTo as $col) {
            if (in_array($col, $this->customizacaoData)) {
                $build[array_search($col, $this->customizacaoData)] = $col;
            }
        }        
        
        return $build;
    }
    
    /**
     *
     * @param mixed $customizacaoTo
     * @return array
     */
    private function buildFromCustomizacao($customizacaoTo = [])
    {
        $clean = [];
        
        foreach ($this->customizacaoData as $name => $data) {
            if (is_array($customizacaoTo) && in_array($data, $customizacaoTo)) {
                continue;
            }
            $clean[$name] = $data;
        }
        
        return $clean;
    }
}
