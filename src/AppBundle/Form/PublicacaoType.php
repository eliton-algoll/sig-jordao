<?php

namespace AppBundle\Form;

use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\AbstractType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use AppBundle\Repository\PublicacaoRepository;

abstract class PublicacaoType extends AbstractType
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
                'placeholder' => 'Selecione',
                'label' => 'Publicação',
            ]);
    }
}
