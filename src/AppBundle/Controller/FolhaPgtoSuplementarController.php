<?php

namespace AppBundle\Controller;

use AppBundle\Entity\AutorizacaoFolha;
use AppBundle\Entity\Projeto;
use AppBundle\Repository\FolhaPagamentoRepository;
use League\Tactician\Bundle\Middleware\InvalidCommandException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\ParameterBag;
use Symfony\Component\HttpFoundation\JsonResponse;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use AppBundle\Form\CadastarFolhaSuplementarType;
use AppBundle\Exception\FolhaSuplementarException;
use AppBundle\Entity\SituacaoFolha;
use AppBundle\Entity\Publicacao;
use AppBundle\Entity\FolhaPagamento;
use AppBundle\CommandBus\InativarAutorizacoesFolhaSuplementarCommand;
use AppBundle\CommandBus\CancelarFolhaSuplementarCommand;
use AppBundle\CommandBus\CadastarFolhaSuplementarCommand;

/**
 * @Security("is_granted('ADMINISTRADOR')")
 */
final class FolhaPgtoSuplementarController extends ControllerAbstract
{
    /**
     *
     * @param Request $request
     * @return Response
     *
     * @Route("folha-pgto-suplementar/create", name="folha_pgto_suplementar_create")
     */
    public function createAction(Request $request)
    {
        $command = new CadastarFolhaSuplementarCommand();
        $command->setPessoaPerfil($this->getPessoaPerfilAutenticado());

        $form = $this->createForm(CadastarFolhaSuplementarType::class, $command, [
            'data_submited' => $request->request->all(),
        ]);
        $form->handleRequest($request);

        if ($form->isSubmitted()) {
            try {
                $this->getBus()->handle($command);
                $this->addFlash('success', 'Autorização efetuada com sucesso.');
                return $this->redirectToRoute('folha_pagamento');
            } catch (FolhaSuplementarException $fse) {
                $this->addFlash('danger', $fse->getMessage());
            } catch (InvalidCommandException $e) {
                $this->addFlashValidationError();
            } catch (\Exception $e) {
                $this->addFlashExecutionError();
            }
        }

        return $this->render('folha_pgto_suplementar/gerar.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     *
     * @param FolhaPagamento $folhaPagamento
     * @param Request $request
     * @return Response
     *
     * @Route("folha-pgto-suplementar/edit/{folhaPagamento}", name="folha_pgto_suplementar_edit")
     */
    public function editAction(FolhaPagamento $folhaPagamento, Request $request)
    {
        if (!$folhaPagamento->isAberta() || $folhaPagamento->isMensal()) {
            throw new NotFoundHttpException();
        }

        $folhaPagamentoMensal = $this->get('app.folha_pagamento_repository')->getMensalBySuplementar($folhaPagamento);
        $enviosFns = $this->get('app.tramitacao_situacao_folha_repository')->findEnvioFnsReferenciaByFolha($folhaPagamento);

        $command = new CadastarFolhaSuplementarCommand();
        $command->setFolhaPagamento($folhaPagamentoMensal);
        $command->setFolhaPagamentoSuplementar($folhaPagamento);
        $command->setPessoaPerfil($this->getPessoaPerfilAutenticado());

        $form = $this->createForm(CadastarFolhaSuplementarType::class, $command, [
            'data_submited' => $request->request->all(),
            'publicacao' => $folhaPagamentoMensal->getPublicacao(),
            'edit' => true,
        ]);
        $form->handleRequest($request);

        if ($form->isSubmitted()) {
            try {
                $this->getBus()->handle($command);
                $this->addFlash('success', 'Autorização efetuada com sucesso.');

                if ($command->fechaFolha()) {
                    return $this->redirectToRoute('folha_pagamento');
                }
            } catch (FolhaSuplementarException $fse) {
                $this->addFlash('danger', $fse->getMessage());
            } catch (InvalidCommandException $e) {
                $this->addFlashValidationError();
            } catch (\Exception $e) {
                $this->addFlash('danger', $e->getMessage());
            }
        }

        return $this->render('folha_pgto_suplementar/gerar.html.twig', [
            'form' => $form->createView(),
            'enviosFns' => $enviosFns,
        ]);
    }

    /**
     *
     * @param FolhaPagamento $folhaPagamento
     * @param Request $request
     * @return JsonResponse
     *
     * @Route("folha-pgto-suplementar/add/{folhaPagamento}", name="folha_pgto_suplementar_add", options={"expose"=true})
     */
    public function addAction(FolhaPagamento $folhaPagamento, Request $request)
    {
        $response = new \stdClass();
        $response->status = true;

        try {
            $command = new CadastarFolhaSuplementarCommand();
            $command->setFolhaPagamento($folhaPagamento);
            $command->setPessoaPerfil($this->getPessoaPerfilAutenticado());
            $command->setFolhaPagamentoSuplementar($folhaPagamento);
            $command->setParticipantes($request->request->get('autorizacoes'));

            $this->getBus()->handle($command);
        } catch (\Exception $e) {
            $response->status = false;
            $response->error = $e->getMessage();
        }

        return new JsonResponse($response);
    }

    /**
     *
     * @param FolhaPagamento $folhaPagamento
     * @param Request $request
     * @return JsonResponse
     *
     * @Route("folha-pgto-suplementar/remove/{folhaPagamento}", name="folha_pgto_suplementar_remove", options={"expose"=true})
     */
    public function removeAction(FolhaPagamento $folhaPagamento, Request $request)
    {
        $response = new \stdClass();
        $response->status = true;

        try {
            $command = new InativarAutorizacoesFolhaSuplementarCommand(
                $folhaPagamento,
                (array)$request->request->get('autorizacoes')
            );

            $this->getBus()->handle($command);
        } catch (FolhaSuplementarException $fse) {
            $response->status = false;
            $response->error = $fse->getMessage();
        } catch (\Exception $e) {
            $response->status = false;
            $response->error = 'Ocorreu um erro na execução.';
        }

        return new JsonResponse($response);
    }

    /**
     *
     * @param FolhaPagamento $folhaPagamento
     * @param Request $request
     * @return RedirectResponse
     *
     * @Route("folha-pgto-suplementar/cancel/{folhaPagamento}", name="folha_pgto_suplementar_cancel", options={"expose"=true})
     */
    public function cancelAction(FolhaPagamento $folhaPagamento, Request $request)
    {
        if (!$folhaPagamento->isReadyToCancel()) {
            throw new NotFoundHttpException();
        }

        try {
            $command = new CancelarFolhaSuplementarCommand(
                $folhaPagamento,
                $request->query->get('dsJustificativa')
            );

            $this->getBus()->handle($command);
            $this->addFlash('success', 'Cancelamento realizado com sucesso!');
        } catch (\Exception $ex) {
            $this->addFlash('danger', 'Ocorreu um erro na execução.');
        }

        return $this->redirectToRoute('folha_pagamento');
    }

    /**
     *
     * @param Publicacao $publicacao
     * @return JsonResponse
     *
     * @Route("folha-pgto-suplementar/list-folhas-mensais/{publicacao}", name="folha_pgto_suplementar_list_folhas_mensais", options={"expose"=true})
     */
    public function listFolhasMensaisAction(Publicacao $publicacao)
    {
        $pb = new ParameterBag([
            'publicacao' => $publicacao,
            'tpFolha' => FolhaPagamento::MENSAL,
            'situacao' => [SituacaoFolha::ENVIADA_FUNDO, SituacaoFolha::ORDEM_BANCARIA_EMITIDA, SituacaoFolha::HOMOLOGADA],
            'order' => ['fp.nuAno' => 'DESC', 'fp.nuMes' => 'DESC'],
        ]);

        $folhas = $this
            ->get('app.folha_pagamento_repository')
            ->search($pb)
            ->getQuery()
            ->getResult();

        return new JsonResponse(array_map(function ($folhaPagamento) {
            return [
                'coSeqFolhaPagamento' => $folhaPagamento->getCoSeqFolhaPagamento(),
                'referencia' => ucfirst($folhaPagamento->getReferenciaExtenso()),
            ];
        }, $folhas));
    }

    /**
     * @param Projeto $projeto
     * @param Request $request
     * @return Response
     *
     * @Route(
     *     "folha-pgto-suplementar/grid-participantes",
     *     name="folha_pgto_suplementar_grid_participantes",
     *     options={"expose"=true}
     * )
     * @Method({"GET"})
     */
    public function gridParticipantesFolhaAction(Request $request)
    {
        $participantes = $this->get('app.folha_pagamento_repository')
            ->findParticipantesFolhaSuplementar($request->query);

        return $this->render(
            'folha_pgto_suplementar/grid-participantes-folha.html.twig', [
            'participantes' => $participantes,
        ]);
    }

    /**
     * @param FolhaPagamento $folhaPagamento
     * @param Request $request
     * @return Response
     *
     * @Route(
     *     "folha-pgto-suplementar/grid-autorizacoes/{folhaPagamento}",
     *     name="folha_pgto_suplementar_grid_autorizacoes",
     *     options={"expose"=true}
     * )
     * @Method({"GET"})
     */
    public function gridAutorizacoesFolhaAction(FolhaPagamento $folhaPagamento, Request $request)
    {
        $request->request->set('folhaPagamento', $folhaPagamento);

        $autorizacoes = $this->get('app.autorizacao_folha_repository')
            ->listByParameters($request->request);

        return $this->render(
            'folha_pgto_suplementar/grid-participantes-folha.html.twig', [
            'autorizacoes' => $autorizacoes,
        ]);
    }

    /**
     * @param Publicacao $publicacao
     * @param int $ano
     * @param int $mes
     * @return JsonResponse
     *
     * @Route(
     *     "folha-pgto-suplementar/list-by-ano-mes/{publicacao}/{ano}/{mes}",
     *     name="folha_pgto_suplementar_list_by_ano_mes",
     *     options={"expose"=true}
     * )
     * @Method({"GET"})
     */
    public function listHomologadasAction(Publicacao $publicacao, $ano, $mes)
    {
        $em = $this->getDoctrine()->getManager();

        $folhasPagamento = $em->getRepository('AppBundle:FolhaPagamento')->search(
            new ParameterBag([
                'publicacao' => $publicacao,
                'tpFolha' => FolhaPagamento::SUPLEMENTAR,
                'situacao' => [
                    SituacaoFolha::HOMOLOGADA,
                    SituacaoFolha::ENVIADA_FUNDO,
                    SituacaoFolha::ORDEM_BANCARIA_EMITIDA,
                ],
                'nuAno' => $ano,
                'nuMes' => str_pad($mes, 2, '0', STR_PAD_LEFT),
                'order' => 'fp.dtInclusao',
                'direction' => 'DESC',
            ])
        )->getQuery()->getResult();

        return new JsonResponse(
            array_map(function (FolhaPagamento $folhaPagamento) {
                return [
                    'coSeqFolhaPagamento' => $folhaPagamento->getCoSeqFolhaPagamento(),
                    'dtInclusao' => $folhaPagamento->getDtInclusao()->format('d/m/Y'),
                ];
            }, $folhasPagamento)
        );
    }
}
