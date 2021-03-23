<?php

namespace AppBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use AppBundle\Repository\GrupoAtuacaoRepository;
use AppBundle\Repository\UfRepository;
use AppBundle\Form\EventListener\AddMunicipioFieldSubscriber;
use AppBundle\Form\EventListener\AddInstituicaoFieldSubscriber;
use AppBundle\Form\EventListener\AddCampusFieldSubscriber;
use AppBundle\Form\EventListener\AddSecretariaFieldSubscriber;

class FiltroRelatorioProjetoType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('nuSipar', TextType::class, array(
                'label' => 'N° SEI'
            ))
            ->add('grupoAtuacao', EntityType::class, array(
                'label' => 'Grupo de atuação',
                'class' => 'AppBundle:GrupoAtuacao',
                'query_builder' => function(GrupoAtuacaoRepository $grupoAtuacaoRepository) {
                    $qb = $grupoAtuacaoRepository->createQueryBuilder('ga');
                    return $qb->where('ga.stRegistroAtivo = \'S\'')
                        ->orderBy('ga.noGrupoAtuacao')
                        ->distinct();
                },
                'choice_label' => 'noGrupoAtuacao',
                'choice_value' => 'noGrupoAtuacao',
                'required' => false
            ))
            ->add('ufCampus', EntityType::class, array(
                'label' => 'UF',
                'class' => 'AppBundle:Uf',
                'required' => false,
                'choice_label' => 'sgUf',
                'query_builder' => function (UfRepository $repository) {
                    $qb = $repository->createQueryBuilder('uf');
                    $qb->where("uf.stRegistroAtivo = 'S'")
                        ->orderBy('uf.sgUf', 'asc');
                    return $qb;
                }
                
            ))
            ->add('ufSecretaria', EntityType::class, array(
                'label' => 'UF',
                'class' => 'AppBundle:Uf',
                'required' => false,
                'choice_label' => 'sgUf',
                'query_builder' => function (UfRepository $repo) {
                    return $repo->createQueryBuilder('uf')
                        ->where("uf.stRegistroAtivo = 'S'")
                        ->orderBy('uf.sgUf', 'asc');
                }
            ))
                ;  
        $builder->addEventSubscriber(new AddMunicipioFieldSubscriber('municipioCampus', 'ufCampus'));
        $builder->addEventSubscriber(new AddInstituicaoFieldSubscriber('instituicao', 'municipioCampus'));
        $builder->addEventSubscriber(new AddCampusFieldSubscriber('campus', 'instituicao'));
        
        $builder->addEventSubscriber(new AddMunicipioFieldSubscriber('municipioSecretaria', 'ufSecretaria'));
        $builder->addEventSubscriber(new AddSecretariaFieldSubscriber('secretaria', 'municipioSecretaria'));
    }
}
