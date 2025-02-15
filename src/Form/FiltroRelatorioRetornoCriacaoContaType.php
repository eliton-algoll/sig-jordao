<?php

namespace App\Form;

use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\AbstractType;
use App\Cpb\DicionarioCpb;

class FiltroRelatorioRetornoCriacaoContaType extends AbstractType
{
    const RELATORIO_COMPLETO = 1;
    const RELATORIO_ST_CADASTRO = 2;
    
    /**
     * 
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $retornoCriacaoConta = $options['retornoCriacaoConta'];
        $stCred = DicionarioCpb::getValues('SIT-CMD');
        $stCredito = [];
        
        array_walk($stCred, function ($value, $key) use(&$stCredito) {
            $stCredito[$value . ' - ' . $key] = $value;            
            unset($stCredito[$key]);
        });
        
        $builder->add('referencia', TextType::class, [
            'label' => 'Mês/Ano de retorno',
            'data' => $retornoCriacaoConta->getDtInclusao()->format('m/Y'),
            'attr' => [
                'readonly' => true,
            ],
        ])->add('arquivo', TextType::class, [
            'label' => 'Arquivo de Retorno',
            'data' => $retornoCriacaoConta->getNoArquivoOriginal(),
            'attr' => [
                'readonly' => true,
            ],
        ])->add('tpRelatorio', ChoiceType::class, [
            'label' => 'Tipo de Relatório',
            'choices' => $options['tpRelatorio'],
            'data' => 1,
            'constraints' => [
                new Assert\NotBlank([ 'message' => 'Este valor não deve ser vazio.' ])
            ],
        ])->add('stCadastro', ChoiceType::class, [
            'label' => 'Situação do Cadastro',
            'choices' => $stCredito,
            'multiple' => true,
            'placeholder' => 'Todas',
            'attr' => [
                'class' => 'hidden',
            ],
            'label_attr' => [
                'class' => 'hidden',
            ],
            'required' => false,
        ])->add('formatRelatorio', FormatoRelatorioType::class, [            
            'constraints' => [
                new Assert\NotBlank([ 'message' => 'Este valor não deve ser vazio.' ])
            ],
        ])        
        ->setMethod('GET');
    }
    
    /**
     * 
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'retornoCriacaoConta' => null,
            'tpRelatorio' => static::getTiposRelatorio(),
            'attr' => [
                'target' => '_blank',
            ],
        ]);
    }
    
    /**
     * 
     * @return array
     */
    static public function getTiposRelatorio()
    {
        return [
            'Relatório Completo' => self::RELATORIO_COMPLETO,
            'Relatório por Situação de Crédito' => self::RELATORIO_ST_CADASTRO,
        ];
    }
    
    /**
     * 
     * @param integer $tpRelatorio
     * @return string
     */
    static public function getDescricaoTipoRelatorio($tpRelatorio)
    {
        return array_search($tpRelatorio, static::getTiposRelatorio());
    }
}
