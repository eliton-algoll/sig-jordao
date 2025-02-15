<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\ParameterBag;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use League\Tactician\Bundle\Middleware\InvalidCommandException;
use App\Controller\ControllerAbstract;
use App\Form\CadastrarProgramaType;
use App\Form\AtualizarProgramaType;
use App\Form\PesquisarProgramaType;
use App\CommandBus\CadastrarProgramaCommand;
use App\CommandBus\AtualizarProgramaCommand;
use App\Entity\Programa;

/**
 * @Security("is_granted('ADMINISTRADOR')")
 */
class ProgramaController extends ControllerAbstract
{
    /**
     * @Route("/programa", name="programa")
     * @Method({"GET"})
     */
    public function allAction(Request $request)
    {
        $queryParams = $request->query->get('pesquisar_programa', array());
        $queryParams['page'] = $request->query->get('page', 1);
        
        $pagination = $this
            ->get('app.programa_query')
            ->search(
                new ParameterBag($queryParams)
            );

        $form = $this->createForm(PesquisarProgramaType::class);

        return $this->render(
            'programa/all.html.twig',
            array(
                'pagination' => $pagination,
                'queryParams' => $queryParams,
                'form' => $form->createView()
            )
        );
    }

    /**
     * @Route("/programa/cadastrar", name="programa_cadastrar")
     */
    public function cadastrarAction(Request $request)
    {
        $command = new CadastrarProgramaCommand();

        $form = $this->createForm(CadastrarProgramaType::class, $command);
        $form->handleRequest($request);

        if ($form->isSubmitted()) {

            try {
                $this->getBus()->handle($command);
                $this->addFlash('success', 'Programa cadastrado com sucesso');
                return $this->redirectToRoute('programa');

            } catch (InvalidCommandException $e) {
                $this->addFlashValidationError();
            }
        }

        return $this->render(
            'programa/cadastrar.html.twig',
            array('form' => $form->createView())
        );
    }

    /**
     * @Route("/programa/atualizar/{programa}", name="programa_atualizar")
     */
    public function atualizarAction(Request $request, Programa $programa)
    {
        $command = new AtualizarProgramaCommand($programa);

        $form = $this->createForm(AtualizarProgramaType::class, $command);
        $form->handleRequest($request);

        if ($form->isSubmitted()) {

            try {
                $this->getBus()->handle($command);
                $this->addFlash('success', 'Programa atualizado com sucesso');
                return $this->redirectToRoute('programa');

            } catch (InvalidCommandException $e) {
                $this->addFlashValidationError();
            }
        }

        return $this->render(
            'programa/atualizar.html.twig',
            array('form' => $form->createView(), 'programa' => $programa)
        );
    }
}