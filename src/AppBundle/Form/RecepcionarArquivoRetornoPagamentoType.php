<?php

namespace AppBundle\Form;

use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\AbstractType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use AppBundle\Repository\PublicacaoRepository;
use AppBundle\Repository\FolhaPagamentoRepository;
use AppBundle\Entity\SituacaoFolha;
use AppBundle\Entity\FolhaPagamento;
use AppBundle\CommandBus\RecepcionarArquivoRetornoPagamentoCommand;

final class RecepcionarArquivoRetornoPagamentoType extends AbstractType
{
    /**
     * 
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $publicacao = $options['publicacao'];
        $tpFolhaPagamento = $options['tpFolhaPagamento'];
        
        $builder
            ->add('publicacao', EntityType::class, [
                'label' => 'Publicação',
                'class' => 'AppBundle:Publicacao',
                'required' => true,
                'query_builder' => function (PublicacaoRepository $repository) {
                    $qb = $repository->createQueryBuilder('pub');
                    $qb->innerJoin('pub.programa', 'prog')
                        ->where("pub.stRegistroAtivo = 'S'")                            
                        ->andWhere("prog.stRegistroAtivo = 'S'");
                    
                    return $qb;
                },
                'choice_label' => function ($publicacao) {
                    return $publicacao->getDescricaoCompleta();
                },
                'placeholder' => 'Selecione',
            ])
            ->add('tpFolhaPagamento', ChoiceType::class, [
                'choices' => FolhaPagamento::getTiposFolha(),
                'expanded' => true,
                'data' => $tpFolhaPagamento,
                'label' => 'Tipo de Folha',
                'choice_attr' => [
                    'required' => false,
                ],                
            ])
            ->add('folhaPagamento', EntityType::class, [
                'class' => 'AppBundle:Folhapagamento',
                'query_builder' => function (FolhaPagamentoRepository $repository) use ($publicacao, $tpFolhaPagamento) {
                    $qb = $repository->createQueryBuilder('fp');
                
                    $qb
                        ->where('fp.stRegistroAtivo = :stAtivo')            
                        ->andWhere('fp.publicacao = :publicacao')
                        ->andWhere('fp.tpFolhaPagamento = :tpFolhaPagamento')
                        ->andWhere('fp.stRetornoPagamento = :stRetornoPagamento')
                        ->andWhere('fp.situacao IN(:situacao)')                            
                        ->orderBy('fp.nuAno', 'DESC')
                        ->addOrderBy('fp.nuMes', 'DESC')
                        ->setParameters([
                            'stAtivo' => 'S',
                            'publicacao' => $publicacao,
                            'tpFolhaPagamento' => $tpFolhaPagamento,
                            'stRetornoPagamento' => 'N',
                            'situacao' => [ SituacaoFolha::ORDEM_BANCARIA_EMITIDA ]
                        ]);
                    
                    return $qb;
                },
                'choice_label' => function ($folhaPagamento) {                    
                    return $folhaPagamento->getNuMesAno() . ' - ' . $folhaPagamento->getNuSipar();
                },
                'label' => 'Referência da Folha de Pagamento',                
                'placeholder' => 'Selecione',
            ])
            ->add('arquivoRetorno', FileType::class, [
                'label' => 'Arquivo de Retorno',
                'required' => true,
            ]);
    }
    
    /**
     * 
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => RecepcionarArquivoRetornoPagamentoCommand::class,
            'publicacao' => null,
            'tpFolhaPagamento' => null,
        ]);        
    }
}
