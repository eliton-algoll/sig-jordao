<?php

namespace AppBundle\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\ParameterBag;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use League\Tactician\Bundle\Middleware\InvalidCommandException;
use AppBundle\CommandBus\RecepcionarArquivoRetornoCadastroCommand;
use AppBundle\Form\RecepcionarArquivoRetornoCadastroType;
use AppBundle\Form\FiltroRelatorioRetornoCriacaoContaType;
use AppBundle\Form\ConsultarRetornosCadastroType;
use AppBundle\Exception\UnexpectedCommandBehaviorException;
use AppBundle\Entity\RetornoCriacaoConta;
use AppBundle\Cpb\DicionarioCpb;

/**
 * @Security("is_granted('ADMINISTRADOR')")
 */
final class ArquivoRetornoCadastroController extends ControllerAbstract
{
    use \AppBundle\Traits\DownloadResponseTrait;
    
    /**
     * 
     * @param Request $request
     * @return Response
     * 
     * @Route("arquivo-retorno-cadastro", name="arquivo_retorno_cadastro", options={"expose"=true})
     * @Method({"GET"})
     */
    public function index(Request $request)
    {
        $form = $this->createForm(ConsultarRetornosCadastroType::class);
        $form->handleRequest($request);

        $pagination = null;
        
        if ($form->isSubmitted()) {
            $request->query->add($request->query->get('consultar_retornos_cadastro'));
            $pagination = $this->get('app.retorno_cadastro_query')->search($request->query);
        }
        
        return $this->render('arquivo_retorno_cadastro/index.html.twig', [
            'form' => $form->createView(),
            'pagination' => $pagination,
        ]);
    }
    
    /**
     * 
     * @param Request $request
     * @return Response|RedirectResponse
     * 
     * @Route("arquivo-retorno-cadastro/recepcionar-arquivo", name="arquivo_retorno_cadastro_recepcionar_arquivo")
     * @Method({"GET", "POST"})
     */
    public function recepcionarArquivo(Request $request)
    {
        $command = new RecepcionarArquivoRetornoCadastroCommand();
        
        $form = $this->createForm(RecepcionarArquivoRetornoCadastroType::class, $command);
        $form->handleRequest($request);
        
        if ($form->isSubmitted()) {
            try {
                $this->getBus()->handle($command);
                $retornoCriacaoConta = $this->get('app.retorno_cadastro_query')->getLast();
                
                $btns = [
                    'Sim' => '<a href="'. $this->generateUrl('arquivo_retorno_cadastro_report', [ 'retornoCriacaoConta' => $retornoCriacaoConta->getCoSeqRetornoCriacaoConta() ]) .'">Sim</a>',
                    'Não' => '<a class="cursor-pointer" data-dismiss="alert" aria-label="Close">Não</a>',
                ];
                
                $this->addFlash('success', 'Arquivo de retorno processado com sucesso. Quantidade de participantes recepcionados: '. $retornoCriacaoConta->getQtParticipante() .'. Deseja imprimir o relatório completo do retorno recepcionado? ' . implode(' ', $btns));
                
                return $this->redirectToRoute('arquivo_retorno_cadastro');
            } catch(UnexpectedCommandBehaviorException $ub) {
                $this->addFlash('danger', $ub->getMessage());
            } catch (InvalidCommandException $ie) {
                $this->addFlashValidationError();                
            }  catch (\Exception $e) {
                $this->addFlashExecutionError();                
            }
        }
        
        return $this->render('arquivo_retorno_cadastro/recepcionar-arquivo-retorno.html.twig', [
            'form' => $form->createView(),
        ]);
    }
    
    /**
     * 
     * @param RetornoCriacaoConta $retornoCriacaoConta
     * @return RedirectResponse
     * 
     * @Route("arquivo-retorno-cadastro/delete/{retornoCriacaoConta}", name="arquivo_retorno_cadastro_delete", options={"expose"=true})
     * @Method({"GET"})
     */
    public function delete(RetornoCriacaoConta $retornoCriacaoConta)
    {
        try {
            $retornoCriacaoConta->inativar();
            
            $em = $this->getDoctrine()->getManager();
            $em->persist($retornoCriacaoConta);
            $em->flush();
            
            $this->addFlash('success', 'Exclusão realizada com sucesso!');
        } catch (\Exception $e) {
            $this->addFlashExecutionError();
        }
        
        return $this->redirectToRoute('arquivo_retorno_cadastro');
    }
    
    /**
     * 
     * @param RetornoCriacaoConta $retornoCriacaoConta
     * @param Request $request
     * @return Response
     * 
     * @Route("arquivo-retorno-cadastro/report/{retornoCriacaoConta}", name="arquivo_retorno_cadastro_report")
     * @Method({"GET"})
     */
    public function report(RetornoCriacaoConta $retornoCriacaoConta, Request $request)
    {
        $form = $this->createForm(FiltroRelatorioRetornoCriacaoContaType::class, null, [
            'retornoCriacaoConta' => $retornoCriacaoConta,            
        ]);
        $form->handleRequest($request);
        
        if ($form->isSubmitted() && $form->isValid()) {            
            $pb = new ParameterBag($request->query->get('filtro_relatorio_retorno_criacao_conta', []));
            $tpRelatorio = FiltroRelatorioRetornoCriacaoContaType::getDescricaoTipoRelatorio($pb->get('tpRelatorio'));
            
            $now = new \DateTime();
            $report = $this->get('app.retorno_cadastro_query')->report($retornoCriacaoConta, $pb);            
            $html = $this->renderView('arquivo_retorno_cadastro/result-report.html.twig', [
                'report' => $report,
                'tpRelatorio' => $tpRelatorio,
                'stCadastro' => DicionarioCpb::getValueDescricao('SIT-CMD', $pb->get('stCadastro')),
                'formatRelatorio' => $pb->get('formatRelatorio'),
            ]);
            
            switch ($pb->get('formatRelatorio'))
            {
                case 'xls' : return $this->responseXls('relatorio-retorno-cadastro', $html);
                case 'pdf' : {                    
                    $pdFacade = $this->get('app.wkhtmltopdf_facade');
                    $pdFacade
                        ->setOption('orientation', 'Landscape')
                        ->setOption('header-right', "Página [page] de [toPage]")
                        ->setOption('margin-top', 30)
                        ->setOption('header-spacing', 15)
                        ->setOption('footer-right', 'Relatório emitido em ' . $now->format('d/m/Y H:i:s'))
                        ->setOption('footer-left', 'Responsável: ' . $this->getUser()->getUsername())
                        ->setOption('header-center', $this->renderView('arquivo_retorno_cadastro/header-report.txt.twig', [
                            'report' => $report,
                            'tpRelatorio' => $tpRelatorio,
                        ]));
                    
                    return $pdFacade->generate($html, 'relatorio-retorno-cadastro'.date('_d_m_Y'));
                }
                default : return new Response($html);
            }
        }
        
        return $this->render('arquivo_retorno_cadastro/report.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
