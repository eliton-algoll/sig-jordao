<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use League\Tactician\Bundle\Middleware\InvalidCommandException;
use AppBundle\Form\CadastrarGrupoAtuacaoType;
use AppBundle\CommandBus\CadastrarGrupoAtuacaoCommand;
use AppBundle\CommandBus\InativarGrupoAtuacaoCommand;

/**
 * @Security("is_granted('COORDENADOR_PROJETO', 'ROLE_PREVIOUS_ADMIN')")
 */
class GrupoAtuacaoController extends ControllerAbstract
{
    /**
     * @Route("/grupo-atuacao", name="grupo_atuacao_index")
     */
    public function indexAction(Request $request)
    {
        $command = new CadastrarGrupoAtuacaoCommand();
        
        $coProjeto = $this->getProjetoAutenticado()->getCoSeqProjeto();
        
        $form = $this->createForm(CadastrarGrupoAtuacaoType::class, $command, array('coProjeto' => $coProjeto));
        $form->handleRequest($request);
        
        if ($form->isSubmitted() && $form->isValid()) {

            $areaTematica = $command->getAreasTematicas()->getCoSeqAreaTematica();

            if(!$this->get('app.grupo_atuacao_query')->findGrupoByProjeto($coProjeto, $areaTematica)){
                try {
                    $this->getBus()->handle($command);
                    $this->addFlash('success', 'Grupo de atuação cadastrado com sucesso');
                    return $this->redirectToRoute('grupo_atuacao_index');

                } catch (InvalidCommandException $e) {
                    $this->addFlashValidationError();
                }
            }else{
                $this->addFlash('danger', 'Grupo de atuação já cadastrado.');
                return $this->redirectToRoute('grupo_atuacao_index');
            }
        }
        
        $grupos = $this->get('app.grupo_atuacao_query')->findGrupoByProjeto($coProjeto);
        
        return $this->render('/grupo_atuacao/index.html.twig', array(
            'form' => $form->createView(),
            'grupos' => $grupos
        ));
    }
    
    /**
     * @Route("/grupo-atuacao/inativar/{grupoAtuacao}", name="grupo_atuacao_inativar", options={"expose"=true})
     * @Method({"POST"})
     */
    public function inativarAction(Request $request, $grupoAtuacao)
    {
        $command = new InativarGrupoAtuacaoCommand();
        $command->setCoSeqGrupoAtuacao($grupoAtuacao);
        
        try {
            $this->getBus()->handle($command);
            
            $return = array(
                'status' => true,
                'message' => 'Grupo de Atuação inativado com sucesso'
            );
            
        } catch (\Exception $e) {
            
            $return = array(
                'status' => false,
                'message' => $e->getMessage()
            );
        }
        
        return new JsonResponse($return);
    }    
}
