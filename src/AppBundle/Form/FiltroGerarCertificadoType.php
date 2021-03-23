<?php

namespace AppBundle\Form;

use AppBundle\Entity\Perfil;
use AppBundle\Validator\Constraints\Cpfcnpj;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

final class FiltroGerarCertificadoType extends AbstractType
{
    /**
     * 
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $projeto = $options['projeto'];
        
        $builder->add('coProjeto', HiddenType::class, [
            'data' => $projeto->getCoSeqProjeto(),
        ])->add('publicacao', EntityType::class, [
            'class' => 'AppBundle:Publicacao',
            'query_builder' => function(EntityRepository $repo) use ($projeto) {            
                $qb = $repo->createQueryBuilder('p')
                    ->innerJoin('AppBundle:Projeto', 'pj', 'JOIN', 'p.coSeqPublicacao = pj.publicacao')
                    ->where('p.stRegistroAtivo = :stAtivo')                    
                    ->setParameter('stAtivo', 'S');
                
                if ($projeto) {                    
                    $qb->andWhere('pj.coSeqProjeto = :projeto')
                        ->setParameter('projeto', $projeto->getCoSeqProjeto());
                }                
                return $qb;
            },
            'choice_label' => function($publicacao) {
                return $publicacao->getDescricaoCompleta();
            },
            'label' => 'Programa',            
        ])->add('nuSipar', TextType::class, [
            'label' => 'Nº SEI',
            'required' => false,
            'attr' => [                
                'readonly' => true,
            ],
            'data' => $projeto->getNuSipar(),
        ])->add('tipoParticipante', EntityType::class, [
            'class' => 'AppBundle:Perfil',
            'query_builder' => function(EntityRepository $repo) {
                return $repo->createQueryBuilder('p')
                    ->where('p.coSeqPerfil <> :perfil')                    
                    ->setParameter('perfil', Perfil::PERFIL_ADMINISTRADOR)
                    ->orderBy('p.dsPerfil', 'ASC');
            },            
            'choice_label' => 'dsPerfil',
            'label' => 'Tipo de Participante',
            'placeholder' => 'Selecione',
            'required' => false,
        ])->add('noPessoa', TextType::class, [
            'label' => 'Nome',
            'attr' => [
                'maxlength' => 100,
            ],
            'required' => false,
        ])->add('nuCpf', TextType::class, [
            'label' => 'CPF',
            'attr' => [
                'maxlength' => 14,
                'class' => 'nuCpf',
            ],
            'required' => false,
            'constraints' => [
                new Cpfcnpj(['aceitar' => 'cpf', 'message_cpf' => 'CPF inválido']),
            ],
        ])->setMethod('GET');
    }
    
    /**
     * 
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([            
            'projeto' => null,
        ]);
    }
}
