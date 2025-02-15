<?php

namespace App\Controller;

use App\CommandBus\CadastrarPlanejamentoAberturaFolhaPagamentoCommand;
use App\CommandBus\InativarPlanejamentoAberturaFolhaPagamentoCommand;
use App\Entity\PlanejamentoAnoFolha;
use App\Entity\Publicacao;
use App\Exception\PlanejamentoAberturaFolhaNotExistsException;
use App\Form\ConsultarPlanejamentoAberturaFolhaPagamentoType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\ParameterBag;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * @Security("is_granted(['ADMINISTRADOR','COORDENADOR_PROJETO','COORDENADOR_GRUPO'])")
 */
final class PlanejamentoAberturaFolhaPagamentoController extends ControllerAbstract
{
    /**
     *
     * @param Request $request
     * @return Response
     *
     * @Route("planejamento-abertura-folha", name="planejamento_abertura_folha")
     * @Method({"GET"})
     */
    public function index(Request $request)
    {
        $pagination = null;
        $form = $this->createForm(ConsultarPlanejamentoAberturaFolhaPagamentoType::class, null, [
            'projeto' => $this->getProjetoAutenticado(),
        ]);
        $form->handleRequest($request);
        
        if ($form->isSubmitted() && $form->isValid()) {
            $dataForm = $request->query->get('consultar_planejamento_abertura_folha_pagamento');
            $pagination = $this->get('app.planejamento_abertura_folha_query')
                ->search(new ParameterBag($dataForm));
        }
        
        return $this->render('planejamento_abertura_folha_pagamento/index.html.twig', [
            'form' => $form->createView(),
            'pagination' => $pagination,
        ]);
    }
    
    /**
     *
     * @param Request $request
     * @return Response
     *
     * @Security("is_granted('ADMINISTRADOR')")
     * @Route("planejamento-abertura-folha/create", name="planejamento_abertura_folha_create")
     * @Method({"GET", "POST"})
     */
    public function create(Request $request)
    {
        $errors = null;
        $command = new CadastrarPlanejamentoAberturaFolhaPagamentoCommand();
        
        if ($request->isMethod('POST')) {
            try {
                $command->handleRequest($request);
                $errors = $this->get('validator')->validate($command);
                
                $this->getBus()->handle($command);
                $this->addFlash('success', 'Inclusão realizada com sucesso!');
                return $this->redirectToRoute('planejamento_abertura_folha');
            } catch (\Exception $e) {
                $this->addFlashValidationError();
            }
        }
        
        return $this->render('planejamento_abertura_folha_pagamento/create.html.twig', [
            'command' => $command,
            'publicacoes' => $this->get('app.publicacao_query')->findAllAtivos(),
            'errors' => $errors,
        ]);
    }
    
    /**
     *
     * @param PlanejamentoAnoFolha $planejamentoAnoFolha
     * @param Request $request
     * @return Response
     *
     * @Security("is_granted('ADMINISTRADOR')")
     * @Route("planejamento-abertura-folha/edit/{id}", name="planejamento_abertura_folha_edit")
     * @Method({"GET", "POST"})
     */
    public function edit(PlanejamentoAnoFolha $planejamentoAnoFolha, Request $request)
    {
        $errors = null;
        $command = new CadastrarPlanejamentoAberturaFolhaPagamentoCommand();
        $command->setPlanejamentoAnoFolha($planejamentoAnoFolha);
        
        if ($request->isMethod('POST')) {
            try {
                $command->handleRequest($request);
                $errors = $this->get('validator')->validate($command);
                
                $this->getBus()->handle($command);
                $this->addFlash('success', 'Alteração realizada com sucesso!');
            } catch (\Exception $e) {
                $this->addFlashValidationError();
            }
        }
        
        return $this->render('planejamento_abertura_folha_pagamento/create.html.twig', [
            'command' => $command,
            'errors' => $errors,
        ]);
    }
    
    /**
     *
     * @param PlanejamentoAnoFolha $planejamentoAnoFolha
     * @return RedirectResponse
     *
     * @Security("is_granted('ADMINISTRADOR')")
     * @Route("planejamento-abertura-folha/delete/{id}", name="planejamento_abertura_folha_delete", options={"expose"=true})
     * @Method({"GET"})
     */
    public function delete(PlanejamentoAnoFolha $planejamentoAnoFolha)
    {
        try {
            $command = new InativarPlanejamentoAberturaFolhaPagamentoCommand($planejamentoAnoFolha);
            $this->getBus()->handle($command);
            $this->addFlash('success', 'Exclusão realizada com sucesso!');
        } catch (\Exception $e) {
            $this->addFlash('danger', 'Ocorreu um erro na execução.');
        }
        
        return $this->redirectToRoute('planejamento_abertura_folha');
    }
    
    /**
     *
     * @param Publicacao $publicacao
     * @return JsonResponse
     *
     * @Route("planejamento-abertura-folha/get-planejamento/{id}", name="planejamento_abertura_folha_get_planejamento", options={"expose"=true})
     */
    public function getLastPlanejamento(Publicacao $publicacao)
    {
        $now = new \DateTime();
        $response = new \stdClass();
        $response->status = true;
                
        try {
            $planejamentoAnoFolha = $this
                ->get('app.planejamento_abertura_folha_query')
                ->getLastPlanejamentoByPublicacao($publicacao->getCoSeqPublicacao());
            
            $response->nuAnoReferencia = $planejamentoAnoFolha->getNextAno();
            $response->data = $planejamentoAnoFolha
                ->getPlanejamentoMesesFolhaAtivos()
                ->map(function ($planejamentoMesFolha) {
                    return $planejamentoMesFolha->toArray();
                })->toArray();
        } catch (PlanejamentoAberturaFolhaNotExistsException $p) {
            $response->nuAnoReferencia = $now->format('Y');
        } catch (\Exception $ex) {
            $response->status = false;
        }
        
        $response->dtAtual = [
            'dia' => $now->format('d'),
            'mes' => $now->format('m'),
            'ano' => $now->format('Y'),
        ];
        
        return new JsonResponse($response);
    }
    
    /**
     *
     * @param PlanejamentoAnoFolha $planejamentoAnoFolha
     * @return Response
     *
     * @Route("planejamento-abertura-folha/detail/{id}", name="planejamento_abertura_folha_detail", options={"expose"=true})
     */
    public function detailPlanejamentoAnoFolha(PlanejamentoAnoFolha $planejamentoAnoFolha)
    {
        return $this->render('planejamento_abertura_folha_pagamento/detail.html.twig', [
            'planejamentoAnoFolha' => $planejamentoAnoFolha,
        ]);
    }
}
