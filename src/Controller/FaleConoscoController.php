<?php

namespace App\Controller;

use App\CommandBus\EnviarFaleConoscoCommand;
use App\Form\EnviarFaleConoscoType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;

class FaleConoscoController extends ControllerAbstract
{
    /**
     *
     * @param Request $request
     * @Route("fale-conosco", name="fale_conosco")
     */
    public function indexAction(Request $request)
    {
        $command = new EnviarFaleConoscoCommand();
        
        $form = $this->createForm(EnviarFaleConoscoType::class, $command);
        
        if ($request->isMethod('POST')) {
            $form->handleRequest($request);
            try {
                $this->getBus()->handle($command);
                $this->addFlash('success', 'Fale conosco enviado com sucesso! Você receberá o retorno no endereço de e-mail informado.');
                return $this->redirectToRoute('fale_conosco');
            } catch (\RuntimeException $rte) {
                $this->addFlash('danger', 'Ocorreu um erro ao enviar o e-mail para o SIGPET. Por favor, repita a operação e, se o erro persistir, entre em contato pelo 136 – Suporte a Sistemas DATASUS.');
            } catch (\Exception $ex) {
                $this->addFlashValidationError();
            }
        }
        
        return $this->render('fale_conosco/index.html.twig', [
            'form' => $form->createView()
        ]);
    }
}
