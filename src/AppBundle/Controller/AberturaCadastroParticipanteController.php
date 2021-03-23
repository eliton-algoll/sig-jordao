<?php

namespace AppBundle\Controller;

use AppBundle\CommandBus\CadastrarAberturaSistemaCadastroParticipanteCommand;
use AppBundle\CommandBus\InativarAberturaSistemaCadastroParticipanteCommand;
use AppBundle\Entity\AutorizaCadastroParticipante;
use AppBundle\Entity\Publicacao;
use AppBundle\Exception\AutorizacaoCadastroParticipanteVigenteExistsException;
use AppBundle\Form\CadastarAberturaSistemaCadastroParticipanteType;
use AppBundle\Form\ConsultarAberturaSistemaCadastroParticipanteType;
use League\Tactician\Bundle\Middleware\InvalidCommandException;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * @Security("is_granted('ADMINISTRADOR')")
 */
final class AberturaCadastroParticipanteController extends ControllerAbstract
{
    /**
     * 
     * @param Request $request
     * @return Response
     * 
     * @Route("abertura-cadastro-participante", name="abertura_cadastro_participante")
     * @Method({"GET"})
     */
    public function index(Request $request)
    {
        $pagination = null;       
        $pb = $request->query;
        $formData = $pb->get('consultar_abertura_sistema_cadastro_participante');
        $form = $this->createForm(ConsultarAberturaSistemaCadastroParticipanteType::class, null, [
            'publicacao' => (isset($formData['publicacao'])) ? $formData['publicacao'] : null,
        ]);
        $form->handleRequest($request);
        
        if ($form->isSubmitted()) {
            $pb->add($formData);
            $pb->set('sort', $pb->get('sort', 'acp.coSeqAutorCadParticipante'));
            $pb->set('direction', $pb->get('direction', 'DESC'));
            $pagination = $this->get('app.participante_query')->searchAberturasCadastroParticipante($pb);
        }
        
        return $this->render('abertura_cadastro_participante/index.html.twig', [
            'form' => $form->createView(),
            'pagination' => $pagination,
        ]);
    }
    
    /**
     * 
     * @param Request $request
     * 
     * @Route("abertura-cadastro-participante/create", name="abertura_cadastro_participante_create")
     * @Method({"GET", "POST"})
     */
    public function create(Request $request)
    {
        $formData = $request->request->get('cadastar_abertura_sistema_cadastro_participante');
        $command = new CadastrarAberturaSistemaCadastroParticipanteCommand();        
        $form = $this->createForm(CadastarAberturaSistemaCadastroParticipanteType::class, $command, [
            'publicacao' => (isset($formData['publicacao'])) ? $formData['publicacao'] : null,
        ]);
        $form->handleRequest($request);        
        
        if ($form->isSubmitted()) {            
            try {
                $this->getBus()->handle($command);
                $this->addFlash('success', 'Inclusão realizada com sucesso!');
                return $this->redirectToRoute('abertura_cadastro_participante');
            } catch (InvalidCommandException $ice) {
                $this->addFlashValidationError();
            } catch (AutorizacaoCadastroParticipanteVigenteExistsException $acpve) {
                $this->addFlash('danger', $acpve->getMessage());
            } catch (\Exception $ex) {
                $this->addFlashExecutionError();
            }
        }
        
        return $this->render('abertura_cadastro_participante/create.html.twig', [
           'form' => $form->createView(),
        ]);
    }
    
    /**
     * 
     * @param AutorizaCadastroParticipante $autorizaCadastroParticipante
     * @param Request $request
     * 
     * @Route("abertura-cadastro-participante/edit/{id}", name="abertura_cadastro_participante_edit")
     * @Method({"GET", "POST"})
     */
    public function edit(
        AutorizaCadastroParticipante $autorizaCadastroParticipante,
        Request $request
    ) {
        $command = new CadastrarAberturaSistemaCadastroParticipanteCommand();
        $command->setAutorizaCadastroParticipante($autorizaCadastroParticipante);
        $form = $this->createForm(CadastarAberturaSistemaCadastroParticipanteType::class, $command, [
            'publicacao' => $autorizaCadastroParticipante->getProjeto()->getPublicacao()->getCoSeqPublicacao(),
        ]);
        $form->handleRequest($request);
        
        if ($form->isSubmitted()) {            
            try {
                $this->getBus()->handle($command);
                $this->addFlash('success', 'Alteração realizada com sucesso!');
            } catch (InvalidCommandException $ice) {
                $this->addFlashValidationError();
            } catch (\Exception $ex) {
                $this->addFlashExecutionError();                
            }
        }
        
        return $this->render('abertura_cadastro_participante/create.html.twig', [
            'form' => $form->createView(),
            'edit' => true,
        ]);
    }
    
    /**
     * 
     * @param AutorizaCadastroParticipante $autorizaCadastroParticipante
     * @return RedirectResponse
     * 
     * @Route("abertura-cadastro-participante/delete/{id}", name="abertura_cadastro_participante_delete", options={"expose"=true})
     */
    public function delete(AutorizaCadastroParticipante $autorizaCadastroParticipante)
    {
        try {
            $command = new InativarAberturaSistemaCadastroParticipanteCommand($autorizaCadastroParticipante);
            $this->getBus()->handle($command);
            $this->addFlash('success', 'Exclusão realizada com sucesso!');
        } catch (\Exception $ex) {
            $this->addFlashExecutionError();
        }
        
        return $this->redirectToRoute('abertura_cadastro_participante');
    }
    
    /**
     * 
     * @param Publicacao $publicacao
     * @return JsonResponse
     * 
     * @Route("abertura-cadastro-participante/get-projetos/{id}", name="abertura_cadastro_participante_get_projetos", options={"expose"=true})
     * @Method({"GET"})
     */
    public function getProjetos(Publicacao $publicacao)
    {
        $projetos = $publicacao->getProjetosAtivos()->map(function ($projeto) {
            return [
                'coSeqProjeto' => $projeto->getCoSeqProjeto(),
                'nuSipar' => $projeto->getNuSipar(),
            ];
        })->toArray();
        
        return new JsonResponse($projetos);
    }
    
    /**
     * 
     * @param Publicacao $publicacao
     * @return JsonResponse
     * 
     * @Route("abertura-cadastro-participante/get-info-folha/{id}", name="abertura_cadastro_participante_get_info_folha", options={"expose"=true})
     * @Method({"GET"})
     */
    public function getFolhaAbertaPagamento(Publicacao $publicacao)
    {
        $response = [];
        
        try {
            $folhaPagamento = $this
                ->get('app.folha_pagamento_repository')
                ->getFolhaAbertaByPublicacao($publicacao);
            $response = $folhaPagamento->toArray();
        } catch (\Exception $e) {
            $response['error'] = $e->getMessage();
        }
        
        return new JsonResponse($response);
    }
    
    /**
     * 
     * @param Publicacao $publicacao
     * @return JsonResponse
     * 
     * @Route("abertura-cadastro-participante/get-folhas/{id}", name="abertura_cadastro_participante_get_folhas", options={"expose"=true})
     * @Method({"GET"})
     */
    public function getFolhasPagamento(Publicacao $publicacao)
    {
        $response = $publicacao->getFolhasPagamentoAtivas()->map(function ($folhaPagamento) {
            if ($folhaPagamento->isMensal()) {
                return $folhaPagamento->toArray();
            }
        })->toArray();
        
        return new JsonResponse($response);
    }
}
