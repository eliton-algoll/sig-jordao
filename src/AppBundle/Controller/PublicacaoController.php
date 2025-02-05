<?php

namespace AppBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use League\Tactician\Bundle\Middleware\InvalidCommandException;
use AppBundle\CommandBus\AtualizarPublicacaoCommand;
use AppBundle\CommandBus\InativarPublicacaoCommand;
use AppBundle\Form\AtualizarPublicacaoType;
use AppBundle\Entity\Publicacao;

/**
 * @Security("is_granted('ADMINISTRADOR')")
 */
class PublicacaoController extends ControllerAbstract
{
    /**
     * @Route("/publicacao/atualizar/{publicacao}", name="publicacao_atualizar")
     */
    public function atualizarAction(Request $request, Publicacao $publicacao)
    {
        $command = new AtualizarPublicacaoCommand();

        $form = $this->createForm(AtualizarPublicacaoType::class, $command);
        $form->handleRequest($request);

        if ($form->isSubmitted()) {

            try {
                $this->getBus()->handle($command);
                $this->addFlash('success', 'Publicação atualizada com sucesso');
                
                return $this->redirectToRoute(
                    'programa_atualizar', 
                    array('programa' => $publicacao->getPrograma()->getCoSeqPrograma())
                );

            } catch (InvalidCommandException $e) {
                $this->addFlashValidationError();
            }
        }

        return $this->render(
            'publicacao/atualizar.html.twig',
            array('form' => $form->createView(), 'publicacao' => $publicacao)
        );
    }
    
    /**
     * @Route("/publicacao/inativar/{publicacao}", name="publicacao_inativar", options={"expose"=true})
     */
    public function inativarAction(Request $request, $publicacao)
    {
        $command = new InativarPublicacaoCommand();
        $command->setCoSeqPublicacao($publicacao);
        
        try {
            $this->getBus()->handle($command);
            
            $return = array(
                'status' => true,
                'message' => 'Publicação inativada com sucesso'
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
