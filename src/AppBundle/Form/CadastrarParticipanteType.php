<?php

namespace AppBundle\Form;

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
            ->add('sexo', EntityType::class, array(
                'label' => 'Sexo',
                'class' => 'AppBundle:Sexo',
                'query_builder' => function (SexoRepository $repo) {
                    return $repo->createQueryBuilder('s')
                        ->where('s.stRegistroAtivo = \'S\'')
                        ->orderBy('s.dsSexo', 'ASC');
                },
                'choice_label' => function ($sexo) {
                    return $sexo->getDsSexo();
                },
                'required' => true,
                'placeholder' => '',
                'mapped' => false,
                'choice_attr' => function ($val, $key, $index) {
                    return ['disabled' => true];
                }
            ))
            ->add('noMae', TextType::class, array(
                'label' => 'Nome da Mãe',
                'attr' => array('readonly' => true),
                'mapped' => false
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