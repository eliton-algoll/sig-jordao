<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\ParameterBag;
use Symfony\Component\HttpFoundation\JsonResponse;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use League\Tactician\Bundle\Middleware\InvalidCommandException;
use App\Form\RecepcionarArquivoRetornoPagamentoType;
use App\Form\FiltroRelatorioRetornoPagamentoType;
use App\Form\ConsultarRetonosPagamentoType;
use App\Exception\UnexpectedCommandBehaviorException;
use App\Entity\RetornoPagamento;
use App\Entity\Publicacao;
use App\Entity\FolhaPagamento;
use App\CommandBus\RecepcionarArquivoRetornoPagamentoCommand;
use App\CommandBus\InativarRetornoPagamentoCommand;
use App\CommandBus\FinalizaFolhaPagamentoCommand;

/**
 * @Security("is_granted('ADMINISTRADOR')")
 */
final class ArquivoRetornoPagamentoController extends ControllerAbstract
{
    use \App\Traits\DownloadResponseTrait;
    
    /**
     * 
     * @param Request $request
     * @return Response
     * 
     * @Route("arquivo-retorno-pagamento", name="arquivo_retorno_pagamento", options={"expose"=true})
     * @Method({"GET"})
     */
    public function index(Request $request)
    {
        $formData = $request->query->get('consultar_retonos_pagamento');
        
        $form = $this->createForm(ConsultarRetonosPagamentoType::class, null, [
            'publicacao' => isset($formData['publicacao']) ? $formData['publicacao'] : null,
        ]);
        $form->handleRequest($request);
        
        $pagination = null;
        
        if ($form->isSubmitted()) {
            $request->query->add($formData);
            $pagination = $this->get('app.retorno_pagamento_query')->searchAll($request->query);
        }
        
        return $this->render('arquivo_retorno_pagamento/index.html.twig', [
            'form' => $form->createView(),
            'pagination' => $pagination,
        ]);
    }
    
    /**
     * @Route("arquivo-retorno-pagamento/recepcionar-arquivo", name="arquivo_retorno_pagamento_recepcionar_arquivo")
     * @Method({"GET", "POST"})
     */
    public function recepcionar(Request $request)
    {
        $formData = $request->request->get('recepcionar_arquivo_retorno_pagamento');
        
        $command = new RecepcionarArquivoRetornoPagamentoCommand();
        $form = $this->createForm(RecepcionarArquivoRetornoPagamentoType::class, $command, [
            'publicacao' => isset($formData['publicacao']) ? $formData['publicacao'] : null,
            'tpFolhaPagamento' => isset($formData['tpFolhaPagamento']) ? $formData['tpFolhaPagamento'] : FolhaPagamento::MENSAL,
        ]);
        $form->handleRequest($request);
        
        if ($form->isSubmitted()) {
            try {
                $this->getBus()->handle($command);
                return $this->forward('App:ArquivoRetornoPagamento:finalizarFolha', [ 'folhaPagamento' => $command->getFolhaPagamento() ]);
            } catch(UnexpectedCommandBehaviorException $ucb) {
                $this->addFlash('danger', $ucb->getMessage());
            }catch (InvalidCommandException $ie) {
                $this->addFlashValidationError();
            } catch (\Exception $e) {                
                $this->addFlashExecutionError();
            }
        }
        
        return $this->render('arquivo_retorno_pagamento/recepcionar-arquivo-retorno.html.twig', [
            'form' => $form->createView(),
        ]);
    }
    
    /**
     * 
     * @param FolhaPagamento $folhaPagamento
     * 
     * @Route("arquivo-retorno-pagamento/finaliza-folha/{folhaPagamento}", name="arquivo_retorno_pagamento_finaliza_folha")
     * @Method({"GET"})
     */
    public function finalizarFolhaAction(FolhaPagamento $folhaPagamento)
    {
        $command = new FinalizaFolhaPagamentoCommand($folhaPagamento);
        
        try {
            $this->getBus()->handle($command);
            
            $retornoPagamento = $folhaPagamento->getRetornosPagamentosAtivos()->last();
        
            $btns = [
                'Sim' => '<a href="'. $this->generateUrl('arquivo_retorno_pagamento_report', ['retornoPagamento' => $retornoPagamento->getCoSeqRetornoPagamento()]) .'">Sim</a>',
                'Não' => '<a class="cursor-pointer" data-dismiss="alert" aria-label="Close">Não</a>',
            ];

            $this->addFlash('success', 'Arquivo de retorno processado com sucesso. Quantidade de participantes recepcionados: '. $retornoPagamento->getQtParticipante() .'. Deseja imprimir o relatório completo do retorno recepcionado? ' . implode(' ', $btns));
        } catch (\Exception $ex) {}        
        
        return $this->redirectToRoute('arquivo_retorno_pagamento');
    }
    
