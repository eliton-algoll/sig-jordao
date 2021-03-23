<?php

namespace AppBundle\Form;

use AppBundle\CommandBus\CadastrarEnvioFormularioAvaliacaoAtividadeCommand;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

final class CadastrarEnvioFormularioAvaliacaoAtividadeType extends AbstractType
{
    /**
     * 
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $formularioAvaliacaoAtividade = $options['formularioAvaliacaoAtividade'];
        $publicacoes = $options['publicacoes'];
        $projetos = $options['projetos'];
        $perfis = $options['perfis'];
        $participantes = $options['to_participantes'];        
        $projetoSelecionado = $options['projetoSelecionado'];
        
        $builder->add('formularioAvaliacaoAtividade', EntityType::class, [
            'class' => 'AppBundle:FormularioAvaliacaoAtividade',
            'query_builder' => function (EntityRepository $repo) {
                return $repo->createQueryBuilder('faa')
                    ->where('faa.stRegistroAtivo = :stAtivo')
                    ->setParameter('stAtivo', 'S')
                    ->orderBy('faa.noFormulario');
            },
            'choice_label' => 'noFormulario',
            'label' => 'Título do Formulário',
            'placeholder' => 'Selecione',
        ])->add('dtInicioPeriodo', TextType::class, [            
            'label' => 'Período válido para retorno dos participantes',            
            'attr' => [
                'class' => 'dmY',
            ],
        ])->add('dtFimPeriodo', TextType::class, [
            'attr' => [
                'class' => 'margin-top-25 dmY',
            ],
        ])->add('publicacoes', EntityType::class, [
            'class' => 'AppBundle:Publicacao',
            'query_builder' => function (EntityRepository $repo) {
                return $repo->createQueryBuilder('p')
                    ->innerJoin('p.programa', 'prg')
                    ->where('p.stRegistroAtivo = :stAtivo')
                    ->andWhere('prg.stRegistroAtivo = :stAtivo')
                    ->setParameter('stAtivo', 'S')
                    ->orderBy('prg.dsPrograma', 'ASC')
                    ->addOrderBy('p.dtPublicacao', 'DESC');
            },
            'choice_label' => function ($publicacao) {
                return $publicacao->getCoSeqPublicacao() . ' - ' . $publicacao->getDescricaoCompleta();
            },
            'multiple' => true,
            'expanded' => true,
            'label' => 'Programa/Publicação',            
        ])->add('projetos', EntityType::class, [
            'class' => 'AppBundle:Projeto',
            'query_builder' => function (EntityRepository $repo) use ($publicacoes) {                
                return $repo->createQueryBuilder('p')
                    ->where('p.publicacao IN(:publicacoes)')
                    ->andWhere('p.stRegistroAtivo = :stAtivo')
                    ->setParameters([
                        'publicacoes' => $publicacoes,
                        'stAtivo' => 'S',
                    ]);
            },
            'choice_label' => function ($projeto) {
                return $projeto->getDescricaoCompletaProjeto();
            },
            'multiple' => true,
            'expanded' => true,
            'label' => 'Projeto',            
        ])->add('perfis', EntityType::class, [
            'class' => 'AppBundle:Perfil',
            'query_builder' => function (EntityRepository $repo) use ($formularioAvaliacaoAtividade) {
                return $repo->createQueryBuilder('p')
                    ->innerJoin(
                        'AppBundle:PerfilFormularioAvaliacaoAtividade',
                        'pfa',
                        'WITH',
                        'p.coSeqPerfil = pfa.perfil')                    
                    ->where('pfa.formularioAvaliacaoAtividade = :formularioAvaliacaoAtividade')
                    ->setParameter('formularioAvaliacaoAtividade', $formularioAvaliacaoAtividade)
                    ->orderBy('p.dsPerfil');
            },
            'choice_label' => 'dsPerfil',
            'multiple' => true,
            'expanded' => true,
            'label' => 'Perfil Responsável',
        ])->add('stEnviaTodos', ChoiceType::class, [
            'choices' => [
                'Todos os participantes Ativos com os perfis selecionados' => true,
                'Selecionar Participantes' => false,
            ],
            'data' => true,
            'expanded' => true,
        ])->add('projetoSelecionado', EntityType::class, [
            'class' => 'AppBundle:Projeto',
            'query_builder' => function (EntityRepository $repo) use ($projetos) {
                return $repo->createQueryBuilder('p')
                    ->where('p.coSeqProjeto IN(:projetos)')                    
                    ->setParameter('projetos', $projetos);
            },
            'choice_label' => function ($projeto) {
                return $projeto->getDescricaoCompletaProjeto();
            },
            'label' => 'Projeto',
            'placeholder' => 'Selecione',
            'required' => false,
        ])->add('from_participantes', EntityType::class, [
            'class' => 'AppBundle:ProjetoPessoa',
            'query_builder' => function (EntityRepository $repo) use ($projetoSelecionado, $perfis) {
                return $repo->createQueryBuilder('pp')
                    ->innerJoin('pp.pessoaPerfil', 'pperf')
                    ->where('pp.projeto  = :projeto')
                    ->andWhere('pp.stRegistroAtivo = :stAtivo')
                    ->andWhere('pperf.perfil IN(:perfis)')
                    ->setParameters([
                        'projeto' => $projetoSelecionado,
                        'stAtivo' => 'S',
                        'perfis' => $perfis,
                    ]);
            },
            'choice_label' => function ($projetoPessoa) {
                return $projetoPessoa->getDescricaoParticipante();
            },
            'choice_attr' => function ($projetoPessoa) {
                return [
                    'id-projeto' => $projetoPessoa->getProjeto()->getCoSeqProjeto(),
                    'id-perfil' => $projetoPessoa->getPessoaPerfil()->getPerfil()->getCoSeqPerfil(),
                ];
            },
            'label' => 'Participantes',
            'multiple' => true,
            'required' => false,
            'attr' => [
                'class' => 'customizacao-report-field',
            ],
        ])->add('to_participantes', EntityType::class, [
            'class' => 'AppBundle:ProjetoPessoa',
            'query_builder' => function (EntityRepository $repo) use ($participantes) {
                return $repo->createQueryBuilder('pp')
                    ->where('pp.coSeqProjetoPessoa IN(:participantes)')
                    ->setParameter('participantes', $participantes);
            },
            'choice_label' => function ($projetoPessoa) {
                return $projetoPessoa->getDescricaoParticipante();
            },            
            'label' => 'Selecionados para Envio',
            'multiple' => true,
            'required' => false,
            'attr' => [
                'class' => 'customizacao-report-field',
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
            'publicacoes' => [],
            'projetos' => [],
            'perfis' => [],
            'to_participantes' => [],
            'formularioAvaliacaoAtividade' => [],
            'projetoSelecionado' => null,
            'dtInicioPeriodo' => null,
            'dtFimPeriodo' => null,
            'stEnviaTodos' => null,
            '_token' => null,
            'data_class' => CadastrarEnvioFormularioAvaliacaoAtividadeCommand::class,
        ]);
    }
}
