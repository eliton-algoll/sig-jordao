<?php

namespace App\Controller;

use App\CommandBus\AnalisarRetornoFormularioAvaliacaoAtividadeCommand;
use App\CommandBus\AtualizarEnvioFormularioAvaliacaoAtividadeCommand;
use App\CommandBus\CadastrarEnvioFormularioAvaliacaoAtividadeCommand;
use App\CommandBus\CadastrarRetornoFormularioAvaliacaoAtividadeCommand;
use App\CommandBus\InativarEnvioFormularioAvaliacaoAtividadeCommand;
use App\CommandBus\InativarTramitacaoFormularioCommand;
use App\Entity\EnvioFormularioAvaliacaoAtividade;
use App\Entity\FormularioAvaliacaoAtividade;
use App\Entity\Projeto;
use App\Entity\TramitacaoFormulario;
use App\Form\AnalisarRetornoFormularioAvaliacaoAtividadeType;
use App\Form\AtualizarEnvioFormularioAvaliacaoAtividadeType;
use App\Form\CadastrarEnvioFormularioAvaliacaoAtividadeType;
use App\Form\CadastrarRetornoFormularioAvaliacaoAtividadeType;
use App\Form\ConsultarEnvioFormulariosAvaliacaoAtividadeType;
use App\Form\ConsultarFormulariosAvaliacaoAtividadeParticipanteType;
use League\Tactician\Bundle\Middleware\InvalidCommandException;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

final class TramitaFormularioAtividadeController extends ControllerAbstract
{
    /**
     * 
     * @param Request $request
     * 
     * @Route("tramita-formulario-atividade", name="tramita_formulario_atividade")
     * @Method({"GET"})
     * @Security("is_granted('ADMINISTRADOR')")
     */
    public function index(Request $request)
    {
        $paginationEnvios = null;
        $paginationRetornos = null;        
        $form = $this->createForm(ConsultarEnvioFormulariosAvaliacaoAtividadeType::class);
        $form->handleRequest($request);
        
        if ($form->isSubmitted() && $form->isValid()) {
            $formData = $request->query->get('consultar_envio_formularios_avaliacao_atividade', []);
            $request->query->add($formData);
            if ($formData['tpTramiteFormulario'] == 'E') {
                $paginationEnvios = $this
                    ->get('app.avalicao_atividade_query')
                    ->searchEnvios($request->query);
            } else {
                $paginationRetornos = $this
                    ->get('app.avalicao_atividade_query')
                    ->searchRetornos($request->query);
            }
        }
        
        return $this->render('tramita_formulario_atividade/index.html.twig', [
            'form' => $form->createView(),
            'paginationEnvios' => $paginationEnvios,
            'paginationRetornos' => $paginationRetornos,
        ]);
    }
    
    /**
     * 
     * @param Request $request
     * @return Response|RedirectResponse
     * 
     * @Route("tramita-formulario-atividade/create", name="tramita_formulario_atividade_create")
     * @Method({"GET", "POST"})
     */
    public function create(Request $request)
    {
        $command = new CadastrarEnvioFormularioAvaliacaoAtividadeCommand();
        $formData = $request->request->get('cadastrar_envio_formulario_avaliacao_atividade', []);        
        $form = $this->createForm(CadastrarEnvioFormularioAvaliacaoAtividadeType::class, $command, $formData);
        
        if ($request->isMethod('POST')) {
            try {
                $form->handleRequest($request);                
                $this->getBus()->handle($command);
                $this->addFlash('success', 'O formulário foi registrado para todos os participantes e foi enviada notificação por e-mail para que seja preenchido no prazo definido. Operação realizada com sucesso.');
                return $this->redirectToRoute('tramita_formulario_atividade');
            } catch (InvalidCommandException $ice) {
                $this->addFlashValidationError();
            }catch (\Exception $e) {                
                $this->addFlashExecutionError();
            }
        }
        
        return $this->render('tramita_formulario_atividade/create.html.twig', [
            'form' => $form->createView(),
        ]);
    }
    
