<?php

namespace AppBundle\Form;

use AppBundle\Entity\Perfil;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;

final class ConsultarFormulariosAtividadesType extends AbstractType
{
    /**
     * 
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('stFormulario', ChoiceType::class, [
            'choices' => [                
                'Ativos' => 'S',
                'Inativos' => 'N',
            ],
            'placeholder' => 'Todos',
            'label' => 'Situação do Formulário',
            'required' => false,
        ])->add('periodicidade', EntityType::class, [
            'class' => 'AppBundle:Periodicidade',
            'choice_label' => 'dsPeriodicidade',
            'placeholder' => 'Todas',
            'label' => 'Periodicidade',
            'required' => false,
        ])->add('perfil', EntityType::class, [
            'class' => 'AppBundle:Perfil',
            'query_builder' => function (EntityRepository $repo) {
                return $repo->createQueryBuilder('p')
                    ->where('p.coSeqPerfil <> :perfilAdministrador')
                    ->setParameter('perfilAdministrador', Perfil::PERFIL_ADMINISTRADOR)
                    ->orderBy('p.dsPerfil', 'ASC');
            },
            'placeholder' => 'Todos',
            'choice_label' => 'dsPerfil',
            'label' => 'Perfil Responsável',
            'required' => false,
        ])->add('titulo', TextType::class, [
            'label' => 'Título do Formulário',
            'attr' => [
                'maxlength' => 100,                
            ],
            'required' => false,
        ])->setMethod('GET');
    }
}
