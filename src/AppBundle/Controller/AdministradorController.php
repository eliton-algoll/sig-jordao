<?php

namespace AppBundle\Controller;

use AppBundle\CommandBus\CadastrarAdministradorCommand;
use AppBundle\CommandBus\CadastrarInstituicaoCommand;
use AppBundle\CommandBus\CadastrarTextoSaudacaoCommand;
use AppBundle\CommandBus\CadastrarUsuarioCommand;
use AppBundle\CommandBus\InativarUsuarioCommand;
use AppBundle\Entity\TextoSaudacao;
use AppBundle\Entity\Usuario;
use AppBundle\Exception\BancoExistsException;
use AppBundle\Form\CadastrarAdministradorType;
use AppBundle\Form\CadastrarInstituicaoType;
use AppBundle\Form\CadastrarTextoSaudacaoType;
use AppBundle\Form\ConsultarAdministradorType;
use Composer\Package\Loader\ValidatingArrayLoader;
use Exception;
use League\Tactician\Bundle\Middleware\InvalidCommandException;
use Symfony\Component\HttpFoundation\ParameterBag;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;


/**
 * @Security("is_granted('ADMINISTRADOR')")
 */
final class AdministradorController extends ControllerAbstract
{
    /**
     *
     * @param Request $request
     * @return Response
     *
     * @Route("administrador", name="administrador")
     * @Method({"GET", "POST"})
     */
    public function index(Request $request)
    {
        $pagination = null;
        $em = $this->getDoctrine()->getManager();
        $form = $this->createForm(ConsultarAdministradorType::class);
        $form->handleRequest($request);
        $pagination = null;

        if (($request->isMethod('GET'))) {
            if( $form->isValid() ) {
                $request->query->add((array)$form->getData());
            }
            $pagination = $this->get('app.administrador_query')->search($request->query);
        }

        return $this->render('administrador/index.html.twig', [
            'form' => $form->createView(),
            'pagination' => $pagination,
        ]);
    }

    /**
     *
     * @param Request $request
     * @return Response
     *
     * @Route("saudacao", name="saudacao")
     * @Method({"GET", "POST"})
     */
    public function saudacaoAction(Request $request)
    {
        $textoSaudacao =  $this->get('app.texto_saudacao_query')->find();
        $command = new CadastrarTextoSaudacaoCommand();
        $command->setValuesByEntity($textoSaudacao);

        $form = $this->get('form.factory')->createNamed('cadastrar_saudacao', CadastrarTextoSaudacaoType::class, $command);
        $form->handleRequest($request);

        if ($form->isSubmitted()) {
            $data = new ParameterBag($request->request->get('cadastrar_saudacao'));
            $command->setDsTextoSaudacao($data->get('dsTextoSaudacao'));
            try {
                $this->getBus()->handle($command);
                $this->addFlash('success', 'Texto alterado com sucesso.');
            } catch (InvalidCommandException $e) {
                $this->addFlashValidationError();
            } catch (\InvalidArgumentException $e) {
                $this->addFlash('danger', $e->getMessage());
            }
        }

        return $this->render('saudacao/create.html.twig', [
            'form' => $form->createView()
        ]);
    }

    /**
     *
     * @param Request $request
     * @return Response
     *
     * @Route("/administrador/create", name="adm_create")
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

    /**
     *
     * @param Usuario $usuario
     * @return RedirectResponse
     *
     * @Route("administrador/activate/{id}", name="adm_activate", options={"expose"=true})
     * @Method({"GET"})
     */
    public function activate(Usuario $usuario)
    {
        try {
            $command = new InativarUsuarioCommand($usuario);
            $this->getBus()->handle($command);

            $this->addFlash('success', 'Operação ativar/desativar realizada com sucesso!');
        } catch (Exception $e) {
            $this->addFlash('warning', $e->getMessage());
            $this->addFlash('danger', 'Ocorreu um erro ao executar a operação.');
        }

        return $this->redirectToRoute('administrador');
    }

}
