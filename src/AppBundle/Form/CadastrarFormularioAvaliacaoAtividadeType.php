<?php

namespace AppBundle\Form;

use AppBundle\CommandBus\CadastrarFormularioAvaliacaoAtividadeCommand;
use AppBundle\Entity\Perfil;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\UrlType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

final class CadastrarFormularioAvaliacaoAtividadeType extends AbstractType
{
    /**
     * 
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('titulo', TextType::class, [
            'label' => 'Título',
            'attr' => [
                'maxlength' => 100,
            ],
        ])->add('descricao', TextareaType::class, [
            'label' => 'Descrição',
            'attr' => [
                'maxlength' => 2000,                
            ],
            'required' => false,
        ])->add('periodicidade', EntityType::class, [
            'class' => 'AppBundle:Periodicidade',
            'choice_label' => 'dsPeriodicidade',
            'label' => 'Periodicidade',
            'label_attr' => [
                'title' => 'Informe a periodicidade em que o formulário poderá ser enviado para avaliação. O sistema mostrará alerta quando o envio for fora do período. Para formulários que serão enviados a qualquer momento, selecionar a opção <Eventual>.',
            ],
        ])->add('perfis', EntityType::class, [
            'class' => 'AppBundle:Perfil',
            'query_builder' => function (EntityRepository $repo) {
                return $repo->createQueryBuilder('p')
                    ->where('p.coSeqPerfil <> :perfilAdministrador')
                    ->setParameter('perfilAdministrador', Perfil::PERFIL_ADMINISTRADOR)
                    ->orderBy('p.dsPerfil', 'ASC');
            },
            'choice_label' => 'dsPerfil',
            'multiple' => true,
            'expanded' => true,
            'label' => 'Perfil Responsável',
            'label_attr' => [
                'title' => 'Selecione os perfis dos participantes para os quais o formulário poderá ser enviado e que serão responsáveis pelo preenchimento e retorno do formulário para o administrador.',
            ],
        ])->add('urlFormulario', UrlType::class, [
            'label' => 'Endereço de acesso ao formulário no FormSUS',
            'attr' => [
                'maxlength' => 200,
            ],
            'required' => false,            
        ])->add('fileFormulario', FileType::class, [
            'label' => 'Anexar Formulário',
            'required' => false,
        ]);
    }
    
    /**
     * 
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefault('data_class', CadastrarFormularioAvaliacaoAtividadeCommand::class);
    }
}
