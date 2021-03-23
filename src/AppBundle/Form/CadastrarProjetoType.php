<?php

namespace AppBundle\Form;

use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use AppBundle\Repository\PublicacaoRepository;

class CadastrarProjetoType extends ProjetoTypeAbstract
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        parent::buildForm($builder, $options);
        
        $builder
            ->add('publicacao', EntityType::class, array(
                'label' => 'Publicação',
                'class' => 'AppBundle:Publicacao',
                'required' => true,
                'query_builder' => function (PublicacaoRepository $repository) {
                    $qb = $repository->createQueryBuilder('pub');
                    $qb
                        ->where("pub.stRegistroAtivo = 'S'")
                        ->andWhere('pub.dtInicioVigencia <= :now')
                        ->andWhere(':now <= pub.dtFimVigencia')
                        ->setParameter('now', new \DateTime());
                    return $qb;
                },
                'choice_label' => function ($publicacao) {
                    return $publicacao->getDescricaoCompleta();
                },
                'choice_attr' => function ($publicacao) {
                    return array(
                        'data-tp-area-tematica' => $publicacao->getPrograma()->getTpAreaTematica()
                    );
                }
            ))
        ;
    }
    
    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'AppBundle\CommandBus\CadastrarProjetoCommand'
        ));
    }
}