    /**
     * 
     * @param EnvioFormularioAvaliacaoAtividade $envioFormularioAvaliacaoAtividade
     * @param Request $request
     * @return Response
     * 
     * @Route("tramita-formulario-atividade/edit/{id}", name="tramita_formulario_atividade_edit")
     * @Method({"GET", "POST"})
     * @Security("is_granted('ADMINISTRADOR')")
     */
    public function edit(
        EnvioFormularioAvaliacaoAtividade $envioFormularioAvaliacaoAtividade,
        Request $request
    ) {
        $command = new AtualizarEnvioFormularioAvaliacaoAtividadeCommand($envioFormularioAvaliacaoAtividade);        
        $form = $this->createForm(AtualizarEnvioFormularioAvaliacaoAtividadeType::class, $command);
        $form->handleRequest($request);
        
        if ($form->isSubmitted()) {
            try {
                $this->getBus()->handle($command);
                $this->addFlash('success', 'O período de validade de retorno do formulário foi atualizado e os participantes notificados por e-mail. Operação realizada com sucesso.');
            } catch (InvalidCommandException $ice) {
                $this->addFlashValidationError();
            } catch (\Exception $e) {                
                $this->addFlashExecutionError();                
            }
        }
        
        return $this->render('tramita_formulario_atividade/edit.html.twig', [
            'form' => $form->createView(),
            'formData' => $envioFormularioAvaliacaoAtividade->getDetailedInfo(),
        ]);
    }    
    
    /**
     * 
     * @param EnvioFormularioAvaliacaoAtividade $envioFormularioAvaliacaoAtividade
     * @return RedirectResponse
     * 
     * @Route("tramita-formulario-atividade/delete/{id}", name="tramita_formulario_atividade_delete", options={"expose"=true})
     * @Method({"GET"})
     * @Security("is_granted('ADMINISTRADOR')")
     */
    public function delete(EnvioFormularioAvaliacaoAtividade $envioFormularioAvaliacaoAtividade)
    {
        $command = new InativarEnvioFormularioAvaliacaoAtividadeCommand($envioFormularioAvaliacaoAtividade);
        
        try {
            $this->getBus()->handle($command);
            $this->addFlash('success', 'O formulário foi excluído e foi enviada notificação por e-mail para o(s) participante(s). Operação realizada com sucesso.');
        } catch (\Exception $e) {
            $this->addFlashExecutionError();            
        }
        
        return $this->redirectToRoute('tramita_formulario_atividade');
    }
    
    /**
     * 
     * @param TramitacaoFormulario $tramitacaoFormulario
     * @return RedirectResponse
     * 
     * @Route("tramita-formulario-atividade/delete-tramitacao/{id}", name="tramita_formulario_atividade_delete_tramitacao", options={"expose"=true})
     * @Method({"GET"})
     * @Security("is_granted('ADMINISTRADOR')")     
     */
    public function deleteTramitacao(TramitacaoFormulario $tramitacaoFormulario)
    {
        $command = new InativarTramitacaoFormularioCommand($tramitacaoFormulario);
        
        try {
            $this->getBus()->handle($command);
            $this->addFlash('success', 'O formulário foi excluído e foi enviada notificação por e-mail para o(s) participante(s). Operação realizada com sucesso.');
        } catch (\Exception $e) {
            $this->addFlashExecutionError();            
        }
        
        return $this->redirectToRoute('tramita_formulario_atividade');
    }
    
    /**
     * 
     * @param TramitacaoFormulario $tramitacaoFormulario
     * @param Request $request
     * @return Response|RedirectResponse
     * 
     * @Route("tramita-formulario-atividade/analisa/{id}", name="tramita_formulario_atividade_analisa")
     * @Method({"GET", "POST"})
     * @Security("is_granted('ADMINISTRADOR')")
     */
    public function analisar(TramitacaoFormulario $tramitacaoFormulario, Request $request)
    {
        $command = new AnalisarRetornoFormularioAvaliacaoAtividadeCommand($tramitacaoFormulario);        
        $form = $this->createForm(AnalisarRetornoFormularioAvaliacaoAtividadeType::class, $command);        
        $form->handleRequest($request);
        
        if ($form->isSubmitted()) {
            try {
                $this->getBus()->handle($command);
                $this->addFlash('success', 'A análise de retorno do formulário foi registrada e o participante notificado por e-mail. Operação realizada com sucesso.');
                return $this->redirectToRoute('tramita_formulario_atividade');
            } catch (InvalidCommandException $ice) {
                $this->addFlashValidationError();
            } catch (\Exception $e) {                
                $this->addFlashExecutionError();                
            }
        }
        
        return $this->render('tramita_formulario_atividade/analisa.html.twig', [
            'form' => $form->createView(),
        ]);
    }
    
