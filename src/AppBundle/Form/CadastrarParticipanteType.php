<?php

namespace AppBundle\Form;

use AppBundle\Repository\IdentidadeGeneroRepository;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use AppBundle\Form\ParticipanteTypeAbstract;
use AppBundle\Repository\ProjetoRepository;
use AppBundle\Repository\SexoRepository;
use AppBundle\Repository\PerfilRepository;

class CadastrarParticipanteType extends ParticipanteTypeAbstract
{

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $projeto = $options['projeto'];
        $pessoaPerfil = $options['pessoaPerfil'];

        $builder
            ->add('nuSei', TextType::class, [
                'label' => 'N° SEI',
                'attr' => [
                    'class' => 'nuSipar',
                    'readonly' => $projeto ? true : false,
                ],
                'data' => $projeto ? $projeto->getNuSipar() : null,
            ])
            ->add('perfil', EntityType::class, array(
                'label' => 'Perfil do Participante',
                'class' => 'AppBundle:Perfil',
                'query_builder' => function (PerfilRepository $repo) use ($pessoaPerfil) {
                    return $repo->getDisponiveisParaCadastroDeParticipante($pessoaPerfil->getPerfil()->getNoRole());
                },
                'choice_label' => function ($perfil) {
                    return $perfil->getDsPerfil();
                },
                'placeholder' => ''
            ))
            ->add('noPessoa', TextType::class, array(
                'label' => 'Nome',
                'mapped' => false,
                'attr' => array('readonly' => true)
            ))
            ->add('nuCpf', TextType::class, array(
                'label' => 'CPF',
                'attr' => array('class' => 'nuCpf')
            ))
            ->add('dtNascimento', TextType::class, array(
                'label' => 'Data de Nascimento',
                'mapped' => false,
                'attr' => array('readonly' => true)
            ))
            ->add('genero', EntityType::class, array(
                'label' => 'Gênero',
                'class' => 'AppBundle:IdentidadeGenero',
                'query_builder' => function (IdentidadeGeneroRepository $repo) {
                    return $repo->createQueryBuilder('s')
                        ->where('s.stRegistroAtivo = \'S\'')
                        ->orderBy('s.dsIdentidadeGenero', 'ASC');
                },
                'choice_label' => function ($genero) {
                    return $genero->getDsIdentidadeGenero();
                },
                'required' => true,
                'placeholder' => '',
                'mapped' => false,
            ))
            ->add('noMae', TextType::class, array(
                'label' => 'Nome da Mãe',
                'attr' => array('readonly' => true),
                'mapped' => false
            ))
            ->add('noDocumentoBancario', FileType::class, array(
                'label' => 'Anexar comprovante bancário',
            ));


        parent::buildForm($builder, $options);
    }

    /**
     * @param \Symfony\Component\OptionsResolver\OptionsResolver $resolver
     */
    public function configureOptions(\Symfony\Component\OptionsResolver\OptionsResolver $resolver)
    {
        $resolver->setDefaults(array('projeto' => null, 'pessoaPerfil' => null));
    }

}