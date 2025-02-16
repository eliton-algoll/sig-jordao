<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\ParameterBag;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use League\Tactician\Bundle\Middleware\InvalidCommandException;
use App\Form\PesquisarFolhaPagamentoType;
use App\Form\AbrirFolhaPagamentoType;
use App\CommandBus\HomologarFolhaPagamentoCommand;
use App\CommandBus\AbrirFolhaPagamentoCommand;
use App\CommandBus\FecharFolhaPagamentoCommand;
use App\CommandBus\EnviarFolhaPagamentoParaFundoCommand;
use App\CommandBus\InformarOrdemBancariaCommand;
use App\Entity\Publicacao;
use App\Entity\ProjetoFolhaPagamento;
use App\Entity\FolhaPagamento;
use Doctrine\DBAL\Exception\UniqueConstraintViolationException;

/**
 * @Security("is_granted('ADMINISTRADOR')")
 */
class FolhaPagamentoController extends ControllerAbstract
{
    use \App\Traits\DownloadResponseTrait;
    /**
     * @Route("folha-pagamento", name="folha_pagamento", options={"expose"=true})
     * @return Response
     */
    public function allAction(Request $request)
    {
        $params = $request->query->get('pesquisar_folha_pagamento', array());
        $params['page'] = $request->query->get('page', 1);
        $params = new ParameterBag($params);
        
        $pagination = $this->get('app.folha_pagamento_query')->searchFolhaPagamento($params);

        $manager = $this->getDoctrine()->getManager();
        $publicacao = $params->get('publicacao') ? $manager->getReference('App:Publicacao', $params->get('publicacao')) : null;
        $situacao = $params->get('situacao') ? $manager->getReference('App:SituacaoFolha', $params->get('situacao')) : null;
                
        $form = $this->createForm(PesquisarFolhaPagamentoType::class, array(
            'publicacao' => $publicacao, 
            'situacao' => $situacao
        ));
        $form->handleRequest($request);

        return $this->render(
            'folha_pagamento/all.html.twig',
            array(
                'pagination' => $pagination,
                'form' => $form->createView()
            )
        );
    }
    
    /**
     * @Route("folha-pagamento/abrir", name="folha_pagamento_abrir")
     * @param Request $request
     * @return Response
     */
    public function abrirAction(Request $request)
    {
        $command = new AbrirFolhaPagamentoCommand();

        $form = $this->createForm(AbrirFolhaPagamentoType::class, $command, array());
        
        if ($request->isMethod('post')) {
            
            $data = new ParameterBag($request->request->get('abrir_folha_pagamento'));
            
            $command = new AbrirFolhaPagamentoCommand();
            $command->setPublicacao($data->get('publicacao'));
            $command->setNuMes($data->get('nuMes'));
            $command->setNuAno($data->get('nuAno'));
            $command->setPessoaPerfilAtor($this->getPessoaPerfilAutenticado());
            
            try {
                
                $this->getBus()->handle($command);
                $this->addFlash('success', 'Folha de pagamento aberta com sucesso.');
                return $this->redirectToRoute('folha_pagamento');
                
            } catch (InvalidCommandException $e) {
                $this->addFlashValidationError();
            } catch (\LogicException $e) {
                $this->addFlash('danger', $e->getMessage());
            } catch (UniqueConstraintViolationException $e) {
                $this->addFlash('danger', 'Não é possível abrir uma folha com a Publicação, Mês e Ano iguais a outra que já foi aberta anteriormente');
            }
        }
        
        return $this->render('folha_pagamento/abrir.html.twig', array('form' => $form->createView()));
    }
    
    /**
     * @Route("folha-pagamento/fechar/{folha}", name="folha_pagamento_fechar", options={"expose"=true})
     * @return Response
     */
    public function fecharAction(FolhaPagamento $folha)
    {
        $command = new FecharFolhaPagamentoCommand();
        $command->setFolhaPagamento($folha);
        $command->setPessoaPerfilAtor($this->getPessoaPerfilAutenticado());
        
        try {
            $this->getBus()->handle($command);
            
            $return = array(
                'status' => true,
                'result' => array('situacao' => $folha->getSituacao()->getDsSituacaoFolha()),
                'message' => 'Folha de pagamento fechada com sucesso'
            );
            
        } catch (\Exception $e) {
            
            $return = array(
                'status' => false,
                'message' => $e->getMessage()
            );
        }
        
        return new JsonResponse($return);
    }
    
