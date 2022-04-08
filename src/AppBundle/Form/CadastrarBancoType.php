<?php

namespace AppBundle\Form;

use AppBundle\CommandBus\CadastrarBancoCommand;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\OptionsResolver\OptionsResolver;

final class CadastrarBancoType extends AbstractType
{
    /**
     * 
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $codigo_somente_leitura = false;

        try {
            $command = $options['data'];
            if ((!is_null($command)) && (is_object($command)) && (get_class($command) === 'AppBundle\CommandBus\CadastrarBancoCommand')) {
                if (!is_null($command->getCoBanco())) {
                    $codigo_somente_leitura = true;
                }
            }
        } catch (\Exception $e) {
            // Do nothing.
        }

        $builder
            ->add('coBanco', TextType::class, [
                'label' => 'Código',
                'attr' => [
                    'maxlength' => 3,
                    'class' => 'number'
                ],
                'disabled' => $codigo_somente_leitura
            ])
            ->add('noBanco', TextType::class, [
                'label' => 'Descrição',
                'attr' => [
                    'maxlength' => 100,
                    'class' => 'text'
                ],
            ])
//            ->add('sgBanco', TextType::class, [
//                'label' => 'Sigla',
//                'attr' => [
//                    'maxlength' => 50,
//                    'class' => 'text'
//                ],
//            ])
//            // Default N
//            ->add('stConvenioFns', ChoiceType::class, [
//                'label' => 'Convênio FNS',
//                'choices' => [
//                    'Sim' => 'S',
//                    'Não' => 'N',
//                ],
//                'expanded' => true
//            ])
            // Default S
            ->add('stRegistroAtivo', ChoiceType::class, [
                'label' => 'Status',
                'choices' => [
                    'Ativo' => 'S',
                    'Inativo' => 'N',
                ],
                //'expanded' => true
            ]);
//            ->add('dsSite', TextType::class, [
//                'label' => 'Site',
//                'attr' => [
//                    'maxlength' => 100,
//                    'class' => 'text'
//                ],
//            ]);
    }
    
    /**
     * 
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => CadastrarBancoCommand::class,
        ]);
    }

}
