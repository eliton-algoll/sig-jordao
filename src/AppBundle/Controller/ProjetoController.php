<?php

namespace AppBundle\Controller;

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
}
