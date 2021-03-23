<?php

namespace AppBundle\Form;

use AppBundle\CommandBus\CadastrarAberturaSistemaCadastroParticipanteCommand;
use AppBundle\Entity\SituacaoFolha;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

final class CadastarAberturaSistemaCadastroParticipanteType extends AbstractType
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
                    ->andWhere('fp.situacao = :situacaoFolha')
                    ->distinct()
                    ->setParameters([
                        'stAtivo' => 'S',
                        'situacaoFolha' => SituacaoFolha::ABERTA,
                    ]);
            },
            'choice_label' => function ($publicacao) {
                return $publicacao->getDescricaoCompleta();
            },
            'label' => 'Programa',
            'placeholder' => 'Selecione',
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
        ])->add('noMesAnoReferencia', TextType::class, [
            'label' => 'Referência da folha de Pagamento (Mês / Ano)',
            'attr' => [
                'readonly' => true,
            ],
        ])->add('dtInicioPeriodo', TextType::class, [
            'label' => 'Período de Abertura',
            'attr' => [
                'class' => 'dmY',
            ],
        ])->add('dtFimPeriodo',  TextType::class, [
            'attr' => [
                'class' => 'dmY margin-top-25',
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
            'publicacao' => null,
            'data_class' => CadastrarAberturaSistemaCadastroParticipanteCommand::class,
        ]);
    }
}
