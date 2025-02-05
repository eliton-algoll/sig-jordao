<?php

namespace AppBundle\Form;

use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\AbstractType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use AppBundle\Repository\PublicacaoRepository;
use AppBundle\CommandBus\RecepcionarArquivoRetornoCadastroCommand;

final class RecepcionarArquivoRetornoCadastroType extends AbstractType
{
    /**
     * 
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
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
            ->add('arquivoRetorno', FileType::class, [
                'label' => 'Arquivo de Retorno',
            ]);
    }
    
    /**
     * 
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefault('data_class', RecepcionarArquivoRetornoCadastroCommand::class);
    }
}
