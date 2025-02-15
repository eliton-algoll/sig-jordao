<?php

namespace App\Controller;

use App\CommandBus\CadastrarValorBolsaCommand;
use App\CommandBus\InativarValorBolsaCommand;
use App\Entity\ValorBolsaPrograma;
use App\Exception\ValorBolsaHasInFolhaException;
use App\Exception\ValorBolsaProgramaExistsException;
use App\Form\CadastrarValorBolsaType;
use App\Form\ConsultarValorBolsaType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * @Security("is_granted('ADMINISTRADOR')")
 */
final class ValorBolsaController extends ControllerAbstract
{
    /**
     * 
     * @param Request $request
     * @return Response
     * 
     * @Route("valor-bolsa", name="valor_bolsa_index")
     * @Method({"GET", "POST"})
     */
    public function index(Request $request)
    {
        $pagination = null;
        $em = $this->getDoctrine()->getManager();
        $form = $this->createForm(ConsultarValorBolsaType::class);
        $form->handleRequest($request);        
        
        if (($request->isMethod('GET') && $form->isValid())) {
            $request->query->add((array) $form->getData());
            $pagination = $this->get('app.valor_bolsa_query')->search($request->query);
            
            foreach ($pagination->getItems() as $valorBolsaPrograma) {
                if ($valorBolsaPrograma->isAtivo()) {
                    try {
                        $em->getRepository('App:FolhaPagamento')->checkNuMesNuAnoHasInFolha(
                            $valorBolsaPrograma->getNuMesVigencia(),
                            $valorBolsaPrograma->getNuAnoVigencia());                        
                    } catch (\Exception $e) {
                        $valorBolsaPrograma->isInFolha = true;
                    }
                }
            }
        }
        
        return $this->render('valor_bolsa/index.html.twig', [
            'form' => $form->createView(),
            'pagination' => $pagination,
        ]);
    }
    
    /**
     * 
     * @param Request $request
     * @return Response
     * 
     * @Route("valor-bolsa/create", name="valor_bolsa_create")
     * @Method({"GET", "POST"})
     */
    public function create(Request $request)
    {
        $command = new CadastrarValorBolsaCommand();
        
        $form = $this->createForm(CadastrarValorBolsaType::class, $command);
        
        if ($request->isMethod('POST')) {
            $form->handleRequest($request);
            try {
                $this->getBus()->handle($command);
                $this->addFlash('success', 'Inclusão realizada com sucesso!');
                return $this->redirectToRoute('valor_bolsa_index');
            } catch (ValorBolsaProgramaExistsException $vbe) {
                $this->addFlash('warning', $vbe->getMessage());
            } catch (ValorBolsaHasInFolhaException $vbf) {
                $this->addFlash('warning', $vbf->getMessage());
            } catch (\Exception $ex) {                
                $this->addFlashValidationError();                
            }
        }
        
        return $this->render('valor_bolsa/create.html.twig', [
            'form' => $form->createView(),
        ]);
    }
    
    /**
     * 
     * @param Request $request
     * @param ValorBolsaPrograma $valorBolsaPrograma
     * @return Response
     * 
     * @Route("valor-bolsa/edit/{id}", name="valor_bolsa_edit")
     * @Method({"GET", "POST"})
     */
    public function edit(Request $request, ValorBolsaPrograma $valorBolsaPrograma)
    {
        $command = new CadastrarValorBolsaCommand();
        $command->setValorBolsaPrograma($valorBolsaPrograma);
        
        $form = $this->createForm(CadastrarValorBolsaType::class, $command);
        
        if ($request->isMethod('POST')) {
            $form->handleRequest($request);
            try {
                $this->getBus()->handle($command);
                $this->addFlash('success', 'Alteração realizada com sucesso!');
                return $this->redirectToRoute('valor_bolsa_edit', [ 'id' => $valorBolsaPrograma->getCoSeqValorBolsaPrograma() ]);
            } catch (ValorBolsaProgramaExistsException $vbe) {
                $this->addFlash('warning', $vbe->getMessage());
            } catch (ValorBolsaHasInFolhaException $vbf) {
                $this->addFlash('warning', $vbf->getMessage());
            } catch (\Exception $ex) {                
                $this->addFlashValidationError();
            }
        }
        
        return $this->render('valor_bolsa/create.html.twig', [
            'form' => $form->createView(),
            'edit' => true,
        ]);
    }
    
    /**
     * 
     * @param ValorBolsaPrograma $valorBolsaPrograma
     * @return RedirectResponse
     * 
     * @Route("valor-bolsa/delete/{id}", name="valor_bolsa_delete", options={"expose"=true})
     * @Method({"GET"})
     */
    public function delete(ValorBolsaPrograma $valorBolsaPrograma)
    {
        try {
            $command = new InativarValorBolsaCommand($valorBolsaPrograma);
            $this->getBus()->handle($command);
            
            $this->addFlash('success', 'Operação exclusão realizada com sucesso!');
        } catch (ValorBolsaHasInFolhaException $vbf) {
            $this->addFlash('warning', $vbf->getMessage());
        } catch (\Exception $e) {
            $this->addFlash('warning', $e->getMessage());
            $this->addFlash('danger', 'Ocorreu um erro ao executar a operação.');
        }
        
        return $this->redirectToRoute('valor_bolsa_index');
    }
}
