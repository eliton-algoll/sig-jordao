<?php
/**
 * Created by PhpStorm.
 * User: pauloe.oliveira
 * Date: 20/03/17
 * Time: 16:24
 */

namespace AppBundle\Form;

use AppBundle\Entity\Publicacao;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ConsultarInformeRendimentoType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('publicacao', EntityType::class, array(
                'label' => 'Programa',
                'class' => 'AppBundle:Publicacao',
                'attr' => array('required' => true),
                'choice_label' => function (Publicacao $publicacao) {
                        return $publicacao->getDescricaoCompleta(true);
                    },
                'placeholder' => ''

            ))
            ->add('nuAnoBase', EntityType::class, array(
                'label' => 'Ano Base',
                'class' => 'AppBundle:FolhaPagamento',
                'query_builder' => function (EntityRepository $repo) {

                    $qb = $repo->createQueryBuilder('fp');

                    $minAno = new \DateTime();
                    $minAno->modify('-5 years');

                    $existis = $repo->createQueryBuilder('fp2');

                    $existis
                        ->select('MAX(fp2.coSeqFolhaPagamento)')
                        ->where('fp2.nuAno >= :ano')
                        ->andWhere('fp2.stRegistroAtivo = :ativo')
                        ->andWhere('fp2.nuOrdemBancaria is not null')
                        ->groupBy('fp2.nuAno');

                    return $qb
                        ->select('fp')
                        ->where($qb->expr()->in('fp.coSeqFolhaPagamento', $existis->getDQL()))
                        ->setParameters([
                            'ano' => $minAno->format('Y'),
                            'ativo' => 'S'
                        ])
                        ->orderBy('fp.nuAno', 'DESC');
                },
                'attr' => array('required' => true),
                'choice_label' => 'nuAno',
                'choice_value' => 'nuAno',
                'placeholder' => 'Selecione'

            ))
            ->add('dtNascimento', TextType::class, array(
                'label' => 'Data de Nascimento',
                'attr' => array('required' => true)
            ))
            ->add('nuCpf', TextType::class, array(
                'label' => 'CPF',
                'attr' => array('class' => 'nuCpf', 'required' => true)
            ))
        ;
    }

    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(
            array(
                'csrf_protection' => false
            )
        );
    }
}

