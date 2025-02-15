<?php

namespace App\Form;

use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\AbstractType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use App\Repository\RetornoCriacaoContaRepository;
use App\Entity\RetornoCriacaoConta;

final class ConsultarRetornosCadastroType extends AbstractType
{
    /**
     * 
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('mesAnoRetorno', EntityType::class, [
            'class' => RetornoCriacaoConta::class,
            'query_builder' => function (RetornoCriacaoContaRepository $repo) {                
                $qb = $repo->createQueryBuilder('rcc');
            
                $uniqueRefs = $repo->findUniqueRefs();
                
                if ($uniqueRefs) {
                    return $qb->where($qb->expr()->in('rcc.coSeqRetornoCriacaoConta', $uniqueRefs))
                        ->orderBy('rcc.dtInclusao', 'DESC');
                } else {
                    return $qb->where('1 = 2');
                }
            },
            'choice_label' => function (RetornoCriacaoConta $retornoCriacaoConta) {
                return $retornoCriacaoConta->getDtInclusao()->format('m/Y');
            },
            'choice_value' => function(RetornoCriacaoConta $retornoCriacaoConta = null) {
                if ($retornoCriacaoConta instanceof RetornoCriacaoConta) {
                    return $retornoCriacaoConta->getDtInclusao()->format('m/Y');
                }
            },
            'placeholder' => 'Selecione',
            'label' => 'Mês/Ano de retorno',
        ])->setMethod('GET');
    }
}
