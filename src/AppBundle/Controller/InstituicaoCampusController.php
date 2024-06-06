<?php

namespace AppBundle\Controller;

use AppBundle\CommandBus\CadastrarAdministradorCommand;
use AppBundle\Form\CadastrarAdministradorType;
use AppBundle\Form\ConsultarAdministradorType;
use AppBundle\Form\ConsultarInstituicaoType;
use Exception;
use League\Tactician\Bundle\Middleware\InvalidCommandException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;


/**
 * @Security("is_granted('ADMINISTRADOR')")
 */
final class InstituicaoCampusController extends ControllerAbstract
{
    /**
     *
     * @param Request $request
     * @return Response
     *
     * @Route("instituicao", name="instituicao")
     * @Method({"GET", "POST"})
     */
    public function index(Request $request)
    {
        $pagination = null;
        $em = $this->getDoctrine()->getManager();
        $form = $this->createForm(ConsultarInstituicaoType::class);
        $form->handleRequest($request);
        $pagination = null;

        if (($request->isMethod('GET'))) {
            if( $form->isValid() ) {
                $request->query->add((array)$form->getData());
            }
            $pagination = $this->get('app.instituicao_query')->searchInst($request->query);
        }

        return $this->render('instituicao/index.html.twig', [
            'form' => $form->createView(),
            'pagination' => $pagination,
        ]);
    }

    /**
     *
     * @param Request $request
     * @return Response
     *
     * @Route("campus", name="campus")
     * @Method({"GET", "POST"})
     */
    public function campus(Request $request)
    {
        $pagination = null;
        $em = $this->getDoctrine()->getManager();
        $form = $this->createForm(ConsultarInstituicaoType::class);
        $form->handleRequest($request);
        $pagination = null;

        if (($request->isMethod('GET'))) {
            if( $form->isValid() ) {
                $request->query->add((array)$form->getData());
            }
            $pagination = $this->get('app.instituicao_query')->search($request->query);
        }

        return $this->render('campus/index.html.twig', [
            'form' => $form->createView(),
            'pagination' => $pagination,
        ]);
    }

    /**
     *
     * @param Request $request
     * @return Response
     *
     * @Route("/instituicao/create", name="instituicao_create")
     * @Method({"GET", "POST"})
     */
    public function create(Request $request)
    {
        $command = new CadastrarAdministradorCommand();
        $form = $this->createForm(CadastrarAdministradorType::class, $command);

        if ($request->isMethod('POST')) {
            $form->handleRequest($request);
            try {
                $this->getBus()->handle($command);
                $this->addFlash('success', 'Inclusão realizada com sucesso! Consulte seu e-mail cadastrado para confirmar os dados de acesso.');
                return $this->redirectToRoute('administrador');
            } catch (InvalidCommandException $e) {
                $erros = array();
                if (method_exists($e, 'getViolations')) {
                    foreach ($e->getViolations() as $violation) {
                        $message = $violation->getMessage();
                        foreach($form->all() as $formElement) {
                            $config = $formElement->getConfig();
                            if($violation->getPropertyPath() == $config->getName()) {
                                $message .= ' Verificar: ' . $config->getOption("label") . '.';
                            }
                        }
                        $erros[] = $message;
                    }
                }
                $this->addFlash('danger', implode('</br>', $erros));
            } catch (Exception $e) {
                $this->addFlash('danger', $e->getMessage());
                $this->addFlashValidationError();
            }
        }

        return $this->render('administrador/create.html.twig', [
            'form' => $form->createView(),
        ]);
    }

}