    /**
     * 
     * @param TramitacaoFormulario $tramitacaoFormulario
     * @return Response
     * 
     * @Route("tramita-formulario-atividade/historico/{id}", name="tramita_formulario_atividade_historico", options={"expose"=true})
     * @Method({"GET"})
     * @Security("is_granted('ROLE_USER')")
     */
    public function getHistorioTramitacao(TramitacaoFormulario $tramitacaoFormulario)
    {
        $historico = $this
            ->get('app.avalicao_atividade_query')
            ->findHistoricoTramitacaoFormulario($tramitacaoFormulario);
        
        return $this->render('tramita_formulario_atividade/historico_tramitacao.html.twig', [
            'historico' => $historico,
        ]);
    }
    
    /**
     * 
     * @param EnvioFormularioAvaliacaoAtividade $envioFormularioAvaliacaoAtividade
     * 
     * @Route("tramita-formulario-atividade/list-tramitacoes/{id}/{tipo}", name="tramita_formulario_atividade_list_tramitacoes")
     * @Method({"GET"})
     * @Security("is_granted('ADMINISTRADOR')")
     */
    public function listTramitacoes(EnvioFormularioAvaliacaoAtividade $envioFormularioAvaliacaoAtividade, $tipo, Request $request)
    {
        switch ($tipo) {
            case 'pendentes': 
                $pagination = $this->get('app.avalicao_atividade_query')->searchPendentes($envioFormularioAvaliacaoAtividade, $request->query);
                break;
            case 'enviados':
                $pagination = $this->get('app.avalicao_atividade_query')->searchEnviados($envioFormularioAvaliacaoAtividade, $request->query);
                break;
            case 'finalizados':
                $pagination = $this->get('app.avalicao_atividade_query')->searchFinalizados($envioFormularioAvaliacaoAtividade, $request->query);
                break;
            default:
                throw new NotFoundHttpException();
        } 
        
        return $this->render('tramita_formulario_atividade/list_tramitacoes.html.twig', [
            'pagination' => $pagination,
        ]);
    }
    
    /**
     * 
     * @param $request Request
     * @return Response
     * 
     * @Route("tramita-formulario-atividade/index-retorno", name="tramita_formulario_atividade_index_retorno")
     * @Method({"GET"})
     * @Security("is_granted(['COORDENADOR_PROJETO','COORDENADOR_GRUPO','PRECEPTOR','TUTOR','ESTUDANTE'])")
     */
    public function indexRetorno(Request $request)
    {
        $projetoPessoa = $this->getPessoaPerfilAutenticado()->getProjetoPessoa($this->getProjetoAutenticado());        
        $form = $this->createForm(ConsultarFormulariosAvaliacaoAtividadeParticipanteType::class, null, [
            'projetoPessoa' => $projetoPessoa,
        ]);
        $form->handleRequest($request);        
        
        if ($form->isSubmitted() && $form->isValid()) {
            $request->query->add($request->query->get('consultar_formularios_avaliacao_atividade_participante', []));
            $request->query->set('projetoPessoa', $projetoPessoa->getCoSeqProjetoPessoa());
            $request->query->set('sort', $request->query->get('sort', 'tf.coSeqTramitacaoFormulario'));
            $request->query->set('direction', $request->query->get('direction', 'DESC'));
            $pagination = $this->get('app.avalicao_atividade_query')->searchRetornos($request->query);            
        }
        
        return $this->render('tramita_formulario_atividade/index_retorno.html.twig', [
            'form' => $form->createView(),
            'pagination' => (isset($pagination)) ? $pagination : null,
        ]);
    }
    
