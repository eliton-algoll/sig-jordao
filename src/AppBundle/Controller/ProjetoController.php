<?php

namespace AppBundle\Controller;

use AppBundle\Entity\CursoGraduacao;
use AppBundle\Entity\DadoAcademico;
use AppBundle\Entity\GrupoAtuacao;
use AppBundle\Entity\ProjetoPessoa;
use AppBundle\Entity\ProjetoPessoaCursoGraduacao;
use AppBundle\Entity\Publicacao;
use AppBundle\Repository\ProjetoRepository;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\ParameterBag;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use League\Tactician\Bundle\Middleware\InvalidCommandException;
use AppBundle\Form\PesquisarCampusType;
use AppBundle\Form\CadastrarProjetoType;
use AppBundle\Form\AtualizarProjetoType;
use AppBundle\Form\PesquisarProjetoType;
use AppBundle\Form\PesquisarSecretariaType;
use AppBundle\CommandBus\CadastrarProjetoCommand;
use AppBundle\CommandBus\AtualizarProjetoCommand;
use AppBundle\Exception\SiparInvalidoException;
use AppBundle\Entity\Projeto;
use AppBundle\Entity\Instituicao;
use AppBundle\Entity\Municipio;
use AppBundle\Entity\CategoriaProfissional;

/**
 * @Security("is_granted('ADMINISTRADOR')")
 */
class ProjetoController extends ControllerAbstract
{

    /**
     * @Route("/projeto", name="projeto")
     * @Method({"GET"})
     */
    public function allAction(Request $request)
    {
        $queryParams = $request->query->get('pesquisar_projeto', array());
        $queryParams['page'] = $request->query->get('page', 1);

        $pagination = $this
            ->get('app.projeto_query')
            ->search(
                new ParameterBag($queryParams)
            );

        $form = $this->createForm(PesquisarProjetoType::class);

        return $this->render(
            '/projeto/all.html.twig',
            array(
                'pagination' => $pagination,
                'queryParams' => $queryParams,
                'form' => $form->createView()
            )
        );
    }

    /**
     * @Route("/projeto/cadastrar", name="projeto_cadastrar")
     */
    public function cadastrarAction(Request $request)
    {
        $command = new CadastrarProjetoCommand();

        $form = $this->get('form.factory')->createNamed('cadastrar_projeto', CadastrarProjetoType::class, $command);
        $form->handleRequest($request);

        if ($form->isSubmitted()) {

            $data = new ParameterBag($request->request->get('cadastrar_projeto'));

            $areasTematicasSaude = [];
            if ( !is_null($data->get('areasTematicasSaude')) ) {
                $areasTematicasSaude = $data->get('areasTematicasSaude');
            }

            $areasTematicasCienciasHumanas = [];
            if ( !is_null($data->get('areasTematicasCienciasHumanas')) ) {
                $areasTematicasCienciasHumanas = $data->get('areasTematicasCienciasHumanas');
            }

            $areasTematicasCienciasSociais = [];
            if ( !is_null($data->get('areasTematicasCienciasSociais')) ) {
                $areasTematicasCienciasSociais = $data->get('areasTematicasCienciasSociais');
            }


            $areasTematicas = array_merge($areasTematicasSaude, $areasTematicasCienciasHumanas);
            $areasTematicas = array_merge($areasTematicas, $areasTematicasCienciasSociais);

            # bind manual devido a complexidade do formulário
            $command
                ->setNuSipar($data->get('nuSipar'))
                ->setDsObservacao($data->get('dsObservacao'))
                ->setStOrientadorServico($data->get('stOrientadorServico'))
                ->setPublicacao($data->get('publicacao'))
                ->setAreasTematicas($areasTematicas)
                ->setCampus($data->get('campus'))
                ->setSecretarias($data->get('secretarias'));
            try {
                $this->getBus()->handle($command);
                $this->addFlash('success', 'Projeto cadastrado com sucesso');
                return $this->redirectToRoute('projeto');

            } catch (InvalidCommandException $e) {
                $this->addFlashValidationError();
            }
        }

        return $this->render(
            'projeto/cadastrar.html.twig',
            array(
                'form' => $form->createView(),
                'formPesquisarCampus' => $this->createForm(PesquisarCampusType::class)->createView(),
                'formPesquisarSecretaria' => $this->createForm(PesquisarSecretariaType::class)->createView(),
            )
        );
    }

