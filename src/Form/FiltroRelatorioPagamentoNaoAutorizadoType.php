<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use App\Repository\PerfilRepository;
use App\Repository\UfRepository;
use App\Form\EventListener\AddMunicipioFieldSubscriber;
use App\Form\EventListener\AddInstituicaoFieldSubscriber;
use App\Form\EventListener\AddCampusFieldSubscriber;
use App\Form\EventListener\AddSecretariaFieldSubscriber;

class FiltroRelatorioPagamentoNaoAutorizadoType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('nuCpf', TextType::class, array(
                'label' => 'CPF',
                'attr' => array('class' => 'nuCpf')
            ))
            ->add('nuMes', ChoiceType::class, array(
                'label' => 'Mês de referência',
                'choices' => [
                    'Jan' => '01',
                    'Fev' => '02',
                    'Mar' => '03',
                    'Abr' => '04',
                    'Mai' => '05',
                    'Jun' => '06',
                    'Jul' => '07',
                    'Ago' => '08',
                    'Set' => '09',
                    'Out' => '10',
                    'Nov' => '11',
                    'Dez' => '12'
                ],
                'preferred_choices' => [str_pad(date('m'), 2, '0', STR_PAD_LEFT)],
                'required' => false
            ))
            ->add('nuAno', ChoiceType::class, array(
                'label' => 'Ano de referência',
                'choices' => range(date('Y'), date('Y') - 10),
                'choice_label' => function($value, $key, $index) {
                    return $value;
                }
            ))
            ->add('noPessoa', TextType::class, array(
                'label' => 'Nome do participante'
            ))
            ->add('perfil', EntityType::class, array(
                'label' => 'Tipo de participante',
                'class' => 'App:Perfil',
                'query_builder' => function(PerfilRepository $perfilRepository) {
                    $qb = $perfilRepository->createQueryBuilder('p');
                    return $qb->where('p.stRegistroAtivo = \'S\'')
                        ->orderBy('p.dsPerfil');
                },
                'choice_label' => 'dsPerfil',
                'required' => false
            ))
            ->add('ufCampus', EntityType::class, array(
                'label' => 'UF',
                'class' => 'App:Uf',
                'required' => false,
                'choice_label' => 'sgUf',
                'query_builder' => function (UfRepository $repository) {
                    $qb = $repository->createQueryBuilder('uf');
                    $qb->where("uf.stRegistroAtivo = 'S'")
                        ->orderBy('uf.sgUf', 'asc');
                    return $qb;
                }
                
            ))
            ->add('ufSecretaria', EntityType::class, array(
                'label' => 'UF',
                'class' => 'App:Uf',
                'required' => false,
                'choice_label' => 'sgUf',
                'query_builder' => function (UfRepository $repo) {
                    return $repo->createQueryBuilder('uf')
                        ->where("uf.stRegistroAtivo = 'S'")
                        ->orderBy('uf.sgUf', 'asc');
                }
            ));
                
        $builder->addEventSubscriber(new AddMunicipioFieldSubscriber('municipioCampus', 'ufCampus'));
        $builder->addEventSubscriber(new AddInstituicaoFieldSubscriber('instituicao', 'municipioCampus'));
        $builder->addEventSubscriber(new AddCampusFieldSubscriber('campus', 'instituicao'));
        
        $builder->addEventSubscriber(new AddMunicipioFieldSubscriber('municipioSecretaria', 'ufSecretaria'));
        $builder->addEventSubscriber(new AddSecretariaFieldSubscriber('secretaria', 'municipioSecretaria'));
    }
    
    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(
            array(
                'csrf_protection' => false
            )
        );
    }
}
