<?php

namespace App\Form;

use App\Entity\Perfil;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use App\Form\ParticipanteTypeAbstract;
use App\Repository\ProjetoRepository;
use App\Repository\IdentidadeGeneroRepository;
use App\Repository\PerfilRepository;

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
                'class' => 'App:Perfil',
                'query_builder' => function (PerfilRepository $repo) use ($perfilParticipante) {
                    $perfis = [$perfilParticipante->getCoSeqPerfil()];
                    if ($perfilParticipante->getCoSeqPerfil() == Perfil::PERFIL_ORIENTADOR_SUPERIOR ||
                        $perfilParticipante->getCoSeqPerfil() == Perfil::PERFIL_ORIENTADOR_MEDIO)
                    {
                        $perfis = [Perfil::PERFIL_ORIENTADOR_SUPERIOR, Perfil::PERFIL_ORIENTADOR_MEDIO];
                    }
                    $qb = $repo->createQueryBuilder('p');
                    return $qb->where('p.coSeqPerfil in (:perfil)')->setParameter('perfil', $perfis);
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
            ->add('dtNascimento', TextType::class, array(
                'label' => 'Data de Nascimento',
                'mapped' => false,
                'attr' => array(
                    'readonly' => true,
                    'value' =>  $pessoaFisica->getDtNascimento()->format('d/m/Y') )
            ))

            ->add('genero', EntityType::class, array(
                'label' => 'Orientação sexual',
                'class' => 'App:IdentidadeGenero',
                'query_builder' => function (IdentidadeGeneroRepository $repo) {
                    return $repo->createQueryBuilder('s')
                        ->where('s.stRegistroAtivo = \'S\'')
                        ->andWhere('s.coIdentidadeGenero > 3')
                        ->orderBy('s.dsIdentidadeGenero', 'ASC');
                },
                'choice_label' => function ($genero) {
                    return $genero->getDsIdentidadeGenero();
                },
                'choice_attr' => function ($genero) use ($projetoPessoa) {
                    return $genero->getCoIdentidadeGenero() == $projetoPessoa->getIdentidadeGenero()->getCoIdentidadeGenero() ?
                            array('selected' => 'selected') : array();
                },
                'required' => true,
                'mapped' => false,
            ))->add('noMae', TextType::class, array(
                'label'  => 'Nome da Mãe',
                'mapped' => false,
                'attr' => array(
                    'readonly' => true,
                    'value' => $pessoaFisica->getNoMae()
                )
            ))
            ->add('noDocumentoBancario', FileType::class, array(
                'label' => 'Anexar comprovante bancário',
            ))
            ->add('noDocumentoMatricula', FileType::class, array(
                'label' => 'Anexar comprovante de matrícula do estudante',
                'attr' => array('class' => 'documento_matricula'),
                'required' => false,
            ));

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