<?php

namespace AppBundle\Controller;

use AppBundle\CommandBus\CadastrarBancoCommand;
use AppBundle\CommandBus\ExcluirBancoCommand;
use AppBundle\CommandBus\InativarBancoCommand;
use AppBundle\Entity\Banco;
use AppBundle\Exception\BancoExistsException;
use AppBundle\Form\CadastrarBancoType;
use AppBundle\Form\ConsultarBancoType;
use Exception;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;


/**
 * @Security("is_granted('ADMINISTRADOR')")
 */
final class BancoController extends ControllerAbstract
{
    /**
     *
     * @param Request $request
     * @return Response
     *
     * @Route("banco", name="banco_index")
     * @Method({"GET", "POST"})
     */
    public function index(Request $request)
    {
        $pagination = null;
        $em = $this->getDoctrine()->getManager();
        $form = $this->createForm(ConsultarBancoType::class);
        $form->handleRequest($request);
        $pagination = null;

        if (($request->isMethod('GET') && $form->isValid())) {
            $request->query->add((array)$form->getData());
            $pagination = $this->get('app.banco_query')->search($request->query);

//            foreach ($pagination->getItems() as $valorBolsaPrograma) {
//                if ($valorBolsaPrograma->isAtivo()) {
//                    try {
//                        $em->getRepository('AppBundle:FolhaPagamento')->checkNuMesNuAnoHasInFolha(
//                            $valorBolsaPrograma->getNuMesVigencia(),
//                            $valorBolsaPrograma->getNuAnoVigencia());
//                    } catch (Exception $e) {
//                        $valorBolsaPrograma->isInFolha = true;
//                    }
//                }
//            }
        }

        return $this->render('banco/index.html.twig', [
            'form' => $form->createView(),
            'pagination' => $pagination,
        ]);
    }

    /**
     *
     * @param Request $request
     * @return Response
     *
     * @Route("banco/create", name="banco_create")
     * @Method({"GET", "POST"})
     */
    public function create(Request $request)
    {
        $command = new CadastrarBancoCommand();
        $form = $this->createForm(CadastrarBancoType::class, $command);

        if ($request->isMethod('POST')) {
            $form->handleRequest($request);

            try {
                $this->getBus()->handle($command);
                $this->addFlash('success', 'Inclusão realizada com sucesso!');
                return $this->redirectToRoute('banco_index');
            } catch (BancoExistsException $e) {
                $this->addFlash('warning', $e->getMessage());
            } catch (Exception $e) {
                $this->addFlashValidationError();
            }
        }

        return $this->render('banco/create.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     *
     * @param Request $request
     * @param Banco $banco
     * @return Response
     *
     * @Route("banco/edit/{id}", name="banco_edit")
     * @Method({"GET", "POST"})
     */
    public function edit(Request $request, Banco $banco)
    {
        $command = new CadastrarBancoCommand($banco);

        $form = $this->createForm(CadastrarBancoType::class, $command);

        if ($request->isMethod('POST')) {
            $form->handleRequest($request);

            try {
                $this->getBus()->handle($command);
//                $this->addFlash('success', 'Alteração realizada com sucesso!');
//                return $this->redirectToRoute('banco_edit', ['id' => $banco->getCoBanco()]);
                $this->addFlash('success', 'Alteração realizada com sucesso!');
                return $this->redirectToRoute('banco_index');
            } catch (BancoExistsException $e) {
                $this->addFlash('warning', $e->getMessage());
            } catch (Exception $e) {
                $this->addFlash('danger', $e->getMessage());
                $this->addFlashValidationError();
            }
        }

        return $this->render('banco/create.html.twig', [
            'form' => $form->createView(),
            'edit' => true,
        ]);
    }

    /**
     *
     * @param Banco $banco
     * @return RedirectResponse
     *
     * @Route("banco/activate/{id}", name="banco_activate", options={"expose"=true})
     * @Method({"GET"})
     */
    public function activate(Banco $banco)
    {
        try {
            $command = new InativarBancoCommand($banco);
            $this->getBus()->handle($command);

            $this->addFlash('success', 'Operação ativar/desativar realizada com sucesso!');
        } catch (Exception $e) {
            $this->addFlash('warning', $e->getMessage());
            $this->addFlash('danger', 'Ocorreu um erro ao executar a operação.');
        }

        return $this->redirectToRoute('banco_index');
    }

    /**
     *
     * @param Banco $banco
     * @return RedirectResponse
     *
     * @Route("banco/delete/{id}", name="banco_delete", options={"expose"=true})
     * @Method({"GET"})
     */
    public function delete(Banco $banco)
    {
        try {
            $command = new ExcluirBancoCommand($banco);
            $this->getBus()->handle($command);

            $this->addFlash('success', 'Operação exclusão realizada com sucesso!');
        } catch (Exception $e) {
            $this->addFlash('warning', $e->getMessage());
            $this->addFlash('danger', 'Ocorreu um erro ao executar a operação.');
        }

        return $this->redirectToRoute('banco_index');
    }

}
