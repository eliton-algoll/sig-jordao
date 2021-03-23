<?php

namespace AppBundle\Form;

use AppBundle\Entity\FolhaPagamento;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

final class ConsultarAberturaSistemaCadastroParticipanteType extends AbstractType
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
            'query_builder' => function (EntityRepository $repo) {
                return $repo->createQueryBuilder('pub')
                    ->innerJoin('pub.programa', 'prog')
                    ->innerJoin('pub.folhasPagamento', 'fp')
                    ->where('pub.stRegistroAtivo = :stAtivo')
                    ->andWhere('prog.stRegistroAtivo = :stAtivo')
                    ->andWhere('fp.stRegistroAtivo = :stAtivo')                    
                    ->distinct()
                    ->setParameter('stAtivo', 'S');
            },
            'choice_label' => function ($publicacao) {
                return $publicacao->getDescricaoCompleta();
            },
            'label' => 'Programa',
            'placeholder' => 'Selecione',
            'required' => false,
        ])->add('projeto', EntityType::class, [
            'class' => 'AppBundle:Projeto',
            'query_builder' => function (EntityRepository $repo) use ($publicacao) {
                return $repo->createQueryBuilder('proj')
                    ->innerJoin('proj.publicacao', 'pub')
                    ->where('pub.coSeqPublicacao = ?0')
                    ->andWhere('proj.stRegistroAtivo = ?1')
                    ->orderBy('proj.nuSipar')
                    ->setParameters([ $publicacao, 'S', ]);
            },
            'choice_label' => 'nuSipar',            
            'label' => 'Projeto',
            'placeholder' => 'Selecione',
            'required' => false,
        ])->add('folhaPagamento', EntityType::class, [
            'class' => 'AppBundle:FolhaPagamento',
            'query_builder' => function (EntityRepository $repo) use ($publicacao) {
                return $repo->createQueryBuilder('fp')
                    ->where('fp.publicacao = ?0')
                    ->andWhere('fp.stRegistroAtivo = ?1')
                    ->andWhere('fp.tpFolhaPagamento = ?2')
                    ->setParameters([ $publicacao, 'S', FolhaPagamento::MENSAL, ])
                    ->orderBy('fp.nuAno', 'DESC')
                    ->addOrderBy('fp.nuMes', 'DESC');
            },
            'choice_label' => function ($folhaPagamento) {
                return ucfirst($folhaPagamento->getReferenciaExtenso());
            },
            'label' => 'Mês/Ano Referência',
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
        $resolver->setDefault('publicacao', null);
    }
}