    /**
     * 
     * @param TramitacaoFormulario $tramitacaoFormulario
     * @param Request $request
     * @return Response
     * 
     * @Route("tramita-formulario-atividade/retorno/{id}", name="tramita_formulario_atividade_retorno")
     * @Method({"GET", "POST"})
     * @Security("is_granted(['COORDENADOR_PROJETO','COORDENADOR_GRUPO','PRECEPTOR','TUTOR','ESTUDANTE'])")
     */
    public function retorno(TramitacaoFormulario $tramitacaoFormulario, Request $request)
    {
        $projetoPessoa = $this->getPessoaPerfilAutenticado()->getProjetoPessoa($this->getProjetoAutenticado());
        
        if ($tramitacaoFormulario->getProjetoPessoa() != $projetoPessoa) {
            throw new NotFoundHttpException();
        }
        
        $command = new CadastrarRetornoFormularioAvaliacaoAtividadeCommand($tramitacaoFormulario);        
        $form = $this->createForm(CadastrarRetornoFormularioAvaliacaoAtividadeType::class, $command);
        $form->handleRequest($request);
        
        if ($form->isSubmitted()) {
            try {
                $this->getBus()->handle($command);
                $this->addFlash('success', 'Retorno do formulário de avaliação de atividades registrado com sucesso.');
                return $this->redirectToRoute('tramita_formulario_atividade_index_retorno');
            } catch (InvalidCommandException $ice) {
                $this->addFlashValidationError();
            } catch (\Exception $e) {                
                $this->addFlashExecutionError();                
            }
        }
        
        return $this->render('tramita_formulario_atividade/retorno.html.twig', [
            'form' => $form->createView(),
        ]);
    }
    
    /**
     * 
     * @param FormularioAvaliacaoAtividade $formularioAvaliacaoAtividade
     * @return JsonResponse
     * 
     * @Route("tramita-formulario-atividade/load-perfis-formulario/{id}", name="tramita_formulario_atividade_load_perfis_formulario", options={"expose"=true})
     * @Method({"GET"})
     */
    public function loadPerfisFormulario(FormularioAvaliacaoAtividade $formularioAvaliacaoAtividade)
    {
        $perfis = $formularioAvaliacaoAtividade
            ->getPerfilFormularioAvaliacaoAtividadeAtivos()
            ->map(function ($perfilFormularioAvaliacaoAtividade) {
                return $perfilFormularioAvaliacaoAtividade->getPerfil()->toArray();
            })->toArray();
            
        return new JsonResponse($perfis);
    }
    
    /**
     * 
     * @param Request $request
     * @return JsonResponse
     * 
     * @Route("tramita-formulario-atividade/load-projetos-publicacao", name="tramita_formulario_atividade_load_projetos_publicacao", options={"expose"=true})
     * @Method({"GET"})
     */
    public function loadProjetosPublicacao(Request $request)
    {
        $publicacoes = $this->get('app.publicacao_query')->findByIds($request->query->get('ids'));        
        $projetos = [];
        
        foreach ($publicacoes as $publicacao) {
            $projetos[] = $publicacao->getProjetosAtivos()->map(function ($projeto) {
                return $projeto->toArray();
            })->toArray();
        }
        
        return new JsonResponse($projetos);
    }
    
    /**
     * 
     * @param Projeto $projeto
     * @param Request $request
     * @return JsonResponse
     * 
     * @Route("tramita-formulario-atividade/load-participantes/{id}", name="tramita_formulario_atividade_load_participantes", options={"expose"=true})
     * @Method({"GET"})
     */
    public function loadParticipantes(Projeto $projeto, Request $request)
    {
        $perfis = $request->query->get('perfis', []);
        
        $participantes = $projeto->getProjetosPessoas()->map(function ($projetoPessoa) use ($perfis) {
            if (in_array($projetoPessoa->getPessoaPerfil()->getPerfil()->getCoSeqPerfil(), $perfis)) {
                return [
                    'coSeqProjetoPessoa' => $projetoPessoa->getCoSeqProjetoPessoa(),
                    'descricaoParticipante' => $projetoPessoa->getDescricaoParticipante(),
                    'coPerfil' => $projetoPessoa->getPessoaPerfil()->getPerfil()->getCoSeqPerfil(),
                    'coProjeto' => $projetoPessoa->getProjeto()->getCoSeqProjeto(),
                ];
            }
        })->toArray();
        
        return new JsonResponse(array_filter($participantes));
    }
}