    /**
     * @Route("folha-pagamento/homologar/{folha}", name="folha_pagamento_homologar")
     * @param Request $request
     * @return Response
     */
    public function homologarAction(Request $request, FolhaPagamento $folha)
    {
        if (!$folha->isFechada()) {
            $this->addFlash('danger', 'A Folha de Pagamento precisa estar fechada para ser homologada.');
            return $this->redirectToRoute('folha_pagamento');
        }
        
        if ($request->isMethod('post')) {


            $command = new HomologarFolhaPagamentoCommand();
            $command->setFolhasProjetos($request->request->get('projetoFolha'));
            $command->setCoFolhaPagamento($folha);
            $command->setPessoaPerfilAtor($this->getPessoaPerfilAutenticado());
            
            try {
                
                $this->getBus()->handle($command);
                return $this->forward('App:FolhaPagamento:gerarHistoricoParticipantes');


            } catch (InvalidCommandException $e) {
                $this->addFlashValidationError();
            }
        }
        
        return $this->render('folha_pagamento/homologar.html.twig', array(
            'folha' => $folha
        ));
    }

    /**
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function gerarHistoricoParticipantesAction(Request $request)
    {

        $projetoFolha = $request->request->get('projetoFolha');

        foreach($projetoFolha as $key => $folha ){
            $arFolhaPagamento[] = $key;
        }

        $projetoFolhaPagamento = $this->get('app.folha_pagamento_query')->findParticipantesFolha($arFolhaPagamento);


        $command = new \App\CommandBus\GerarHistoricoFolhaCommand($projetoFolhaPagamento);

        $this->getBus()->handle($command);

        $this->addFlash('success', 'Folhas de pagamento homologadas com sucesso.');
        return $this->redirectToRoute('folha_pagamento');
    }


    
    /**
     * @Route("folha-pagamento/enviar-para-fundo/{folha}", name="folha_pagamento_enviar_para_fundo", options={"expose"=true})
     * @param Request $request
     * @return Response
     */
    public function enviarParaFundoAction(Request $request, FolhaPagamento $folha)
    {
        if (!$folha->isHomologada()) {
            $this->addFlash('danger', 'A Folha de Pagamento precisa estar homologada para ser enviada para o Fundo Nacional de Saúde.');
            return $this->redirectToRoute('folha_pagamento');
        }
        
        $command = new EnviarFolhaPagamentoParaFundoCommand();
        $command->setFolhaPagamento($folha);
        $command->setNuSipar($request->query->get('nuSipar'));
        $command->setPessoaPerfilAtor($this->getPessoaPerfilAutenticado());
        
        try {
            $this->getBus()->handle($command);
            
            $return = array(
                'status' => true,
                'result' => array('situacao' => $folha->getSituacao()->getDsSituacaoFolha()),
                'message' => 'Folha de pagamento enviada para Fundo Nacional de Saúde'
            );
            
        } catch (InvalidCommandException $ex) {   
            $erros = array();
            
            if (method_exists($ex, 'getViolations')) {
                foreach ($ex->getViolations() as $violation) {
                    $erros[] = $violation->getMessage();
                }
            }
            
            $return = array(
                'status' => false,
                'message' => implode('</br>', $erros)
            );
        } catch (\Exception $e) {
            $return = array(
                'status' => false,
                'message' => $e->getMessage()
            );
        }
        
        return new JsonResponse($return);
    }
    
    /**
     * @Route("folha-pagamento/informar-ordem-bancaria/{folha}", name="folha_pagamento_informar_ordem_bancaria", options={"expose"=true})
     * @param Request $request
     * @return Response
     */
    public function InformarOrdemBancariaAction(Request $request, FolhaPagamento $folha)
    {
        if (!$folha->isEnviadaFundo()) {
            $this->addFlash('danger', 'A Folha de Pagamento precisa ter sido enviada para o Fundo Nacional de Saúde para que se possa informar a Ordem Bancária do pagamento.');
            return $this->redirectToRoute('folha_pagamento');
        }
        
        $command = new InformarOrdemBancariaCommand();
        $command->setFolhaPagamento($folha);
        $command->setNuOrdemBancaria($request->query->get('nuOrdemBancaria'));
        $command->setPessoaPerfilAtor($this->getPessoaPerfilAutenticado());
        
        try {
            $this->getBus()->handle($command);
            
            $return = array(
                'status' => true,
                'result' => array('situacao' => $folha->getSituacao()->getDsSituacaoFolha()),
                'message' => 'Folha de pagamento enviada para Fundo Nacional de Saúde'
            );
            
        } catch (InvalidCommandException $ex) {   
            $erros = array();
            
            if (method_exists($ex, 'getViolations')) {
                foreach ($ex->getViolations() as $violation) {
                    $erros[] = $violation->getMessage();
                }
            }
            
            $return = array(
                'status' => false,
                'message' => implode('</br>', $erros)
            );
        } catch (\Exception $e) {
            $return = array(
                'status' => false,
                'message' => $e->getMessage()
            );
        }
        
        return new JsonResponse($return);
    }
    
