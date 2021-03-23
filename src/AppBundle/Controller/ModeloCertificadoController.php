<?php

namespace AppBundle\Controller;

use AppBundle\CommandBus\AtivarModeloCertificadoCommand;
use AppBundle\CommandBus\SalvarModeloCertificadoCommand;
use AppBundle\Entity\ModeloCertificado;
use AppBundle\Exception\UnexpectedCommandBehaviorException;
use AppBundle\Form\FiltroModeloCertificadoType;
use AppBundle\Form\ModeloCertificadoType;
use League\Tactician\Bundle\Middleware\InvalidCommandException;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Component\HttpFoundation\ParameterBag;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * @Security("is_granted('ADMINISTRADOR')")
 */
final class ModeloCertificadoController extends ControllerAbstract
{
    const SESSION_FILTER_NAME = 'modelo_certificado_filter';

    /**
     * @param Request $request
     * @return Response
     * 
     * @Route("/modelo-certificado", name="modelo_certificado")
     * @Method({"GET", "POST"})
     */
    public function index(Request $request)
    {
        if ($request->get('filter') === 'clear') {
            $this->removeSessionFilter($request);
            return $this->redirectToRoute('modelo_certificado');
        }

        $filter = $this->getSessionFilter($request);
        $filter->add($request->query->all());

        $form = $this->createForm(FiltroModeloCertificadoType::class, $filter->get('filtro_modelo_certificado'));
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $filter->replace(['filtro_modelo_certificado' => $form->getData()]);
        }

        $pagination = null;
        if ($filter->get('filtro_modelo_certificado')) {
            $this->setSessionFilter($request, $filter);
            $pagination = $this->get('app.modelo_certificado_query')->search($filter);
        }

        return $this->render('modelo_certificado/index.html.twig', [
            'form' => $form->createView(),
            'pagination' => $pagination
        ]);
    }

    /**
     * @param Request $request
     * @return Response
     *
     * @Route("/modelo-certificado/cadastrar", name="modelo_certificado_create")
     * @Method({"GET", "POST"})
     */
    public function create(Request $request)
    {
        $command = new SalvarModeloCertificadoCommand();
        $form = $this->createForm(ModeloCertificadoType::class, $command, [
            'validation_groups' => ['Default', 'Create']
        ]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            try {
                $this->getBus()->handle($command);
                $this->addFlash('success', 'Dados salvos com sucesso.');
                return $this->redirectToRoute('modelo_certificado');
            } catch (UnexpectedCommandBehaviorException $e) {
                $this->addFlash('danger', $e->getMessage());
            } catch (InvalidCommandException $e) {
                $this->addFlashValidationError();
            }
        }

        return $this->render('modelo_certificado/form.html.twig', [
            'form' => $form->createView()
        ]);
    }

    /**
     * @param Request $request
     * @param ModeloCertificado $modeloCertificado
     * @return Response
     *
     * @Route("/modelo-certificado/alterar/{id}", name="modelo_certificado_edit")
     * @Method({"GET", "POST"})
     */
    public function edit(Request $request, ModeloCertificado $modeloCertificado)
    {
        $command = new SalvarModeloCertificadoCommand($modeloCertificado);
        $form = $this->createForm(ModeloCertificadoType::class, $command);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            try {
                $this->getBus()->handle($command);
                $this->addFlash('success', 'Dados salvos com sucesso.');
                return $this->redirectToRoute('modelo_certificado');
            } catch (UnexpectedCommandBehaviorException $e) {
                $this->addFlash('danger', $e->getMessage());
            } catch (InvalidCommandException $e) {
                $this->addFlashValidationError();
            }
        }

        return $this->render('modelo_certificado/form.html.twig', [
            'form' => $form->createView(),
            'edit' => true,
        ]);
    }

    /**
     * @param ModeloCertificado $modeloCertificado
     * @return Response
     *
     * @Route("/modelo-certificado/visualizar/{id}", name="modelo_certificado_preview")
     * @Method({"GET"})
     */
    public function preview(ModeloCertificado $modeloCertificado)
    {
        $html = $this->renderView('modelo_certificado/preview.pdf.twig', [
            'modelo' => $modeloCertificado
        ]);

        $pdfFacade = $this->get('app.wkhtmltopdf_facade');
        return $pdfFacade->generate($html,
            $modeloCertificado->getNoModeloCertificado(),
            ['orientation' => 'Landscape'],
            false);
    }

    /**
     * @param ModeloCertificado $modeloCertificado
     * @return Response
     *
     * @Route("/modelo-certificado/ativar/{id}", name="modelo_certificado_active")
     * @Method({"GET", "POST"})
     */
    public function active(ModeloCertificado $modeloCertificado)
    {
        $command = new AtivarModeloCertificadoCommand($modeloCertificado);
        try {
            $this->getBus()->handle($command);
            $this->addFlash('success', 'Registro ativado com sucesso.');
        } catch (UnexpectedCommandBehaviorException $e) {
            $this->addFlash('danger', $e->getMessage());
        } catch (InvalidCommandException $e) {
            $this->addFlashValidationError();
        }
        return $this->redirectToRoute('modelo_certificado');
    }

    /**
     * @param Request $request
     * @return ParameterBag
     */
    private function getSessionFilter(Request $request)
    {
        $filter = $request->getSession()->get(self::SESSION_FILTER_NAME, new ParameterBag());
        if ($filter->has('filtro_modelo_certificado')) {
            $formData = $filter->get('filtro_modelo_certificado');
            if (!empty($formData['programa'])) {
                $formData['programa'] = $this->get('app.programa_query')->searchById($formData['programa']);
            }
            $filter->set('filtro_modelo_certificado', $formData);
        }
        return $filter;
    }

    /**
     * @param Request $request
     * @param ParameterBag $filter
     */
    private function setSessionFilter(Request $request, ParameterBag $filter)
    {
        if ($filter->has('filtro_modelo_certificado')) {
            $formData = $filter->get('filtro_modelo_certificado');
            if (!empty($formData['programa'])) {
                $formData['programa'] = $formData['programa']->getCoSeqPrograma();
            }
            $filter->set('filtro_modelo_certificado', $formData);
        }
        $request->getSession()->set(self::SESSION_FILTER_NAME, $filter);
    }

    private function removeSessionFilter(Request $request)
    {
        $request->getSession()->remove(self::SESSION_FILTER_NAME);
    }
}
