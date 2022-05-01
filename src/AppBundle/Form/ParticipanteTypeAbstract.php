<?php

namespace AppBundle\Form;

use AppBundle\Entity\AreaTematica;
use AppBundle\Entity\GrupoAtuacao;
use AppBundle\Repository\AreaTematicaRepository;
use AppBundle\Repository\GrupoAtuacaoRepository;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use AppBundle\Repository\ProjetoRepository;
use AppBundle\Repository\PerfilRepository;
use AppBundle\Repository\UfRepository;
use AppBundle\Repository\TitulacaoRepository;
use AppBundle\Repository\CursoGraduacaoRepository;
use AppBundle\Repository\BancoRepository;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\FormEvent;
use AppBundle\Entity\Uf;
use AppBundle\Entity\Projeto;

class ParticipanteTypeAbstract extends AbstractType
{

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $projeto = $options['projeto'];

        if ($projeto) {
            $builder
                ->add('stVoluntarioProjeto', ChoiceType::class, array(
                    'label' => 'Participante Bolsista?',
                    'choices' => array(
                        'Não' => 'S',
                        'Sim' => 'N'
                    ),
                    'expanded' => true,
                    'choice_value' => function ($currentChoiceKey) {
                        return $currentChoiceKey ? $currentChoiceKey : 'N';
                    },
                ));
        }

        $builder
//            ->add('coBanco', ChoiceType::class, array(
//                'label' => 'Banco',
//                'choices' => array(
//                    '001 - Banco do Brasil S.A' => '001'
//                ),
//                'required' => true,
//            ))
            ->add('coBanco', EntityType::class, array(
                'label' => 'Banco',
                'class' => 'AppBundle:Banco',
                'placeholder' => '',
                'query_builder' => function (BancoRepository $repo) {
                    return $repo->createQueryBuilder('banco')
                        ->where('banco.stRegistroAtivo = \'S\' AND banco.dtRemocao IS NULL')
                        ->orderBy('banco.coBanco', 'ASC');
                },
                'choice_label' => function ($banco) {
                    return $banco->getCoBanco() . ' - ' . $banco->getNoBanco();
                },
                'required' => true,
            ))
            ->add('coAgenciaBancaria', TextType::class, array(
                'label' => 'Agência Bancária',
                'attr' => array('maxlength' => 6)
            ))
            ->add('coConta', TextType::class, array(
                'label' => 'Conta',
                'attr' => array('maxlength' => 10)
            ))
            ##############################################
            ->add('dsEnderecoWeb', EmailType::class, array(
                'label' => 'E-mail',
                'attr' => array('maxlength' => 60)
            ))
            ->add('coCep', TextType::class, array(
                'label' => 'CEP',
                'attr' => array('maxlength' => 8, 'class' => 'int')
            ))
            ->add('coUf', EntityType::class, array(
                'label' => 'UF',
                'class' => 'AppBundle:Uf',
                'placeholder' => '',
                'query_builder' => function (UfRepository $repo) {
                    return $repo->createQueryBuilder('uf')
                        ->where('uf.stRegistroAtivo = \'S\'')
                        ->orderBy('uf.sgUf', 'ASC');
                },
                'choice_label' => function ($uf) {
                    return $uf->getSgUf();
                }
            ))
            ->add('noLogradouro', TextType::class, array(
                'label' => 'Logradouro',
                'attr' => array('maxlength' => 50)
            ))
            ->add('nuLogradouro', TextType::class, array(
                'label' => 'Número',
                'attr' => array('maxlength' => 7)
            ))
            ->add('dsComplemento', TextType::class, array(
                'label' => 'Complemento',
                'attr' => array('maxlength' => 15)
            ))
            ->add('noBairro', TextType::class, array(
                'label' => 'Bairro',
                'attr' => array('maxlength' => 30)
            ))
            ##############################################
            ->add('categoriaProfissional', EntityType::class, array(
                'label' => 'Categoria Profissional',
                'class' => 'AppBundle:CategoriaProfissional',
                'choice_label' => function ($categoria) {
                    return $categoria->getDsCategoriaProfissional();
                },
                'required' => false
            ))
            ->add('coCnes', TextType::class, array(
                'label' => 'Número CNES',
                'attr' => array('maxlength' => 7),
                'required' => false
            ))
            ->add('titulacao', EntityType::class, array(
                'label' => 'Titulação',
                'class' => 'AppBundle:Titulacao',
                'query_builder' => function (TitulacaoRepository $repo) {
                    return $repo->createQueryBuilder('t')
                        ->where('t.stRegistroAtivo = \'S\'')
                        ->orderBy('t.dsTitulacao', 'ASC');
                },
                'choice_label' => function ($t) {
                    return $t->getDsTitulacao();
                },
                'required' => false
            ))
            ->add('cursoGraduacao', EntityType::class, array(
                'label' => 'Curso de Graduação',
                'class' => 'AppBundle:CursoGraduacao',
                'query_builder' => function (CursoGraduacaoRepository $repo) {
                    return $repo->createQueryBuilder('cg')
                        ->where('cg.stRegistroAtivo = \'S\'')
                        ->orderBy('cg.dtInclusao') // Apenas para a opção outro ficar em último
                        ->addOrderBy('cg.dsCursoGraduacao');
                },
                'choice_label' => 'dsCursoGraduacao',
                'required' => false
            ))
            ->add('nuAnoIngresso', TextType::class, array(
                'label' => 'Ano de ingresso',
                'attr' => array('maxlength' => 4, 'class' => 'int'),
                'required' => false
            ))
            ->add('nuMatriculaIES', TextType::class, array(
                'label' => 'Matrícula IES',
                'required' => false
            ))
            ->add('nuSemestreAtual', TextType::class, array(
                'label' => 'Semestre atual',
                'attr' => array('maxlength' => 2, 'class' => 'int'),
                'required' => false
            ));

