<?php

namespace AppBundle\Form;

use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

final class ConsultarPlanejamentoAberturaFolhaPagamentoType extends AbstractType
{
    /**
     * 
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $projeto = $options['projeto'];
        $placeholderPublicacao = ($projeto) ? false : 'Selecione';
        
        $builder->add('publicacao', EntityType::class, [
            'class' => 'AppBundle:Publicacao',
            'query_builder' => function (EntityRepository $repo) use ($projeto) {
                $qb = $repo->createQueryBuilder('p')
                    ->innerJoin('p.programa', 'prg')
                    ->orderBy('prg.dsPrograma', 'ASC')
                    ->addOrderBy('p.dtPublicacao', 'DESC');
                
                if ($projeto) {
                    $qb->where('p.coSeqPublicacao = :coPublicacao')
                        ->setParameter('coPublicacao', $projeto->getPublicacao()->getCoSeqPublicacao());
                }
                
                return $qb;
            },
            'choice_label' => function($publicacao) {
                return $publicacao->getDescricaoCompleta();
            },
            'label' => 'Programa',
            'placeholder' => $placeholderPublicacao,
            'required' => false,
        ])->add('nuAnoReferencia', EntityType::class, [
            'class' => 'AppBundle:PlanejamentoAnoFolha',
            'query_builder' => function (EntityRepository $repo) {
                $results = $repo                        
                    ->createQueryBuilder('paf')
                    ->select('MAX(paf.coSeqPlanejamentoAnoFolha) as coSeqPlanejamentoAnoFolha, paf.nuAno')
                    ->where('paf.stRegistroAtivo = :stAtivo')
                    ->setParameter('stAtivo', 'S')
                    ->groupBy('paf.nuAno')
                    ->getQuery()
                    ->getArrayResult();
                
                $ids = array_map(function($row) {
                    return $row['coSeqPlanejamentoAnoFolha'];
                }, $results);
            
                return $repo->createQueryBuilder('paf')                    
                    ->where('paf.coSeqPlanejamentoAnoFolha IN (:ids)')
                    ->setParameter('ids', $ids)
                    ->orderBy('paf.nuAno', 'DESC');
            },
            'choice_label' => 'nuAno',
            'choice_value' => 'nuAno',
            'label' => 'Ano de Referência',
            'placeholder' => 'Selecione',
            'required' => false,
        ])->setMethod('GET');
    }
    
    /**
     * 
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefault('projeto', null);
    }
}
