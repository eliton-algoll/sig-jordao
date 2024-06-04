<?php

namespace AppBundle\Form;

use AppBundle\CommandBus\CadastrarAdministradorCommand;
use AppBundle\CommandBus\CadastrarBancoCommand;
use AppBundle\CommandBus\CadastrarUsuarioCommand;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\OptionsResolver\OptionsResolver;

final class CadastrarAdministradorType extends AbstractType
{
    /**
     * 
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {

        $builder
            ->add('nuCpf', TextType::class, [
                'label' => 'CPF',
                'attr' => array('class' => 'nuCpf'),
                'required' => true
            ])
            ->add('dsLogin', TextType::class, [
                'label' => 'Login',
                'required' => true
            ])->setMethod('POST');
    }
    
    /**
     * 
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => CadastrarAdministradorCommand::class,
        ]);
    }

}
