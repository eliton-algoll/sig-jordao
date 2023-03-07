<?php

namespace AppBundle\Controller;

use AppBundle\Entity\DadoAcademico;
use AppBundle\Entity\GrupoAtuacao;
use AppBundle\Entity\ProjetoPessoa;
use AppBundle\Entity\ProjetoPessoaCursoGraduacao;
use AppBundle\Entity\Publicacao;
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

            # bind manual devido a complexidade do formulário
            $command
                ->setNuSipar($data->get('nuSipar'))
                ->setDsObservacao($data->get('dsObservacao'))
                ->setPublicacao($data->get('publicacao'))
                ->setAreasTematicas($data->get('areasTematicas'))
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
        $command = new AtualizarProjetoCommand($projeto);

        $form = $this->get('form.factory')->createNamed('cadastrar_projeto', AtualizarProjetoType::class, $command);
        $form->handleRequest($request);

        if ($form->isSubmitted()) {

            $data = new ParameterBag($request->request->get('cadastrar_projeto'));

            # bind manual devido a complexidade do formulário
            $command
                ->setCoSeqProjeto($data->get('coSeqProjeto'))
                ->setNuSipar($data->get('nuSipar'))
                ->setDsObservacao($data->get('dsObservacao'))
                ->setPublicacao($data->get('publicacao'))
                ->setAreasTematicas($data->get('areasTematicas'))
                ->setCampus($data->get('campus'))
                ->setSecretarias($data->get('secretarias'));

            try {
                $this->getBus()->handle($command);
                $this->addFlash('success', 'Projeto atualizado com sucesso');
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

                $categoriasProfissionais = [];
                $cursosGraduacao = [];

                $estudantes = $em->getRepository(ProjetoPessoa::class)->search(new ParameterBag(array(
                    'projeto' => $projeto,
                    'grupoTutorial' => $grupoAtuacaoEncontrado,
                    'coPerfil' => 6, // Estudante
                    'stRegistroAtivo' => 'S'
                )))->getQuery()->getResult();

                for ($r = count($estudantes) - 1; $r > -1; $r--) {
                    if ($estudantes[$r]['stVoluntarioProjeto'] == 'S') {
                        array_splice($estudantes, $r, 1);
                    }
                }

                $participanrtes = $em->getRepository(ProjetoPessoa::class)->search(new ParameterBag(array(
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
                if(count($participanrtes)==0){
                    $eixoAtuacao = null;
                }

                // Obtém o eixo de atuação
                // Obtém as categorias
                // Obtém os cursos de graduação
                foreach ($preceptores as $preceptor) {
                    if($preceptor['stVoluntarioProjeto'] == 'N'){
                        $preceptoresIds[] = $preceptor['coSeqProjetoPessoa'];
                    }

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

                    if (!is_null($cursoGraduacao)) {
                        $coSeqCursoGraduacao = $cursoGraduacao->getCursoGraduacao()->getCoSeqCursoGraduacao();

                        if ($eixoAtuacao == 'A') { // Assistência à Saúde
                            $estudantesEncontrados = 0;

                            foreach ($estudantes as $estudante) {
                                $cursoGraduacaoEstudante = $em->getRepository(ProjetoPessoaCursoGraduacao::class)->findOneBy(array(
                                    'projetoPessoa' => $estudante['coSeqProjetoPessoa'],
                                    'stRegistroAtivo' => 'S'
                                ));

                                if (!is_null($cursoGraduacaoEstudante)) {
                                    if ($cursoGraduacaoEstudante->getCursoGraduacao()->getCoSeqCursoGraduacao() == $coSeqCursoGraduacao) {
                                        $estudantesEncontrados++;
                                    }
                                }
                            }

                            if ($estudantesEncontrados < 4) {
                                array_push($cursosGraduacao, $coSeqCursoGraduacao);
                            }
                        } else {
                            array_push($cursosGraduacao, $coSeqCursoGraduacao);
                        }
                    }
                }

                for ($i = count($preceptores) - 1; $i > -1; $i--) {
                    if ($preceptores[$i]['nuCpfCnpjPessoa'] == $cpfEnviado) {
                        array_splice($preceptores, $i, 1);
                    }
                }

                for ($r = count($preceptores) - 1; $r > -1; $r--) {
                    if ($preceptores[$r]['stVoluntarioProjeto'] == 'S') {
                        array_splice($preceptores, $r, 1);
                    }
                }

                if($request->query->get('voluntario') == 'S'){
                    $preceptoresIds = [];
                }

                $response->details = [
                    'eixoAtuacao' => $eixoAtuacao,
                    'temDoisPreceptores' => (count($preceptores) >= 2),
                    'categoriasProfissionais' => $categoriasProfissionais,
                    'cursosGraduacao' => $cursosGraduacao,
                    'estudantesEncontrados' => $estudantesEncontradosGrupo,
                    'preceptores' => $preceptoresIds
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
