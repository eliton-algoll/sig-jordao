<?php

namespace AppBundle\Controller;

use AppBundle\CommandBus\FiltroRelatorioPagamentoCommand;
use AppBundle\CommandBus\FiltroRelatorioProjetoCommand;
use AppBundle\Entity\AutorizacaoFolha;
use AppBundle\Entity\SituacaoFolha;
use AppBundle\Form\FiltroRelatorioDetalhadoFolhaPagamentoType;
use AppBundle\Form\FiltroRelatorioFolhaPagamento;
use AppBundle\Form\FiltroRelatorioFolhaPagamentoType;
use AppBundle\Form\FiltroRelatorioGerencialPagamentoType;
use AppBundle\Form\FiltroRelatorioGerencialParticipanteType;
use AppBundle\Form\FiltroRelatorioPagamentoNaoAutorizadoType;
use AppBundle\Form\FiltroRelatorioPagamentoType;
use AppBundle\Form\FiltroRelatorioParticipanteType;
use AppBundle\Form\FiltroRelatorioProjetoType;
use AppBundle\Report\GerencialPagamentoFilter;
use Doctrine\ORM\Query;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Component\HttpFoundation\ParameterBag;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class RelatorioController extends ControllerAbstract
{
    use \AppBundle\Traits\DownloadResponseTrait;

    /**
     * @Route("relatorio/pagamento", name="relatorio_pagamento")
     * @param Request $request
     */
    public function pagamentoAction(Request $request)
    {
        $queryParams = $request->request->get('filtro_relatorio_pagamento', array());
        $queryParams['order-by'] = $request->request->get('order-by', null);
        $queryParams['sort'] = $request->request->get('sort', null);

        $pb = new ParameterBag($queryParams);

        $command = new FiltroRelatorioPagamentoCommand();

        $form = $this->createForm(FiltroRelatorioPagamentoType::class, $command);

        $form->handleRequest($request);
        $results = [];
        if ($form->isSubmitted()) {
            $results = $this->get('app.vw_folha_pagamento_query')
                ->searchRelatorioPagamento($pb);
        }

        if (!is_null($request->request->get('flag-download', null))) {
//            $view = 'relatorio/pagamento_autorizado.html.twig';
//            if($pb->getInt('stProjetoFolha') == SituacaoProjetoFolha::HOMOLOGADA) {
//                $view = 'relatorio/pagamento_homologado.html.twig';
//            }
            $view = 'relatorio/pagamento.html.twig';

            return $this->responseXls(
                'relatorio_pagamento_bolsa',
                $this->renderView($view, array(
                    'results' => $results,
                    'queryParams' => $queryParams,
                ))
            );
        }

        return $this->render('relatorio/filtros_pagamento.html.twig', array(
            'results' => $results,
            'queryParams' => $queryParams,
            'form' => $form->createView(),
        ));
    }

    /**
     * @Route("relatorio/projeto", name="relatorio_projeto")
     * @param Request $request
     */
    public function projetoAction(Request $request)
    {
        $queryParams = $request->request->get('filtro_relatorio_projeto');
        $queryParams['order-by'] = $request->request->get('order-by', null);
        $queryParams['sort'] = $request->request->get('sort', null);

        $command = new FiltroRelatorioProjetoCommand();

        $form = $this->createForm(FiltroRelatorioProjetoType::class, $command);

        $form->handleRequest($request);

        $projetos = [];
        if ($form->isSubmitted()) {
            $projetos = $this->get('app.projeto_query')
                ->searchRelatorioProjeto(new ParameterBag($queryParams));
        }

        if (!is_null($request->request->get('flag-download', null))) {
            return $this->responseXls(
                'relatorio_projeto',
                $this->renderView('relatorio/projetos.html.twig', array(
                    'projetos' => $projetos,
                    'queryParams' => $queryParams,
                ))
            );
        }

        return $this->render('relatorio/filtros_projeto.html.twig', array(
            'projetos' => $projetos,
            'queryParams' => $queryParams,
            'form' => $form->createView(),
        ));
    }

    /**
     * @Route("relatorio/participante", name="relatorio_participante")
     * @param Request $request
     */
    public function participanteAction(Request $request)
    {
        $queryParams = $request->request->get('filtro_relatorio_participante');
        $queryParams['order-by'] = $request->request->get('order-by', null);
        $queryParams['sort'] = $request->request->get('sort', null);

        $form = $this->createForm(FiltroRelatorioParticipanteType::class);

        $form->handleRequest($request);

        $participantes = [];
        if ($form->isSubmitted() && $form->isValid()) {
            $participantes = $this->get('app.participante_query')
                ->searchRelatorioParticipante(new ParameterBag($queryParams));
        }

        if (!is_null($request->request->get('flag-download', null))) {
            return $this->responseXls(
                'relatorio_participante',
                $this->renderView('relatorio/participantes.html.twig', array(
                    'participantes' => $participantes,
                    'queryParams' => $queryParams,
                ))
            );
        }

        return $this->render('relatorio/filtros_participante.html.twig', array(
            'participantes' => $participantes,
            'queryParams' => $queryParams,
            'form' => $form->createView(),
        ));
    }

    /**
     * @Route("relatorio/pagamento-nao-autorizado", name="relatorio_pagamento_nao_autorizado")
     * @param Request $request
     * @return Response
     */
    public function pagamentoNaoAutorizado(Request $request)
    {
        $queryParams = $request->request->get('filtro_relatorio_pagamento_nao_autorizado');
        $queryParams['order-by'] = $request->request->get('order-by', null);
        $queryParams['sort'] = $request->request->get('sort', null);

        $command = new \AppBundle\CommandBus\FiltroRelatorioPagamentoNaoAutorizadoCommand;

        $form = $this->createForm(FiltroRelatorioPagamentoNaoAutorizadoType::class, $command);

        $form->handleRequest($request);

        $participantes = [];
        if ($form->isSubmitted() && $form->isValid()) {
            $participantes = $this->get('app.folha_pagamento_query')
                ->searchRelatorioPagamentoNaoAutorizado(new ParameterBag($queryParams));
        }

        if (!is_null($request->request->get('flag-download', null))) {
            return $this->responseXls(
                'relatorio_pagamento_nao_autorizado',
                $this->renderView('relatorio/pagamento_nao_autorizado.html.twig', array(
                    'participantes' => $participantes,
                    'queryParams' => $queryParams,
                ))
            );
        }

        return $this->render('relatorio/filtros_pagamento_nao_autorizado.html.twig', array(
            'participantes' => $participantes,
            'queryParams' => $queryParams,
            'form' => $form->createView(),
        ));
    }

    /**
     * @Security("is_granted(['ADMINISTRADOR', 'COORDENADOR_PROJETO'])")
     * @Route("relatorio/gerencial-participante", name="relatorio_gerencial_participante")
     * @Method({"GET", "POST"})
     */
    public function gerencialParticipante(Request $request)
    {
        $report = null;
        $formData = $request->request->get('filtro_relatorio_gerencial_participante');
        $form = $this->createForm(FiltroRelatorioGerencialParticipanteType::class, null, [
            'projeto' => $this->getProjetoAutenticado(),
            'filtro_relatorio_gerencial_participante' => $formData,
        ]);
        $form->handleRequest($request);

        if ($request->isMethod('POST') && $form->isValid()) {
            $pb = new ParameterBag($formData);
            $pb->set('projeto', ($this->getProjetoAutenticado()) ? $this->getProjetoAutenticado()->getCoSeqProjeto() : null);
            $report = $this->get('app.participante_query')
                ->searchRelatorioGerencial($pb);

            if ($request->request->has('stDownload')) {
                return $this->responseXls(
                    'relatorio_gerencial_participante',
                    $this->renderView('relatorio/gerencial_participante.html.twig', array(
                        'report' => $report,
                    ))
                );
            }
        }

        return $this->render('relatorio/filtros_gerencial_participante.html.twig', [
            'form' => $form->createView(),
            'report' => $report,
            'projeto' => $this->getProjetoAutenticado(),
        ]);
    }

    /**
     *
     * @param Request $request
     *
     * @Route("relatorio/gerencial-pagamento", name="relatorio_gerencial_pagamento")
     * @Method({"GET", "POST"})
     * @Security("is_granted(['ADMINISTRADOR'])")
     */
    public function gerencialPagamento(Request $request)
    {
        $report = null;
        $filter = new GerencialPagamentoFilter();
        $formData = $request->request->get('filtro_relatorio_gerencial_pagamento');
        $form = $this->createForm(FiltroRelatorioGerencialPagamentoType::class, $filter, [
            'filtro_relatorio_gerencial_pagamento' => $formData,
        ]);
        $form->handleRequest($request);

        if ($request->isMethod('POST') && $form->isValid()) {
            $report = $this->getDoctrine()->getManager()->getRepository('AppBundle:VwGerencialPagamento')->searchRelatorio($filter);

            if ($request->request->has('stDownload')) {
                return $this->responseXls(
                    'relatorio_gerencial_pagamento',
                    $this->renderView('relatorio/gerencial_pagamento.html.twig', array(
                        'report' => $report,
                    ))
                );
            }
        }

        return $this->render('relatorio/filtros_gerencial_pagamento.html.twig', [
            'form' => $form->createView(),
            'report' => $report,
        ]);
    }

    /**
     * @Route("relatorio/pagamento-detalhado", name="relatorio_pagamento_detalhado")
     * @Security("is_granted('ADMINISTRADOR')")
     *
     * @param Request $request
     * @return Response
     */
    public function pagamentoDetalhadoAction(Request $request)
    {
        $form = $this->createForm(
            FiltroRelatorioDetalhadoFolhaPagamentoType::class,
            $request->request->all(),
            ['attr' => ['target' => '_blank']]
        );
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $request->request->add($request->request->get('filtro_relatorio_detalhado_folha_pagamento'));
            $report = $this->get('app.folha_pagamento_detalhado_report')->getReport($request->request);

            if ($report) {
                return $this->render('relatorio/pagamento_detalhado.html.twig', [
                    'report' => $report,
                ]);
            } else {
                return new Response('Nenhum registro encontrado.');
            }
        }

        return $this->render('relatorio/filtros_pagamento_detalhado.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route(
     *     "relatorio/folha-pagamento",
     *     name="relatorio_folha_pagamento"
     * )
     * @Security("is_granted('ADMINISTRADOR')")
     * @Method({"GET", "POST"})
     *
     * @param Request $request
     * @return Response
     * @throws \Exception
     */
    public function folhaPagamentoAction(Request $request)
    {
        $report = $error = null;
        $form = $this->createForm(
            FiltroRelatorioFolhaPagamentoType::class, [],
            $request->request->all()
        );
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $pb = new ParameterBag($request->request->get('filtro_relatorio_folha_pagamento'));
            $pb->set('sort', 'pes.noPessoa');
            $pb->set('direction', 'ASC');
            $pb->set('situacaoFolha', [
                SituacaoFolha::HOMOLOGADA,
                SituacaoFolha::ENVIADA_FUNDO,
                SituacaoFolha::ORDEM_BANCARIA_EMITIDA,
            ]);

            $em = $this->getDoctrine()->getManager();
            $report = $em->getRepository(AutorizacaoFolha::class)->search($pb, Query::HYDRATE_ARRAY);

            if ($report) {
                $html = $this->renderView('relatorio/folha_pagamento.html.twig', ['report' => $report]);
                switch ($pb->get('tpOutput')) {
                    case 'xls' :
                        return $this->responseXls('relatorio-retorno-cadastro', $html);
                    case 'pdf' :
                        {
                            $now = new \DateTime();
                            $pdFacade = $this->get('app.wkhtmltopdf_facade');
                            $pdFacade
                                ->setOption('orientation', 'Landscape')
                                ->setOption('header-right', "Página [page] de [toPage]")
                                ->setOption('margin-top', 30)
                                ->setOption('header-spacing', 15)
                                ->setOption('footer-right', 'Relatório emitido em ' . $now->format('d/m/Y H:i:s'))
                                ->setOption('footer-left', 'Responsável: ' . $this->getUser()->getUsername());

                            return $pdFacade->generate($html, 'relatorio-retorno-cadastro' . date('_d_m_Y'));
                        }
                }
            } else {
                $error = 'Nenhum resultado encontrado para os filtros informados.';
            }
        }

        return $this->render(
            'relatorio/filtro_folha_pagamento.html.twig', ['form' => $form->createView(), 'error' => $error,]
        );
    }
}