    /**
     * @Route("/projeto/atualizar/{projeto}", name="projeto_atualizar")
     */
    public function atualizarAction(Request $request, Projeto $projeto)
    {

        $nrGrupoProjeto = $this->get('app.projeto_query')->countNrGruposByProjeto($projeto->getCoSeqProjeto());
        $command = new AtualizarProjetoCommand($projeto);
        $command->setQtGrupos($nrGrupoProjeto['NRGRUPOS']);

        $form = $this->get('form.factory')->createNamed('cadastrar_projeto', AtualizarProjetoType::class, $command);
        $form->handleRequest($request);

        if ($form->isSubmitted()) {

            $data = new ParameterBag($request->request->get('cadastrar_projeto'));

            $areasTematicasSaude = [];
            if ( !is_null($data->get('areasTematicasSaude')) ) {
                $areasTematicasSaude = $data->get('areasTematicasSaude');
            }

            $areasTematicasCienciasHumanas = [];
            if ( !is_null($data->get('areasTematicasCienciasHumanas')) ) {
                $areasTematicasCienciasHumanas = $data->get('areasTematicasCienciasHumanas');
            }

            $areasTematicasCienciasSociais = [];
            if ( !is_null($data->get('areasTematicasCienciasSociais')) ) {
                $areasTematicasCienciasSociais = $data->get('areasTematicasCienciasSociais');
            }


            $areasTematicas = array_merge($areasTematicasSaude, $areasTematicasCienciasHumanas);
            $areasTematicas = array_merge($areasTematicas, $areasTematicasCienciasSociais);

            # bind manual devido a complexidade do formulário
            $command
                ->setCoSeqProjeto($data->get('coSeqProjeto'))
                ->setNuSipar($data->get('nuSipar'))
                ->setDsObservacao($data->get('dsObservacao'))
                ->setPublicacao($data->get('publicacao'))
                ->setAreasTematicas($areasTematicas)
                ->setCampus($data->get('campus'))
                ->setQtGrupos($data->get('qtGrupos'))
                ->setNrGruposInicio($nrGrupoProjeto['NRGRUPOS'])
                ->setSecretarias($data->get('secretarias'));

            try {
                $this->getBus()->handle($command);
                $this->addFlash('success', 'Projeto atualizado com sucesso.');
                return $this->redirectToRoute('projeto');

            } catch (InvalidCommandException $e) {

                $command
                    ->setCampus($projeto->getCampus())
                    ->setSecretarias($projeto->getSecretarias());

                $this->addFlashValidationError();
            }
        }

        return $this->render(
            'projeto/cadastrar.html.twig',
            array(
                'title' => 'Atualizar Projeto',
                'form' => $form->createView(),
                'formPesquisarCampus' => $this->createForm(PesquisarCampusType::class)->createView(),
                'formPesquisarSecretaria' => $this->createForm(PesquisarSecretariaType::class)->createView(),
            )
        );
    }

    /**
     * @Route("/projeto/instituicoes/{municipio}", name="projeto_get_instituicoes_by_municipio", options={"expose"=true})
     */
    public function getInstituicoesByMunicipio(Request $request, Municipio $municipio)
    {
        return new JsonResponse($this->get('app.instituicao_query')->listInstituicoesByMunicipio($municipio));
    }

    /**
     * @Route("/projeto/campus/{instituicao}", name="projeto_get_campus_by_instituicao", options={"expose"=true})
     */
    public function getCampusByInstituicao(Request $request, Instituicao $instituicao)
    {
        return new JsonResponse($this->get('app.instituicao_query')->listCampusByInstituicao($instituicao));
    }

