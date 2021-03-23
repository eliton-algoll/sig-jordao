<?php

namespace AppBundle\Form;

use AppBundle\Entity\ModeloCertificado;
use AppBundle\Entity\Programa;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

final class FiltroModeloCertificadoType extends AbstractType
{
    /**
     *
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('programa', EntityType::class, [
                'class' => Programa::class,
                'query_builder' => function(EntityRepository $repo) {
                    return $repo->createQueryBuilder('pr')
                        ->where('pr.stRegistroAtivo = \'S\'')
                        ->orderBy('pr.dsPrograma', 'ASC');
                },
                'choice_label' => 'dsPrograma',
                'label' => 'Programa',
                'placeholder' => 'Selecione',
                'required' => false
            ])
            ->add('tipo', ChoiceType::class, [
                'placeholder' => 'Selecione',
                'label' => 'Tipo de Modelo',
                'choices' => ModeloCertificado::getTpDocumentos(),
                'required' => false
            ])
            ->add('nome', TextType::class, array(
                'label' => 'Nome do Modelo',
                'attr' => array(
                    'maxlength' => 30
                ),
                'required' => false
            ))
            ->add('ativo', ChoiceType::class, [
                'placeholder' => 'Selecione',
                'label' => 'Registro Ativo?',
                'choices' => ['Sim' => 'S', 'Não' => 'N'],
                'required' => false
            ]);
    }

    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'csrf_protection' => false
        ]);
    }
}
