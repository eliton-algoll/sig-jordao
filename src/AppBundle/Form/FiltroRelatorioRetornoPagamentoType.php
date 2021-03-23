<?php

namespace AppBundle\Form;

use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use AppBundle\Cpb\DicionarioCpb;

final class FiltroRelatorioRetornoPagamentoType extends AbstractType
{
    const RELATORIO_COMPLETO = 1;
    const RELATORIO_ST_CREDITO = 2;
    
    /**
     * 
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $retornoPagamento = $options['retornoPagamento'];
        $stCred = DicionarioCpb::getValues('SIT-CMD');
        $stCredito = [];
        
        array_walk($stCred, function ($value, $key) use(&$stCredito) {
            $stCredito[$value . ' - ' . $key] = $value;            
            unset($stCredito[$key]);
        });
        
        $builder->add('referencia', TextType::class, [
            'label' => 'Referência da Folha de Pagamento',
            'data' => $retornoPagamento->getFolhaPagamento()->getNuMesAno(),
            'attr' => [
                'readonly' => true,
            ],
        ])->add('arquivo', TextType::class, [
            'label' => 'Arquivo de Retorno de Pagamento',
            'data' => $retornoPagamento->getNoArquivoOriginal(),
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
        ])->add('stCredito', ChoiceType::class, [
            'label' => 'Situação de Crédito',
            'choices' => $stCredito,
            'multiple' => true,
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
            'retornoPagamento' => null,
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
            'Relatório por Situação de Crédito' => self::RELATORIO_ST_CREDITO,
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