    /**
     * @Route("/projeto/secretarias/{municipio}", name="projeto_get_secretarias_by_municipio", options={"expose"=true})
     */
    public function getSecretariasByMunicipio(Request $request, Municipio $municipio)
    {
        return new JsonResponse($this->get('app.pessoa_juridica_query')->listSecretariasSaudeByMunicipio($municipio));
    }

    /**
     * @Route("/projeto/secretaria/{cnpj}", name="projeto_get_secretaria_by_cnpj", options={"expose"=true}, requirements={"cnpj"=".+"})
     */
    public function getSecretariaByCnpj(Request $request, $cnpj)
    {
        try {
            $return = array(
                'result' => $this->get('app.pessoa_juridica_query')->listSecretariaSaudeByCnpj($cnpj),
                'status' => true
            );

        } catch (\Exception $e) {
            $return = array(
                'message' => $e->getMessage(),
                'status' => false
            );
        }

        return new JsonResponse($return);
    }

    /**
     * @Security("is_granted('COORDENADOR_PROJETO')")
     * @Route("/projeto/quantidade-de-perfil-por-grupo-de-atuacao/{coProjeto}", name="projeto_qtd_perfil_grupo_atuacao", options={"expose"= true}, requirements={"coProjeto"="\d+"})
     * @param Request $request
     * @param integer $coProjeto código do projeto
     * @return JsonResponse Description
     */
    public function quantidadeDePerfisPorGrupoDeAtuacao(Request $request, $coProjeto)
    {
        return new JsonResponse($this->get('app.vw_grupoatuacao_qtprofissional_query')->quantidadeDePerfisPorGrupoDeAtuacao($coProjeto));
    }

    /**
     * @Route("projeto/check-sipar-exists", name="projeto_check_sipar_existis", options={"expose"=true})
     * @Method({"GET"})
     *
     * @return JsonResponse
     */
    public function checkSiparExistis(Request $request)
    {
        $response = new \stdClass();
        $response->exists = false;

        try {
            $this->get('app.projeto_query')->getBySipar($request->query->get('nuSipar'));
            $response->exists = true;
        } catch (SiparInvalidoException $sie) {
            $response->msg = $sie->getMessage();
        }

        return new JsonResponse($response);
    }

    /**
     * @param Publicacao $publicacao
     * @return JsonResponse
     *
     * @Route("projeto/list-by-publicacao/{publicacao}", name="projeto_list_by_publicacao", options={"expose"=true})
     */
    public function listByPublicacao(Publicacao $publicacao)
    {
        $pb = new ParameterBag([
            'publicacao' => $publicacao->getCoSeqPublicacao(),
        ]);

        $result = $this->get('app.projeto_repository')->search($pb)
            ->select('p')
            ->andWhere('p.stRegistroAtivo = \'S\'')
            ->getQuery()
            ->getArrayResult();

        return new JsonResponse($result);
    }

    /**
     * @param Request $request
     * @return JsonResponse
     *
     * @Route("projeto/get-by-sipar", name="projeto_get_by_sipar", options={"expose"=true})
     */
    public function getBySiparAction(Request $request)
    {
        $response = new \stdClass();
        $response->status = true;

        $em = $this->getDoctrine()->getManager();

        try {
            $projeto = $em->getRepository(Projeto::class)->getBySipar(
                $request->query->get('nuSipar'),
                $request->query->get('publicacao'),
                $request->query->get('ignoreVigencia') ? false : true
            );

            $response->projeto = $projeto->toArray();
        } catch (SiparInvalidoException $e) {
            $response->status = false;
            $response->error = $e->getMessage();
        }

        return new JsonResponse($response);
    }

