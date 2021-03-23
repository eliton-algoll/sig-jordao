<?php

namespace AppBundle\Form;

use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use AppBundle\Form\ParticipanteTypeAbstract;
use AppBundle\Repository\ProjetoRepository;
use AppBundle\Repository\SexoRepository;
use AppBundle\Repository\PerfilRepository;

class AtualizarParticipanteType extends ParticipanteTypeAbstract
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $projetoPessoa = $options['projetoPessoaParticipante'];
        $options['projeto'] = $projetoPessoa->getProjeto();
        $pessoaPerfil = $options['projetoPessoaParticipante']->getPessoaPerfil();
        $perfilParticipante = $pessoaPerfil->getPerfil();
        $projetoParticipante = $options['projetoPessoaParticipante']->getProjeto();
        $pessoaFisica = $pessoaPerfil->getPessoaFisica();
        
        $builder
            ->add('nuSei', TextType::class, [
                'label' => 'N° SEI',
                'attr' => [
                    'class' => 'nuSipar',
                    'readonly' => $projetoParticipante ? true : false,
                ],
                'data' => $projetoParticipante ? $projetoParticipante->getNuSipar() : null,
            ])
            ->add('perfil', EntityType::class, array(
                'label' => 'Perfil do Participante',
                'mapped' => false,
                'class' => 'AppBundle:Perfil',
                'query_builder' => function (PerfilRepository $repo) use ($perfilParticipante) {
                    $qb = $repo->createQueryBuilder('p');
                    return $qb->where('p.coSeqPerfil = :perfil')->setParameter('perfil', $perfilParticipante->getCoSeqPerfil());
                },
                'choice_label' => function ($perfil) {
                    return $perfil->getDsPerfil();
                }
            ))                
            ->add('noPessoa', TextType::class, array(
                'label'  => 'Nome',
                'mapped' => false,
                'attr' => array(
                    'readonly' => true, 
                    'value' => $pessoaFisica->getPessoa()->getNoPessoa()
                )
            ))
            ->add('dtInclusao', TextType::class, array(
                'label' => 'Data do Cadastro',
                'mapped' => false,
                'attr' => array(
                    'readonly' => true,
                    'value' => $projetoPessoa->getDtInclusao()->format('d/m/Y')
                )
            ))
            ->add('dtDesligamento', TextType::class, array(
                'label' => 'Data de Desligamento',
                'mapped' => false,
                'attr' => array(
                    'readonly' => true,
                    'value' => ($projetoPessoa->getDtDesligamento()) ? $projetoPessoa->getDtDesligamento()->format('d/m/Y') : null,
                    'class' => (!$projetoPessoa->getDtDesligamento()) ? 'hidden' : null,
                ),
                'label_attr' => array(
                    'class' => (!$projetoPessoa->getDtDesligamento()) ? 'hidden' : null,
                )
            ))
            ->add('nuCpf', TextType::class, array(
                'label' => 'CPF',
                'mapped' => false,
                'attr' => array(
                    'readonly' => true,
                    'value' => $pessoaFisica->getNuCpf()
                )
            ))
            ->add('sexo', EntityType::class, array(
                'label' => 'Sexo',
                'class' => 'AppBundle:Sexo',
                'query_builder' => function (SexoRepository $repo) use ($pessoaFisica) {
                    return $repo->createQueryBuilder('s')
                                ->where('s.stRegistroAtivo = \'S\'')
                                ->where('s.coSexo = :sexo')
                                ->setParameter('sexo', $pessoaFisica->getSexo()->getCoSexo())
                                ->orderBy('s.dsSexo', 'ASC');
                },
                'choice_label' => function ($sexo) {
                    return $sexo->getDsSexo();
                },
                'disabled' => true,
                'mapped' => false
            ))->add('noMae', TextType::class, array(
                'label'  => 'Nome da Mãe',
                'mapped' => false,
                'attr' => array(
                    'readonly' => true,
                    'value' => $pessoaFisica->getNoMae()
                )
            ))
        ;
                
        parent::buildForm($builder, $options);
    }
    
    /**
     * @param \Symfony\Component\OptionsResolver\OptionsResolver $resolver
     */
    public function configureOptions(\Symfony\Component\OptionsResolver\OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'projeto' => null, 
            'pessoaPerfil' => null,
            'projetoPessoaParticipante' => null
        ));
    }    
}