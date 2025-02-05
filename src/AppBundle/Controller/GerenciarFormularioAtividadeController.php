<?php

namespace AppBundle\Controller;

use AppBundle\CommandBus\CadastrarFormularioAvaliacaoAtividadeCommand;
use AppBundle\CommandBus\InativarFormularioAvaliacaoAtividadesCommand;
use AppBundle\Entity\FormularioAvaliacaoAtividade;
use AppBundle\Form\CadastrarFormularioAvaliacaoAtividadeType;
use AppBundle\Form\ConsultarFormulariosAtividadesType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Component\HttpFoundation\ParameterBag;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * @Security("is_granted('ADMINISTRADOR')")
 */
final class GerenciarFormularioAtividadeController extends ControllerAbstract
{
    /**
     * 
     * @param Request $request
     * @return Response
     * 
     * @Route("gerenciar-formulario-atividade", name="gerenciar_formulario_atividade")
     * @Method({"GET"})
     */
    public function index(Request $request)
    {
        $pagination = null;
        $form = $this->createForm(ConsultarFormulariosAtividadesType::class);
        $form->handleRequest($request);
        
        if ($form->isSubmitted()) {
            $formData = $request->query->get('consultar_formularios_atividades');
            $pagination = $this->get('app.avalicao_atividade_query')->searchFormularios(new ParameterBag($formData));
        }
        
        return $this->render('gerenciar_formulario_atividade/index.html.twig', [
            'form' => $form->createView(),
            'pagination' => $pagination,
        ]);
    }
    
    /**
     * 
     * @param Request $request
     * @return Response
     * 
     * @Route("gerenciar-formulario-atividade/create", name="gerenciar_formulario_atividade_create")
     * @Method({"GET", "POST"})
     */
    public function create(Request $request)
    {
        $command = new CadastrarFormularioAvaliacaoAtividadeCommand();
        
        $form = $this->createForm(CadastrarFormularioAvaliacaoAtividadeType::class, $command);
        $form->handleRequest($request);
        
        if ($request->isMethod('POST') && $form->isValid()) {
            try {
                $this->getBus()->handle($command);
                $this->addFlash('success', 'Inclusão realizada com sucesso!');
                return $this->redirectToRoute('gerenciar_formulario_atividade');
            } catch (\Exception $e) {
                $this->addFlashExecutionError();
            }
        }
        
        return $this->render('gerenciar_formulario_atividade/create.html.twig', [
            'form' => $form->createView(),
        ]);
    }
    
    /**
     * 
     * @param FormularioAvaliacaoAtividade $formularioAvaliacaoAtividade
     * @param Request $request
     * 
     * @Route("gerenciar-formulario-atividade/edit/{id}", name="gerenciar_formulario_atividade_edit")
     * @Method({"GET","POST"})
     */
    public function edit(
        FormularioAvaliacaoAtividade $formularioAvaliacaoAtividade,
        Request $request
    ) {
        $command = new CadastrarFormularioAvaliacaoAtividadeCommand();
        $command->setFormularioAvaliacaoAtividade($formularioAvaliacaoAtividade);
        
        $form = $this->createForm(CadastrarFormularioAvaliacaoAtividadeType::class, $command);
        
        if ($request->isMethod('POST')) {
            try {
                $form->handleRequest($request);
                $this->getBus()->handle($command);
                $this->addFlash('success', 'Alteração realizada com sucesso!');
                return $this->redirectToRoute('gerenciar_formulario_atividade_edit', [
                    'id' => $formularioAvaliacaoAtividade->getCoSeqFormAvaliacaoAtivd(),
                ]);
            } catch (\Exception $e) {
                $this->addFlashExecutionError();
            }
        }
        
        return $this->render('gerenciar_formulario_atividade/create.html.twig', [
            'form' => $form->createView(),
        ]);
    }
    
    /**
     * 
     * @param FormularioAvaliacaoAtividade $formularioAvaliacaoAtividade
     * @param type $softDelete
     * @return RedirectResponse
     * 
     * @Route("gerenciar-formulario-atividade/delete/{id}/{softDelete}", name="gerenciar_formulario_atividade_delete", options={"expose"=true})
     * @Method({"GET"})
     */
    public function delete(FormularioAvaliacaoAtividade $formularioAvaliacaoAtividade, $softDelete)
    {
        try {
            $command = new InativarFormularioAvaliacaoAtividadesCommand(
                $formularioAvaliacaoAtividade,
                (bool) $softDelete
            );
            $this->getBus()->handle($command);
            $this->addFlash(
                'success',
                ((bool) $softDelete) ?  'O registro foi inativado com sucesso!' : 'Exclusão realizada com sucesso!'
            );
        } catch (\Exception $e) {
            $this->addFlashExecutionError();
        }
        
        return $this->redirectToRoute('gerenciar_formulario_atividade');
    }
    
    /**
     * 
     * @param FormularioAvaliacaoAtividade $formularioAvaliacaoAtividade
     * @return RedirectResponse
     * 
     * @Route("gerenciar-formulario-atividade/active/{id}", name="gerenciar_formulario_atividade_active")
     * @Method({"GET"})
     */
    public function active(FormularioAvaliacaoAtividade $formularioAvaliacaoAtividade)
    {
        try {
            if ($formularioAvaliacaoAtividade->isInativo()) {
                $formularioAvaliacaoAtividade->ativar();

                $em = $this->getDoctrine()->getManager();
                $em->persist($formularioAvaliacaoAtividade);
                $em->flush();
            }
            
            $this->addFlash('success', 'O registro foi ativado com sucesso!');
        } catch (\Exception $e) {
            $this->addFlashExecutionError();
        }
        
        return $this->redirectToRoute('gerenciar_formulario_atividade');
    }
}
