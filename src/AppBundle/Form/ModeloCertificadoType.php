<?php

namespace AppBundle\Form;

use AppBundle\CommandBus\SalvarModeloCertificadoCommand;
use AppBundle\Entity\ModeloCertificado;
use AppBundle\Entity\Programa;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

final class ModeloCertificadoType extends AbstractType
{
    /**
     *
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        /** @var SalvarModeloCertificadoCommand $data */
        $data = $builder->getData();

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
                'required' => true
            ])
            ->add('tipo', ChoiceType::class, [
                'placeholder' => 'Selecione',
                'label' => 'Tipo de Modelo',
                'choices' => ModeloCertificado::getTpDocumentos(),
                'required' => true
            ])
            ->add('nome', TextType::class, [
                'label' => 'Nome do Modelo',
                'attr' => [
                    'maxlength' => 200
                ],
                'required' => true
            ])
            ->add('descricao', TextareaType::class, [
                'label' => 'Conteúdo',
                'attr' => [
                    'maxlength' => 4000
                ],
                'required' => true
            ])
            ->add('imagem', FileType::class, [
                'label' => 'Imagem do Certificado',
                'required' => !$data->getId()
            ])
            ->add('imagemRodape', FileType::class, [
                'label' => 'Imagem do Rodapé (Marcas do Governo)',
                'required' => false
            ]);
    }

    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => SalvarModeloCertificadoCommand::class,
            'validation_groups' => ['Default']
        ]);
    }
}
