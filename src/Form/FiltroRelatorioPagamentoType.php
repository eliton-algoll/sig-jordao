<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use App\Repository\SituacaoProjetoFolhaRepository;
use App\Entity\SituacaoProjetoFolha;
use Symfony\Component\OptionsResolver\OptionsResolver;
use App\Repository\UfRepository;
use App\Form\EventListener\AddMunicipioFieldSubscriber;
use App\Form\EventListener\AddInstituicaoFieldSubscriber;
use App\Form\EventListener\AddCampusFieldSubscriber;
use App\Form\EventListener\AddSecretariaFieldSubscriber;

class FiltroRelatorioPagamentoType extends AbstractType
{
    
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('nuSipar', TextType::class, array(
                'label' => 'N° SEI',
                'attr' => array('class' => 'nuSipar')
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
                'required' => false
            ))
            ->add('stProjetoFolha', EntityType::class, array(
                'label' => 'Situação',
                'class' => 'App:SituacaoProjetoFolha',
                'query_builder' => function(SituacaoProjetoFolhaRepository $situacaoProjetoFolha) {
                    $qb = $situacaoProjetoFolha->createQueryBuilder('spf');
                    return $qb->where('spf.coSeqSituacaoProjFolha in(:situacoes)')
                        ->setParameter('situacoes', array(
                        SituacaoProjetoFolha::AUTORIZADA, 
                        SituacaoProjetoFolha::HOMOLOGADA 
                    ));
                },
                'choice_label' => function ($situacaoProjetoFolha) {
                    return $situacaoProjetoFolha->getDsSituacaoProjetoFolha();
                },
                'required' => true
            ))
            ->add('ufCampus', EntityType::class, array(
                'label' => 'UF',
                'class' => 'App:Uf',
                'required' => false,
                'choice_label' => 'sgUf',
                'query_builder' => function (UfRepository $repository) {
                    $qb = $repository->createQueryBuilder('uf');
                    $qb
                        ->where("uf.stRegistroAtivo = 'S'")
                        ->orderBy('uf.sgUf', 'asc');
                    return $qb;
                }
                
            ))
            ->add('ufSecretaria', EntityType::class, array(
                'label' => 'UF',
                'class' => 'App:Uf',
                'required' => false,
                'choice_label' => 'sgUf',
                'query_builder' => function (UfRepository $repo) {
                    return $repo->createQueryBuilder('uf')
                        ->where("uf.stRegistroAtivo = 'S'")
                        ->orderBy('uf.sgUf', 'asc');
                }
            ));
            
        $builder->addEventSubscriber(new AddMunicipioFieldSubscriber('municipioCampus', 'ufCampus'));
        $builder->addEventSubscriber(new AddInstituicaoFieldSubscriber('instituicao', 'municipioCampus'));
        $builder->addEventSubscriber(new AddCampusFieldSubscriber('campus', 'instituicao'));
        
        $builder->addEventSubscriber(new AddMunicipioFieldSubscriber('municipioSecretaria', 'ufSecretaria'));
        $builder->addEventSubscriber(new AddSecretariaFieldSubscriber('secretaria', 'municipioSecretaria'));
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
