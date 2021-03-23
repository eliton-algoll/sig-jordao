<?php

namespace AppBundle\Form;

use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\AbstractType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Doctrine\ORM\EntityRepository;
use AppBundle\Entity\SituacaoFolha;
use AppBundle\Entity\FolhaPagamento;

final class ConsultarRetonosPagamentoType extends AbstractType
{
    /**
     * 
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $publicacao = $options['publicacao'];
        
        $builder->add('publicacao', EntityType::class, [
                'class' => 'AppBundle:Publicacao',
                'required' => true,
                'query_builder' => function (EntityRepository $repository) {
                    $qb = $repository->createQueryBuilder('pub');
                    return $qb
                        ->innerJoin('pub.programa', 'prog')
                        ->orderBy('prog.dsPrograma', 'ASC')
                        ->addOrderBy('pub.dtPublicacao', 'DESC');                    
                },
                'choice_label' => function ($publicacao) {
                    return $publicacao->getDescricaoCompleta();
                },
                'placeholder' => 'Selecione',
                'label' => 'Publicação',
            ])->add('referencia', EntityType::class, [
                'class' => FolhaPagamento::class,
                'query_builder' => function (EntityRepository $repository) use ($publicacao) {
                    $qb = $repository->createQueryBuilder('fp');
                
                    return $qb
                        ->where('fp.stRegistroAtivo = :stAtivo')
                        ->andWhere('fp.publicacao = :publicacao')
                        ->andWhere('fp.tpFolhaPagamento = :tpFolhaPagamento')
                        ->andWhere('fp.situacao IN(:situacao)')
                        ->orderBy('fp.nuAno', 'DESC')
                        ->addOrderBy('fp.nuMes', 'DESC')
                        ->setParameters([
                            'stAtivo' => 'S',
                            'publicacao' => $publicacao,
                            'tpFolhaPagamento' => FolhaPagamento::MENSAL,
                            'situacao' => SituacaoFolha::getSituacoesEntreHomologadoPago(),
                        ]);
                },
                'choice_label' => function ($folhaPagamento) {                    
                    return $folhaPagamento->getNuMesAno();
                },
                'choice_value' => function ($folhaPagamento) {                    
                    return ($folhaPagamento) ? $folhaPagamento->getNuMesAno() : null;
                },
                'label' => 'Referência da Folha de Pagamento',                
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
        $resolver->setDefaults([            
            'publicacao' => null,            
        ]);        
    }
}
