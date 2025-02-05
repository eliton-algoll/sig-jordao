<?php

namespace AppBundle\Form;

use AppBundle\Entity\GrupoAtuacao;
use AppBundle\Repository\GrupoAtuacaoRepository;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use AppBundle\Repository\PerfilRepository;
use AppBundle\Repository\ProgramaRepository;

class PesquisarParticipanteType extends AbstractType
{
    /**
     * 
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('noPessoa', TextType::class, array(
                'label' => 'Nome',
                'attr' => array(
                    'maxlength' => 100,
                ),
                'required' => false,
            ))
            ->add('nuCpf', TextType::class, array(
                'label' => 'CPF',
                'attr' => array('class' => 'nuCpf'),
                'required' => false,
            ))
            ->add('coPerfil', EntityType::class, array(
                'label' => 'Perfil do Participante',
                'class' => 'AppBundle:Perfil',
                'query_builder' => function (PerfilRepository $repo) use ($options) {
                    $qb = $repo->createQueryBuilder('p')
                        ->where('p.stRegistroAtivo = \'S\'')
                        ->orderBy('p.dsPerfil', 'ASC');
                    if (!$options['perfil']->isAdministrador()) {
                        $qb->andWhere('p.coSeqPerfil != 1');
                    }
                    return $qb;
                },
                'choice_label' => function($perfil) {
                    return $perfil->getDsPerfil();
                },
                'required' => false
            ))
        ;
                
        if ($options['perfil']->isAdministrador()) {
            $builder
                ->add('nuSipar', TextType::class, array(
                    'label' => 'N° SEI',
                    'required' => false,
                    'attr' => array('class' => 'nuSipar')
                ))
                ->add('coPrograma', EntityType::class, array(
                    'label' => 'Programa',
                    'class' => 'AppBundle:Programa',
                    'query_builder' => function (ProgramaRepository $repo) {
                        return $repo->createQueryBuilder('p')
                                    ->where('p.stRegistroAtivo = \'S\'')
                                    ->orderBy('p.dsPrograma', 'ASC');
                    },
                    'choice_label' => function ($programa) {
                        return $programa->getDsPrograma();
                    },
                    'required' => false
                ))
            ;
        } elseif ($options['projeto']) {
            $builder->add('grupoTutorial', EntityType::class, [
                'label' => 'Grupo',
                'class' => GrupoAtuacao::class,
                'query_builder' => function(GrupoAtuacaoRepository $repository) use($options) {
                    return $repository
                        ->createQueryBuilder('ga')
                        ->where('ga.stRegistroAtivo = :stAtivo')
                        ->andWhere('ga.projeto = :projeto')
                        ->setParameters([
                            'stAtivo' => 'S',
                            'projeto' => $options['projeto']
                        ]);
                },
                'choice_label' => 'noGrupoAtuacao',
                'placeholder' => '',
                'required' => false,
            ]);
        }

        $builder->add('stRegistroAtivo', ChoiceType::class, array(
           'label' => 'Status Ativo?',
            'choices' => ['Sim' => 'S', 'Não' => 'N'],
            'required' => false
        ));
    }

    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'perfil' => null,
            'projeto' => null,
        ));
    }
}