    /**
     * 
     * @param RetornoPagamento $retornoPagamento
     * 
     * @Route("arquivo-retorno-pagamento/delete/{retornoPagamento}", name="arquivo_retorno_pagamento_delete", options={"expose"=true})
     * @Method({"GET"})
     */
    public function delete(RetornoPagamento $retornoPagamento)
    {
        $command = new InativarRetornoPagamentoCommand($retornoPagamento);
        
        try {
            $this->getBus()->handle($command);
            $this->addFlash('success', 'Exclusão realizada com sucesso!');
        } catch (\Exception $ex) {
            $this->addFlashExecutionError();
        }
        
        return $this->redirectToRoute('arquivo_retorno_pagamento');
    }
    
    /**
     * 
     * @param RetornoPagamento $retornoPagamento
     * @param Request $request
     * @return Response
     * 
     * @Route("arquivo-retorno-pagamento/report/{retornoPagamento}", name="arquivo_retorno_pagamento_report")
     * @Method({"GET"})
     */
    public function report(RetornoPagamento $retornoPagamento, Request $request)
    {
        $form = $this->createForm(FiltroRelatorioRetornoPagamentoType::class, null, [
            'retornoPagamento' => $retornoPagamento,            
        ]);
        $form->handleRequest($request);
        
        if ($form->isSubmitted() && $form->isValid()) {
            $pb = new ParameterBag($request->query->get('filtro_relatorio_retorno_pagamento', []));
            $tpRelatorio = FiltroRelatorioRetornoPagamentoType::getDescricaoTipoRelatorio($pb->get('tpRelatorio'));
            
            $now = new \DateTime();
            $report = $this->get('app.retorno_pagamento_query')->report($retornoPagamento, $pb);            
            $html = $this->renderView('arquivo_retorno_pagamento/result-report.html.twig', [
                'report' => $report,
                'tpRelatorio' => $tpRelatorio,                
                'formatRelatorio' => $pb->get('formatRelatorio'),
            ]);
            
            switch ($pb->get('formatRelatorio'))
            {
                case 'xls' : return $this->responseXls('relatorio-retorno-pagamento', $html);
                case 'pdf' : {
                    $pdfFacade = $this->get('app.wkhtmltopdf_facade');
                    $pdfFacade
                        ->setOption('orientation', 'Landscape')
                        ->setOption('header-right', "Página [page] de [toPage]")
                        ->setOption('margin-top', 30)
                        ->setOption('header-spacing', 15)
                        ->setOption('footer-right', 'Relatório emitido em ' . $now->format('d/m/Y H:i:s'))
                        ->setOption('footer-left', 'Responsável: ' . $this->getUser()->getUsername())
                        ->setOption('header-center', $this->renderView('arquivo_retorno_pagamento/header-report.txt.twig', [
                            'report' => $report,
                            'tpRelatorio' => $tpRelatorio,
                        ]));
                    
                    return $pdfFacade->generate($html, 'relatorio-retorno-pagamento'.date('_d_m_Y'));
                }
                default : return new Response($html);
            }
        }
        
        return $this->render('arquivo_retorno_pagamento/report.html.twig', [
            'form' => $form->createView(),
        ]);
    }
    
    /**
     * 
     * @param Publicacao $publicacao
     * @param Request $request
     * @return JsonResponse
     * 
     * @Route("arquivo-retorno-pagamento/load-folhas/{publicacao}", name="arquivo_retorno_pagamento_load_folhas", options={"expose"=true})
     * @Method({"GET"})
     */
    public function loadFolhasSemRecepcaoByPublicacao(Publicacao $publicacao, Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $result = $em
            ->getRepository(FolhaPagamento::class)
            ->findByPublicacaoAndTipo(
                $publicacao,
                $request->query->get('tpFolhaPagamento', FolhaPagamento::MENSAL)
            );
        
        $response = array_map(function ($folhaPagamento) {
            return [
                'coSeqFolhaPagamento' => $folhaPagamento->getCoSeqFolhaPagamento(),                
                'nuMesAnoSipar' => $folhaPagamento->getNuMesAno() . ' - ' . $folhaPagamento->getNuSipar(),
            ];
        }, array_filter($result, function ($folhaPagamento) {
            return ($folhaPagamento->isEnviadaFundo() || $folhaPagamento->isOrdemBancaria()) && !$folhaPagamento->isRetornoPagamento();
        }));
        
        return new JsonResponse($response);
    }
    
    /**
     * 
     * @param Publicacao $publicacao
     * @return JsonResponse
     * 
     * @Route("arquivo-retorno-pagamento/load-referencias/{publicacao}", name="arquivo_retorno_pagamento_load_referencias", options={"expose"=true})
     * @Method({"GET"})
     */
    public function loadReferenciasByPublicacao(Publicacao $publicacao)
    {
        $em = $this->getDoctrine()->getManager();
        $result = $em->getRepository(FolhaPagamento::class)
            ->findByPublicacao($publicacao);
        
        $response = array_map(function ($folhaPagamento) {
            return [
                'nuAno' => $folhaPagamento->getNuAno(),
                'nuMes' => $folhaPagamento->getNuMes(),
                'nuMesAno' => $folhaPagamento->getNuMesAno(),
            ];
        }, array_filter($result, function ($folhaPagamento) {
            return $folhaPagamento->isEnviadaFundo() || $folhaPagamento->isOrdemBancaria();
        }));
        
        return new JsonResponse(array_filter($response));
    }
}
