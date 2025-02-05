<?php

namespace AppBundle\Form;

use AppBundle\CommandBus\EnviarFaleConoscoCommand;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

final class EnviarFaleConoscoType extends AbstractType
{
    /**
     *
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('nome', TextType::class, [
            'label' => 'Nome',
            'attr' => [
                'maxlength' => 100,
            ],
        ])->add('email', EmailType::class, [            
            'label' => 'E-mail',
                'attr' => [
                    'maxlength' => 100,
            ],            
        ])->add('confirmMail', EmailType::class, [
            'label' => 'Confirmar e-mail informado',
                'attr' => [
                    'maxlength' => 100,
            ],
        ])->add('tipoAssunto', EntityType::class, [
            'class' => 'AppBundle:TipoAssunto',
            'query_builder' => function (EntityRepository $repo) {
                return $repo->createQueryBuilder('ta')
                    ->orderBy('ta.coSeqTipoAssunto', 'ASC');
            },
            'choice_label' => 'dsTipoAssunto',
            'label' => 'Como podemos ajudar?',
            'placeholder' => 'Selecione',
        ])->add('assunto', TextType::class, [
            'label' => 'Assunto',
            'required' => false,
            'attr' => [
                'maxlength' => 100,
            ],
        ])->add('menssagem', TextareaType::class, [
            'label' => 'Mensagem',
            'attr' => [
                'maxlength' => 4000,
            ],
        ]);
    }
    
    /**
     *
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
           'data_class' => EnviarFaleConoscoCommand::class
        ]);
    }
}
