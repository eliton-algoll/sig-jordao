<?php

namespace AppBundle\Form;

use AppBundle\CommandBus\CadastrarValorBolsaCommand;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\OptionsResolver\OptionsResolver;

final class CadastrarValorBolsaType extends AbstractType
{
    /**
     * 
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('publicacao', EntityType::class, [
            'class' => 'AppBundle:Publicacao',
            'query_builder' => function(EntityRepository $repo) {
                return $repo->createQueryBuilder('p')
                    ->innerJoin('p.programa', 'prg')
                    ->where('p.stRegistroAtivo = :stAtivo')
                    ->andWhere('prg.stRegistroAtivo = :stAtivo')
                    ->setParameter('stAtivo', 'S')
                    ->orderBy('prg.dsPrograma', 'ASC')
                    ->addOrderBy('p.dtPublicacao', 'DESC');
            },
            'choice_label' => function($publicacao) {
                return $publicacao->getDescricaoCompleta();
            },
            'label' => 'Programa',
            'placeholder' => 'Selecione',                    
        ])->add('tipoParticipante', EntityType::class, [
            'class' => 'AppBundle:Perfil',
            'query_builder' => function(EntityRepository $repo) {
                return $repo->createQueryBuilder('p')
                    ->orderBy('p.dsPerfil', 'ASC');
            },
            'choice_label' => 'dsPerfil',
            'label' => 'Tipo de Participante',
            'placeholder' => 'Selecione',
        ])->add('vlBolsa', TextType::class, [
            'label' => 'Valor da Bolsa',
            'attr' => [
                'maxlength' => 10,
                'class' => 'money'
            ],            
        ])->add('inicioVigencia', TextType::class, [
            'label' => 'Início de Vigência (Mês/Ano)',
            'attr' => [
                'maxlength' => 7,
            ],
        ])->add('dtInclusao', DateTimeType::class, [
            'label' => 'Data de Cadastro',
            'attr' => [
                'maxlength' => 10,
                'readonly' => true,
            ],
            'widget' => 'single_text',
            'format' => 'd/MM/yyyy',
            'data' => new \DateTime(),
        ]);
    }
    
    /**
     * 
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => CadastrarValorBolsaCommand::class,
        ]);
    }
}
