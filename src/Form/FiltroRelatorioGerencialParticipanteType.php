<?php

namespace App\Form;

use App\Entity\GrupoAtuacao;
use App\Entity\Perfil;
use App\Repository\GrupoAtuacaoRepository;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Callback;
use Symfony\Component\Validator\Context\ExecutionContextInterface;

final class FiltroRelatorioGerencialParticipanteType extends AbstractType
{
    private $customizacaoData = [
        'Programa/Publicação' => 'PROGRAMA_PUBLICACAO',
        'Número SEI' => 'NU_SIPAR',
        'Grupo' => 'GRUPO_ATUACAO_PROJETO',
        'Área de atuação no projeto' => 'AREA_ATUACAO_PROJETO',
        'Instituição de Ensino' => 'NO_INSTITUICAO_PROJETO',
        'Secretaria de Saúde' => 'SECRETARIA_SAUDE',
        'Nome do Participante *' => 'NO_PESSOA',
        'Código do Banco' => 'CO_BANCO',
        'Nome do Banco' => 'NO_BANCO',
        'Agência Bancária' => 'CO_AGENCIA',
        'Conta' => 'CO_CONTA',
        'Ano de Ingresso no curso' => 'NU_ANO_INGRESSO',
        'Categoria Profissional' => 'DS_CATEGORIA_PROFISSIONAL',
        'Curso de Graduação' => 'CURSO_GRADUACAO',
        'CPF' => 'NU_CPF_CNPJ_PESSOA',
        'Data do Cadastro' => 'DT_INCLUSAO_PROJPESSOA',
        'Data do Desligamento' => 'DT_DESLIGAMENTO',
        'Data de Nascimento' => 'DT_NASCIMENTO',
        'Endereço Completo' => 'ENDERECO',
        'Endereço de E-mail' => 'DS_EMAIL',
        'Número CNES' => 'CO_CNES',
        'Sexo' => 'SG_SEXO',
        'Situação' => 'TIPO_PARTICIPACAO',
        'Tipo de Participante' => 'DS_PERFIL',
        'Tipo de Participação' => 'ST_VOLUNTARIO_PROJETO',
        'Telefone' => 'TELEFONE',
    ];
    
    /**
     *
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $projeto = $options['projeto'];
        $form = $options['filtro_relatorio_gerencial_participante'];
        $toCustomizacao = (isset($form['to_customizacao'])) ? $form['to_customizacao'] : [];
        $placeHolderPublicacao = ($projeto) ? false : 'Selecione';
        
        $builder->add('publicacao', EntityType::class, [
            'class' => 'App:Publicacao',
            'query_builder' => function (EntityRepository $repo) use ($projeto) {
                $qb = $repo->createQueryBuilder('p')
                    ->innerJoin('p.programa', 'prg')
                    ->orderBy('prg.dsPrograma', 'ASC')
                    ->addOrderBy('p.dtPublicacao', 'DESC');
                
                if ($projeto) {
                    $qb->where('p.coSeqPublicacao = :publicacao')
                        ->setParameter('publicacao', $projeto->getPublicacao()->getCoSeqPublicacao());
                }
                return $qb;
            },
            'choice_label' => function ($publicacao) {
                return $publicacao->getDescricaoCompleta();
            },
            'label' => 'Programa / Publicação',
            'required' => false,
            'placeholder' => $placeHolderPublicacao,
        ])->add('nuSipar', TextType::class, [
            'label' => 'Nº SEI',
            'required' => false,
            'attr' => [
                'class' => 'nuSipar',
                'readonly' => ($projeto) ? true : false,
            ],
            'data' => ($projeto) ? $projeto->getNuSipar() : null,
        ])->add('tipoParticipante', EntityType::class, [
            'class' => 'App:Perfil',
            'query_builder' => function (EntityRepository $repo) {
                return $repo->createQueryBuilder('p')
                    ->where('p.coSeqPerfil <> :perfil')
                    ->setParameter('perfil', Perfil::PERFIL_ADMINISTRADOR)
                    ->orderBy('p.dsPerfil', 'ASC');
            },
            'choice_label' => 'dsPerfil',
            'label' => 'Tipo de Participante',
            'required' => false,
            'placeholder' => 'Selecione',
        ])->add('tipoParticipacao', ChoiceType::class, [
            'choices' => [
                'Bolsista' => 'N',
                'Voluntário' => 'S',
            ],
            'label' => 'Tipo de Participação',
            'required' => false,
            'placeholder' => 'Selecione',
        ])->add('stParticipante', ChoiceType::class, [
            'choices' => [
                'Ativo' => 'S',
                'Inativo' => 'N',
            ],
            'label' => 'Situação do Participante',
            'required' => true,
            'data' => 'S',
        ])->add('from_customizacao', ChoiceType::class, [
            'choices' => $this->buildFromCustomizacao($toCustomizacao),
            'multiple' => true,
            'label' => 'Customização',
            'required' => false,
            'attr' => [
                'class' => 'customizacao-report-field',
            ],
        ])->add('to_customizacao', ChoiceType::class, [
            'choices' => $this->buildToCustomizacao($toCustomizacao),
            'multiple' => true,
            'label' => ' ',
            'required' => false,
            'attr' => [
                'class' => 'customizacao-report-field',
            ],
            'constraints' => [
                new Callback([ 'callback' => [$this, 'validateForm'] ])
            ],
        ]);

        if ($projeto && $projeto->getPublicacao()->getPrograma()->isGrupoTutorial()) {
            $builder->add('grupoTutorial', EntityType::class, [
                'label' => 'Grupo Tutorial',
                'class' => GrupoAtuacao::class,
                'query_builder' => function(GrupoAtuacaoRepository $repository) use ($projeto) {
                    return $repository->createQueryBuilder('ga')
                        ->where('ga.projeto = :projeto')
                        ->andWhere('ga.stRegistroAtivo = :stAtivo')
                        ->orderBy('ga.noGrupoAtuacao')
                        ->setParameters([
                            'projeto' => $projeto,
                            'stAtivo' => 'S',
                        ]);
                },
                'choice_label' => 'noGrupoAtuacao',
                'required' => false
            ]);
        }
    }
    
    /**
     *
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'projeto' => null,
            'filtro_relatorio_gerencial_participante' => [],
        ]);
    }
    
    /**
     *
     * @param mixed $customizacaoTo
     * @return array
     */
    private function buildToCustomizacao($customizacaoTo = [])
    {
        $build = [];
        
        foreach ($customizacaoTo as $col) {
            if (in_array($col, $this->customizacaoData)) {
                $build[array_search($col, $this->customizacaoData)] = $col;
            }
        }        
        
        return $build;
    }
    
    /**
     *
     * @param mixed $customizacaoTo
     * @return array
     */
    private function buildFromCustomizacao($customizacaoTo = [])
    {
        $clean = [];
        
        foreach ($this->customizacaoData as $name => $data) {
            if (is_array($customizacaoTo) && in_array($data, $customizacaoTo)) {
                continue;
            }
            $clean[$name] = $data;
        }
        
        return $clean;
    }
    
    /**
     *
     * @param array $data
     * @param ExecutionContextInterface $context
     */
    public function validateForm($data, ExecutionContextInterface $context)
    {
        if (!in_array('NO_PESSOA', $data)) {
            $context->buildViolation('É obrigatório selecionar: Nome do Participante na lista de customização do relatório. Verifique e repita a operação.')
                ->atPath('to_customizacao')
                ->addViolation();
        }
    }
}
