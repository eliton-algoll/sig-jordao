<?php

namespace AppBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use AppBundle\Repository\PerfilRepository;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use AppBundle\Validator\Constraints as AppAssert;
use AppBundle\Repository\CursoGraduacaoRepository;
use AppBundle\Repository\GrupoAtuacaoRepository;

class FiltroRelatorioParticipanteType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('nuSipar', TextType::class, array(
                'label' => 'N° SEI'
            ))
            ->add('nuCpf', TextType::class, array(
                'label' => 'CPF',
                'attr' => array('class' => 'nuCpf'),
                'constraints' => array(
                    new AppAssert\Cpfcnpj(['aceitar' => 'cpf', 'message_cpf' => 'CPF inválido']),
                ),
            ))
            ->add('noPessoa', TextType::class, array(
                'label' => 'Nome do participante'
            ))
            ->add('perfil', EntityType::class, array(
                'label' => 'Tipo de participante',
                'class' => 'AppBundle:Perfil',
                'query_builder' => function(PerfilRepository $perfilRepository) {
                    $qb = $perfilRepository->createQueryBuilder('p');
                    return $qb->where('p.stRegistroAtivo = \'S\'')
                        ->orderBy('p.dsPerfil');
                },
                'choice_label' => 'dsPerfil',
                'required' => false
            ))
            ->add('stVoluntarioProjeto', ChoiceType::class, array(
                'label' => 'É voluntário?',
                'choices' => ['Sim' => 'S', 'Não' => 'N'],
                'required' => false
            ))
            ->add('grupoAtuacao', EntityType::class, array(
                'label' => 'Grupo de atuação',
                'class' => 'AppBundle:GrupoAtuacao',
                'query_builder' => function(GrupoAtuacaoRepository $grupoAtuacaoRepository) {
                    $qb = $grupoAtuacaoRepository->createQueryBuilder('ga');
                    return $qb->where('ga.stRegistroAtivo = \'S\'')
                        ->orderBy('ga.noGrupoAtuacao');
                },
                'choice_value' => 'noGrupoAtuacao',
                'choice_label' => 'noGrupoAtuacao',
                'required' => false
            ))
            ->add('cursoGraduacao', EntityType::class, array(
                'label' => 'Curso graduação',
                'class' => 'AppBundle:CursoGraduacao',
                'query_builder' => function(CursoGraduacaoRepository $cursoGraduacaoRepository) {
                    $qb = $cursoGraduacaoRepository->createQueryBuilder('cg');
                    return $qb->where('cg.stRegistroAtivo = \'S\'')
                        ->orderBy('cg.dsCursoGraduacao');
                },
                'choice_value' => 'dsCursoGraduacao',
                'choice_label' => 'dsCursoGraduacao',
                'required' => false
            ))
            ;
    }
}
