<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use League\Tactician\Bundle\Middleware\InvalidCommandException;
use AppBundle\Form\CadastrarEstabelecimentoType;
use AppBundle\CommandBus\CadastrarEstabelecimentoCommand;
use AppBundle\CommandBus\InativarEstabelecimentoCommand;

/**
 * @Security("is_granted(['COORDENADOR_PROJETO', 'ROLE_PREVIOUS_ADMIN'])")
 */
class EstabelecimentoController extends ControllerAbstract
{
    /**
     * @Route("/estabelecimento", name="estabelecimento_index")
     */
    public function indexAction(Request $request)
    {
        $command = new CadastrarEstabelecimentoCommand();
        $command->setCoProjeto($this->getProjetoAutenticado()->getCoSeqProjeto());
        
        $form = $this->createForm(CadastrarEstabelecimentoType::class, $command);
        $form->handleRequest($request);
        
        if ($form->isSubmitted()) {
            
            try {
                $this->getBus()->handle($command);
                $this->addFlash('success', 'Estabelecimento cadastrado com sucesso');
                return $this->redirectToRoute('estabelecimento_index');
                
            } catch (InvalidCommandException $e) {
                $this->addFlashValidationError();
                
            } catch (\InvalidArgumentException $e) {
                $this->addFlash('danger', $e->getMessage());
            }
        }
        
        $estabelecimentos = $this->get('app.estabelecimento_query')->listEstabelecimentos(
            $this->getProjetoAutenticado()->getCoSeqProjeto()
        );
        
        return $this->render('/estabelecimento/index.html.twig', array(
            'form' => $form->createView(),
            'estabelecimentos' => $estabelecimentos
        ));
    }
    
    /**
     * @Route("/estabelecimento/inativar/{estabelecimento}", name="estabelecimento_inativar", options={"expose"=true})
     * @Method({"POST"})
     */
    public function inativarAction(Request $request, $estabelecimento)
    {
        $command = new InativarEstabelecimentoCommand();
        $command->setCoSeqProjetoEstabelec($estabelecimento);
        
        try {
            $this->getBus()->handle($command);
            
            $return = array(
                'status' => true,
                'message' => 'Estabelecimento removido com sucesso'
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