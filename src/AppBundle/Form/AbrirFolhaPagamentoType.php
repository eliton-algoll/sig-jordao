<?php

namespace AppBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use AppBundle\Repository\PublicacaoRepository;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;

class AbrirFolhaPagamentoType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
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
                }
            ))
            ->add('nuMes', ChoiceType::class, array(
                'label'  => 'Mês',
                'choices' => [
                    'Jan' => '01',
                    'Fev' => '02',
                    'Mar' => '03',
                    'Abr' => '04',
                    'Mai' => '05',
                    'Jun' => '06',
                    'Jul' => '07',
                    'Ago' => '08',
                    'Set' => '09',
                    'Out' => '10',
                    'Nov' => '11',
                    'Dez' => '12'
                ],
                'preferred_choices' => [str_pad(date('m'), 2, '0', STR_PAD_LEFT)],
                'required' => true
            ))
           ->add('nuAno', ChoiceType::class, array(
                'label'  => 'Ano',
                'choices'  => [ 
                    date('Y') => date('Y'), 
                    date('Y',strtotime('-1 year')) => date('Y',strtotime('-1 year')) 
                ],
                'required' => true
            ));
    }
}
    