    /**
     * @Route("folha-pagamento/homologar/projeto-folha/{projetoFolha}", name="folha_pagamento_homologar_visualizar_projeto_folha")
     * @Method({"GET"})
     * @param ProjetoFolhaPagamento $projetoFolha
     * @return Response
     */
    public function visualizarProjetoFolhaAction(ProjetoFolhaPagamento $projetoFolha)
    {
        $grupos = $this->get('app.grupo_atuacao_query')->findByProjeto($projetoFolha->getProjeto());

        $em = $this->getDoctrine()->getManager();
        
        $autorizacaoFolhas = $em->getRepository('App:AutorizacaoFolha')->getByProjetoFolha($projetoFolha);
        $qtParticipantes = $em->getRepository('App:AutorizacaoFolha')->findQtParticipantesPorGrupoAtuacao($projetoFolha);
        
        return $this->render('folha_pagamento/visualizar_projeto_folha.html.twig', array(
            'projetoFolha' => $projetoFolha,
            'grupos'  => $grupos,
            'autorizacaoFolhas' => $autorizacaoFolhas,
            'qtParticipantes' => $qtParticipantes
        ));        
    }
    
    /**
     * @Route("folha-pagamento/download-xls/{folha}", name="folha_pagamento_download_xls")
     * @param FolhaPagamento $folha
     * @return Response
     */
    public function downloadXlsAction(FolhaPagamento $folha)
    {
        $params = array(
            'folha' => $folha,
            'autorizacoes' => $this->get('app.autorizacao_folha_query')->getAllByFolha($folha)
        );
        
        return $this->responseXls(
            'relatorio',
            $this->renderView('folha_pagamento/download_xls.html.twig', $params)
        );
    }
    
    /**
     * @Route("folha-pagamento/projetos-vinculados/{folha}", name="folha_pagamento_projetos_vinculados", options={"expose"=true})
     * @param FolhaPagamento $folha
     * @return Response
     */
    public function projetosVinculadosAction(FolhaPagamento $folha) 
    {   
        return $this->render('folha_pagamento/projetos_vinculados.html.twig', array(
            'projetosFolhaPagamento' => $folha->getProjetosAtivos(),
            'projetosPublicacao' => $folha->getPublicacao()->getProjetosAtivos()
        ));
    }
    
    /**
     * @Route("folha-pagamento/historico-tramitacao-status/{folha}", name="folha_pagamento_historico_tramitacao_status", options={"expose"=true})
     * @param FolhaPagamento $folha
     * @return Response
     */
    public function historicoTramitacaoStatusAction(FolhaPagamento $folha)
    {
        return $this->render('folha_pagamento/historico_tramitacao_status.html.twig', array(
           'arr_status' => $folha->getHistoricoTramitacaoFolha()
        ));
    }
    
    /**
     * 
     * @param Publicacao $publicacao
     * @param Request $request
     * @return JsonResponse
     * 
     * @Route("folha-pagamento/load-folhas-publicacao/{publicacao}", name="folha_pagamento_load_folhas_publicacao", options={"expose"=true})
     * @Method({"GET"})
     */
    public function loadFolhasByPublicacao(Publicacao $publicacao, Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $result = $em
            ->getRepository('App:FolhaPagamento')
            ->findByPublicacaoAndTipo(
                $publicacao,
                $request->query->get('tpFolhaPagamento', FolhaPagamento::MENSAL)
            );
        
        $response = array_map(function ($folhaPagamento) {
            return [
                'coSeqFolhaPagamento' => $folhaPagamento->getCoSeqFolhaPagamento(),
                'nuAno' => $folhaPagamento->getNuAno(),
                'nuMes' => $folhaPagamento->getNuMes(),
                'nuMesAno' => $folhaPagamento->getNuMesAno(),                
            ];
        }, $result);
        
        return new JsonResponse($response);
    }

    /**
     * @Route("folha-pagamento/list-by-params", name="folha_pagamento_list_by_params", options={"expose"=true})
     * 
     * @param Request $request
     * @return JsonResponse
     */
    public function listFolhasByParamAction(Request $request)
    {
        $folhas = $this
            ->get('app.folha_pagamento_repository')
            ->search($request->query)
            ->getQuery()
            ->getResult();
        
        return new JsonResponse(array_map(function ($folhaPagamento) {
            return [
                'coFolhaPagamento' => $folhaPagamento->getCoSeqFolhaPagamento(),
                'referencia' => ucfirst($folhaPagamento->getDescricaoCompletaFolha()),
            ];
        }, $folhas));
    }

}