    /**
     * @param Request $request
     * @return JsonResponse
     *
     * @Security("is_granted('COORDENADOR_PROJETO')")
     * @Route("projeto/get-grupo-tutorial-details", name="projeto_get_grupo_tutorial_details", options={"expose"=true})
     * @Method({"GET"})
     */
    public function getByGrupoTutorialDetails(Request $request)
    {
        $response = new \stdClass();
        $response->status = true;

        $em = $this->getDoctrine()->getManager();

        try {
            $projeto = $em->getRepository(Projeto::class)->getBySipar($request->query->get('nuSipar'),
                null, false);

            if ((!is_null($projeto)) && ($projeto->getPublicacao()->getPrograma()->isGrupoTutorial())) {

                // Obtem o Grupo de Atuacao
                $gruposAtuacao = $em->getRepository(GrupoAtuacao::class)
                    ->findByProjetoAndId($projeto->getCoSeqProjeto(), $request->query->get('grupoTutorial'));

                $totalGrupos = count($gruposAtuacao);
                $grupoAtuacaoEncontrado = null;

                foreach ($gruposAtuacao as $grupoAtuacao) {
                    if ($grupoAtuacao->getCoSeqGrupoAtuacao() == $request->query->get('grupoTutorial')) {
                        $grupoAtuacaoEncontrado = $grupoAtuacao;
                        break;
                    }
                }

                $eixoAtuacao = null;

                if (!is_null($grupoAtuacaoEncontrado)) {
                    $eixoAtuacao = $grupoAtuacaoEncontrado->getCoEixoAtuacao();
                }

                // Obtém os preceptores obrigatórios
                $preceptores = $em->getRepository(ProjetoPessoa::class)->search(new ParameterBag(array(
                    'projeto' => $projeto,
                    'grupoTutorial' => $grupoAtuacaoEncontrado,
                    'coPerfil' => 4, // Preceptor
                    'stRegistroAtivo' => 'S'
                )))->getQuery()->getResult();

                $categoriasProfissionais_ = $em->getRepository(CategoriaProfissional::class)
                    ->findBy(['stRegistroAtivo' => 'S']);


                $cursoGraduacaoCandidato = $em->getRepository(CursoGraduacao::class)->find($request->query->get('cursoGraduacaoCandidato'));

                $categoriasSaude = [];
                $categoriasCienciasHumanas = [];
                $categoriasCienciasSociais  = [];
                foreach ($categoriasProfissionais_ as $categ) {
                    switch ($categ->getTpAreaFormacao()) {
                        case '1':
                            $categoriasSaude[] = $categ;
                            break;
                        case '2':
                            $categoriasCienciasHumanas[] = $categ;
                            break;
                        case '3':
                            $categoriasCienciasSociais[] = $categ;
                            break;
                    }
                }

                $categoriasProfissionais = [];
                $cursosGraduacao = [];
                $cursosGraduacaoSaude = [];

                $estudantes = $em->getRepository(ProjetoPessoa::class)->search(new ParameterBag(array(
                    'projeto' => $projeto,
                    'grupoTutorial' => $grupoAtuacaoEncontrado,
                    'coPerfil' => 6, // Estudante
                    'stRegistroAtivo' => 'S'
                )))->getQuery()->getResult();

                $participantes = $em->getRepository(ProjetoPessoa::class)->search(new ParameterBag(array(
                    'projeto' => $projeto,
                    'grupoTutorial' => $grupoAtuacaoEncontrado,
                    'stRegistroAtivo' => 'S'
                )))->getQuery()->getResult();

                // Remove o estudante da listagem (pois já está cadastrado)
                $cpfEnviado = $request->query->get('cpf');
                $cpfEnviado = preg_replace('/\D/', '', $cpfEnviado);

                // O estudante precisa se remover da lista
                for ($i = count($estudantes) - 1; $i > -1; $i--) {
                    if ($estudantes[$i]['nuCpfCnpjPessoa'] == $cpfEnviado) {
                        array_splice($estudantes, $i, 1);
                    }
                }

                $estudantesEncontradosGrupo = count($estudantes);

                $preceptoresIds = [];
                if(count($participantes)==0){
                    $eixoAtuacao = null;
                }

                for ($i = count($preceptores) - 1; $i > -1; $i--) {
                    if ($preceptores[$i]['nuCpfCnpjPessoa'] == $cpfEnviado) {
                        array_splice($preceptores, $i, 1);
                    }
                }

                $cursoCandidatoSaude = false;
                $estudantesSaudeEncontradosGrupo = [];
                $estudantesCienciasHumanasEncontradosGrupo = [];
                $estudantesCienciasSociaisEncontradosGrupo = [];
                if( count($preceptores) ) {
                    foreach ($estudantes as $estudante) {
                        $cursoGraduacaoEstudante = $em->getRepository(ProjetoPessoaCursoGraduacao::class)->findOneBy(array(
                            'projetoPessoa' => $estudante['coSeqProjetoPessoa'],
                            'stRegistroAtivo' => 'S'
                        ));

                        if (!is_null($cursoGraduacaoEstudante)) {
                            foreach ($categoriasSaude as $cat) {
                                if( $cursoGraduacaoEstudante->getCursoGraduacao()->getDsCursoGraduacao() == $cat->getDsCategoriaProfissional() ) {
                                    $estudantesSaudeEncontradosGrupo[] = $cursoGraduacaoEstudante->getCursoGraduacao()->getCoSeqCursoGraduacao();
                                }

                                if( $cursoGraduacaoCandidato->getDsCursoGraduacao() == $cat->getDsCategoriaProfissional() ) {
                                    $cursoCandidatoSaude = true;
                                }
                            }

                            foreach ($categoriasCienciasHumanas as $cat) {
                                if( $cursoGraduacaoEstudante->getCursoGraduacao()->getDsCursoGraduacao() == $cat->getDsCategoriaProfissional() ) {
                                    $estudantesCienciasHumanasEncontradosGrupo[] = $cursoGraduacaoEstudante->getCursoGraduacao()->getCoSeqCursoGraduacao();
                                }
                            }

                            foreach ($categoriasCienciasSociais as $cat) {
                                if( $cursoGraduacaoEstudante->getCursoGraduacao()->getDsCursoGraduacao() == $cat->getDsCategoriaProfissional() ) {
                                    $estudantesCienciasSociaisEncontradosGrupo[] = $cursoGraduacaoEstudante->getCursoGraduacao()->getCoSeqCursoGraduacao();
                                }
                            }
                        }
                    }
                }

                // Obtém o eixo de atuação
                // Obtém as categorias
                // Obtém os cursos de graduação
                foreach ($preceptores as $preceptor) {
                    $preceptoresIds[] = $preceptor['coSeqProjetoPessoa'];

                    if ((is_null($eixoAtuacao)) && (!is_null($preceptor['coEixoAtuacao']))) {
                        $eixoAtuacao = $preceptor['coEixoAtuacao'];
                    }

                    $dadoAcademico = $em->getRepository(DadoAcademico::class)->findOneBy(array(
                        'projetoPessoa' => $preceptor['coSeqProjetoPessoa'],
                        'stRegistroAtivo' => 'S'
                    ));

                    if (!is_null($dadoAcademico)) {
                        array_push($categoriasProfissionais,
                            $dadoAcademico->getCategoriaProfissional()->getCoSeqCategoriaProfissional());
                    }
                    $cursoGraduacao = $em->getRepository(ProjetoPessoaCursoGraduacao::class)->findOneBy(array(
                        'projetoPessoa' => $preceptor['coSeqProjetoPessoa'],
                        'stRegistroAtivo' => 'S'
                    ));
                }

                $eixosOriginais = ['A','B','C'];
                $eixosDisponiveis = [];
                if( !$eixoAtuacao ) {
                    if( $totalGrupos < 3 ) {
                        $eixosDisponiveis = $eixosOriginais;
                    }

                    if( $totalGrupos > 2 ) {
                        $nrGruposProjetoComParticipantes = $this->get('app.projeto_query')->getNrGruposComParticpantesPorProjeto($projeto->getCoSeqProjeto());
                        if( count($nrGruposProjetoComParticipantes) && $totalGrupos > 2 ) {
                            //Para projetos com 5 grupos.
                            if( $totalGrupos == 3 ) {
                                foreach ($nrGruposProjetoComParticipantes as $eixosEncontrados) {
                                    $eixosDisponiveis[] = $eixosEncontrados['CO_EIXO_ATUACAO'];
                                }
                            }

                            //Dados para validar projeto com 4 e 5 grupos.
                            $nrGruposProjetoComParticipantes = $this->get('app.projeto_query')->getEixosComParticpantes($projeto->getCoSeqProjeto());
                            $eixoA = 0;
                            $eixoB = 0;
                            $eixoC = 0;
                            foreach ($nrGruposProjetoComParticipantes as $eixosEncontrados) {
                                if(  $eixosEncontrados['CO_EIXO_ATUACAO'] == 'A' ) {
                                    $eixoA++;
                                }

                                if(  $eixosEncontrados['CO_EIXO_ATUACAO'] == 'B' ) {
                                    $eixoB++;
                                }

                                if(  $eixosEncontrados['CO_EIXO_ATUACAO'] == 'C' ) {
                                    $eixoC++;
                                }
                            }
                            //Para projetos com 4 grupos.
                            if( $totalGrupos == 4 ) {

                                if( $eixoA > 1 ) {
                                    $eixosDisponiveis[] = 'A';
                                }

                                if( $eixoB > 1 ) {
                                    $eixosDisponiveis[] = 'B';
                                }

                                if( $eixoC > 1 ) {
                                    $eixosDisponiveis[] = 'C';
                                }
                            }
                            //Para projetos com 5 grupos.
                            if( $totalGrupos == 5 ) {

                                if( $eixoA > 2 ) {
                                    $eixosDisponiveis[] = 'A';
                                    if( $eixoB == 2 ) {
                                        $eixosDisponiveis[] = 'B';
                                    }
                                    if( $eixoC == 2 ) {
                                        $eixosDisponiveis[] = 'C';
                                    }
                                }

                                if( $eixoB > 2 ) {
                                    $eixosDisponiveis[] = 'B';
                                    if( $eixoA == 2 ) {
                                        $eixosDisponiveis[] = 'A';
                                    }
                                    if( $eixoC == 2 ) {
                                        $eixosDisponiveis[] = 'C';
                                    }
                                }

                                if( $eixoC > 2 ) {
                                    $eixosDisponiveis[] = 'C';
                                    if( $eixoA == 2 ) {
                                        $eixosDisponiveis[] = 'A';
                                    }
                                    if( $eixoB == 2 ) {
                                        $eixosDisponiveis[] = 'B';
                                    }
                                }
                            }
                        }
                    }
                }

                if(count($participantes) == '0') {
                    $eixosDisponiveis = [];
                }

                $response->details = [
                    'eixoAtuacao' => $eixoAtuacao,
                    'temDoisPreceptores' => (count($preceptores) >= 2),
                    'categoriasProfissionais' => $categoriasProfissionais,
                    'cursosGraduacao' => $cursosGraduacao,
                    'cursosGraduacaoSaude' => $cursosGraduacaoSaude,
                    'estudantesEncontrados' => $estudantesEncontradosGrupo,
                    'estudantesCursoSaude' => array_values(array_unique($estudantesSaudeEncontradosGrupo)),
                    'estudantesSaude' => count($estudantesSaudeEncontradosGrupo),
                    'estudantesCienciasSociaisEncontrados' => count($estudantesCienciasSociaisEncontradosGrupo),
                    'estudantesCienciasHumanas' => count($estudantesCienciasHumanasEncontradosGrupo),
                    'cursoCandidatoSaude' => $cursoCandidatoSaude,
                    'preceptores' => $preceptoresIds,
                    'eixosPermitidos' => $eixosDisponiveis
                ];
            } else {
                $response->details = [
                    'eixoAtuacao' => null,
                    'temDoisPreceptores' => true,
                    'categoriasProfissionais' => [],
                    'cursosGraduacao' => [],
                    'preceptores' => [],
                ];
            }
        } catch (SiparInvalidoException $e) {
            $response->status = false;
            $response->error = $e->getMessage();
        }

        return new JsonResponse($response);
    }
}