        $this->buildGrupoTutorial($builder, $projeto);
        $this->buildAreaAtuacao($builder, $projeto);

        $formModifierUf = function (FormInterface $form, Uf $uf = null) {
            $municipios = null === $uf ? array() : $uf->getMunicipios();

            $form->add('coMunicipioIbge', EntityType::class, array(
                'class' => 'AppBundle:Municipio',
                'label' => 'Municipio',
                'choices' => $municipios,
                'choice_label' => function ($municipio) {
                    return $municipio->getNoMunicipio();
                }
            ));
        };

        $builder->addEventListener(
            FormEvents::PRE_SET_DATA,
            function (FormEvent $event) use ($formModifierUf) {
                $data = $event->getData();
                $formModifierUf($event->getForm(), $data->getCoUf());
            }
        );

        $builder->get('coUf')->addEventListener(
            FormEvents::POST_SUBMIT,
            function (FormEvent $event) use ($formModifierUf) {
                $uf = $event->getForm()->getData();
                $formModifierUf($event->getForm()->getParent(), $uf);
            }
        );
    }

    /**
     * @param FormBuilderInterface $builder
     * @param Projeto|null $projeto
     */
    private function buildGrupoTutorial(FormBuilderInterface $builder, Projeto $projeto = null)
    {
        if (!$projeto || !$projeto->getPublicacao()->getPrograma()->isGrupoTutorial()) {
            return;
        }

        $builder
            ->add('stAlunoRegular', ChoiceType::class, array(
                'label' => 'Aluno Regular',
                'choices' => array(
                    'Não' => 'N',
                    'Sim' => 'S'
                ),
                'expanded' => true,
                'choice_value' => function ($currentChoiceKey) {
                    return $currentChoiceKey ? $currentChoiceKey : null;
                },
            ))
            ->add('grupoTutorial', EntityType::class, [
                'label' => 'Grupo Tutorial',
                'class' => GrupoAtuacao::class,
                'placeholder' => 'Selecione um Grupo Tutorial',
                'query_builder' => function (GrupoAtuacaoRepository $repository) use ($projeto) {
                    return $repository->createQueryBuilder('ga')
                        ->where('ga.projeto = :projeto')
                        ->andWhere('ga.stRegistroAtivo = :stAtivo')
                        ->setParameters([
                            'projeto' => $projeto,
                            'stAtivo' => 'S',
                        ])
                        ->orderBy('ga.noGrupoAtuacao');
                },
                'choice_label' => function (GrupoAtuacao $grupoAtuacao) {
                    return $grupoAtuacao->getNoGrupoAtuacao();
                },
                'required' => true
            ])
            ->add('coEixoAtuacao', ChoiceType::class, array(
                'label' => 'Eixo de Atuação',
                'choices' => array(
                    'Gestão em Saúde' => 'G',
                    'Assistência à Saúde' => 'A'
                ),
                'expanded' => true,
                'choice_value' => function ($currentChoiceKey) {
                    return $currentChoiceKey ? $currentChoiceKey : null;
                },
            ))
            ->add('stDeclaracaoCursoPenultimo', CheckboxType::class, array(
                'label' => 'Declaro que o Estudante está cursando os 2 últimos anos da Graduação.',
                'required' => false,
            ))
            ->add('areaTematica', EntityType::class, [
                'label' => 'Área de atuação',
                'class' => AreaTematica::class,
                'query_builder' => function (AreaTematicaRepository $repository) use ($projeto) {
                    return $repository->createQueryBuilder('at')
                        ->innerJoin('at.tipoAreaTematica', 'tat')
                        ->where('at.stRegistroAtivo = :stAtivo')
                        ->andWhere('at.projeto = :projeto')
                        ->orderBy('tat.dsTipoAreaTematica')
                        ->setParameters([
                            'stAtivo' => 'S',
                            'projeto' => $projeto,
                        ]);
                },
                'choice_label' => function (AreaTematica $areaTematica) {
                    return implode(' - ', [
                        $areaTematica->getCoSeqAreaTematica(),
                        $areaTematica->getTipoAreaTematica()->getDsTipoAreaTematica(),
                    ]);
                },
                'required' => true,
                'multiple' => true,
            ]);
    }

    /**
     * @param FormBuilderInterface $builder
     * @param Projeto|null $projeto
     */
    private function buildAreaAtuacao(FormBuilderInterface $builder, Projeto $projeto = null)
    {
        if (!$projeto || !$projeto->getPublicacao()->getPrograma()->isAreaAtuacao()) {
            return;
        }

        $formMofifierProjeto = function (FormInterface $form, Projeto $projeto = null) {

            if ($areasTematicas = null === $projeto) {
                $areasTematicas = [];
            } else {
                $list = [];
                $areasTematicas = [];

                foreach ($projeto->getAreasTematicasAtivas() as $areaTematica) {
                    foreach ($areaTematica->getAreasTematicasGruposAtuacaoAtivas() as $areaTematicaGrupoAtuacao) {
                        $coSeqGrupoAtuacao = $areaTematicaGrupoAtuacao->getGrupoAtuacao()->getCoSeqGrupoAtuacao();
                        if (in_array($coSeqGrupoAtuacao, $list)) {
                            $areasTematicas[$coSeqGrupoAtuacao] .= ', ' . $areaTematicaGrupoAtuacao->getAreaTematica()->getTipoAreaTematica()->getDsTipoAreaTematica();
                        } else {
                            $areasTematicas[$coSeqGrupoAtuacao] = $coSeqGrupoAtuacao . ' - ' . $areaTematicaGrupoAtuacao->getAreaTematica()->getTipoAreaTematica()->getDsTipoAreaTematica();
                        }
                        $list[] = $coSeqGrupoAtuacao;
                    }
                }

                $areasTematicas = array_flip($areasTematicas);
            }

            $form->add('areaTematica', ChoiceType::class, array(
                'label' => 'Grupos de atuação',
                'choices' => $areasTematicas,
                'multiple' => true,
                'required' => false
            ));
        };

        $builder->add('cursosLecionados', EntityType::class, array(
            'label' => 'Curso Lecionado',
            'class' => 'AppBundle:CursoGraduacao',
            'query_builder' => function (CursoGraduacaoRepository $repo) {
                return $repo->createQueryBuilder('cg')
                    ->where('cg.stRegistroAtivo = \'S\'')
                    ->orderBy('cg.dsCursoGraduacao', 'ASC');
            },
            'choice_label' => function ($cg) {
                return $cg->getDsCursoGraduacao();
            },
            'multiple' => true,
            'expanded' => true,
            'required' => false
        ));

        $builder->addEventListener(
            FormEvents::PRE_SET_DATA,
            function (FormEvent $event) use ($formMofifierProjeto, $projeto) {
                $data = $event->getData();
                $formMofifierProjeto($event->getForm(), $data->getProjeto() ?: $projeto);
            }
        );

        $builder->get('nuSei')->addEventListener(
            FormEvents::POST_SUBMIT,
            function (FormEvent $event) use ($formMofifierProjeto, $projeto) {
                // $projeto = $event->getForm()->getData();
                $formMofifierProjeto($event->getForm()->getParent(), $projeto);
            }
        );
    }

}