<?php

namespace App\Controller;

use App\CommandBus\CadastrarInstituicaoCommand;
use App\CommandBus\CadastrarTipoAreaTematicaCommand;
use App\CommandBus\InativarTipoAreaTematicaCommand;
use App\CommandBus\InativarUsuarioCommand;
use App\Entity\Instituicao;
use App\Entity\TipoAreaTematica;
use App\Entity\Usuario;
use App\Form\CadastrarInstituicaoType;
use App\Form\CadastrarTipoAreaTematicaType;
use App\Form\ConsultarAreaFormacaoType;
use League\Tactician\Bundle\Middleware\InvalidCommandException;
use Symfony\Component\HttpFoundation\ParameterBag;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Component\HttpFoundation\Response;

/**
 * @Security("is_granted('ADMINISTRADOR')")
 */
class TipoAreaTematicaController extends ControllerAbstract
{
    /**
     *
     * @param Request $request
     * @return Response
     *
     * @Route("/area/formacao", name="curso_formacao")
     * @Method({"GET", "POST"})
     */
    public function index(Request $request)
    {
        $queryParams = $request->query->get('consultar_area_formacao', array());
        $queryParams['page'] = $request->query->get('page', 1);

        $pagination = $this
            ->get('app.tipo_area_tematica_query')
            ->search(
                new ParameterBag($queryParams)
            );

        $form = $this->createForm(ConsultarAreaFormacaoType::class);

        return $this->render('area_formacao/index.html.twig', [
            'form' => $form->createView(),
            'queryParams' => $queryParams,
            'pagination' => $pagination,
        ]);
    }

    /**
     * @Route("/area/formacao/cadastrar", name="area_formacao_create")
     */
    public function cadastrarAction(Request $request)
    {
        $command = new CadastrarTipoAreaTematicaCommand();

        $form = $this->get('form.factory')->createNamed('consultar_area_formacao', CadastrarTipoAreaTematicaType::class, $command);
        $form->handleRequest($request);

        if ($form->isSubmitted()) {

            $data = new ParameterBag($request->request->get('consultar_area_formacao'));
            $command
                ->setDsTipoAreaTematica(mb_strtoupper($data->get('dsTipoAreaTematica')))
                ->setTpAreaTematica(1)
                ->setTpAreaFormacao($data->get('tpAreaFormacao'));
            try {
                $this->getBus()->handle($command);
                $this->addFlash('success', 'Curso de Formação cadastrado com sucesso.');
                return $this->redirectToRoute('curso_formacao');

            } catch (InvalidCommandException $e) {
                $this->addFlashValidationError();
            } catch (\InvalidArgumentException $e) {
                $this->addFlash('danger', $e->getMessage());
            }

        }

        return $this->render(
            'area_formacao/create.html.twig',
            array(
                'form' => $form->createView(),
                'edit' => false
            )
        );
    }

    /**
     *
     * @param Request $request
     * @return Response
     *
     * @Route("/area/formacao/atualizar/{tipoAreaTematica}", name="curso_formacao_atualizar")
     * @Method({"GET", "POST"})
     */
    public function atualizarAction(Request $request, TipoAreaTematica $tipoAreaTematica)
    {
        $command = new CadastrarTipoAreaTematicaCommand();
        $command->setValuesByEntity($tipoAreaTematica);

        $form = $this->get('form.factory')->createNamed('consultar_area_formacao', CadastrarTipoAreaTematicaType::class, $command);
        $form->handleRequest($request);

        if ($form->isSubmitted()) {
            $data = new ParameterBag($request->request->get('consultar_area_formacao'));
            $command
                ->setDsTipoAreaTematica(mb_strtoupper($data->get('dsTipoAreaTematica')))
                ->setTpAreaFormacao($data->get('tpAreaFormacao'));

            try {
                $this->getBus()->handle($command);
                $this->addFlash('success', 'Curso de Formação alterado com sucesso.');
                return $this->redirectToRoute('curso_formacao');
            } catch (InvalidCommandException $e) {
                $this->addFlashValidationError();
            } catch (\InvalidArgumentException $e) {
                $this->addFlash('danger', $e->getMessage());
            }
        }

        return $this->render('area_formacao/create.html.twig', [
            'form' => $form->createView(),
            'edit' => true,
        ]);
    }

    /**
     *
     * @param TipoAreaTematica $tpAreaTematica
     * @return RedirectResponse
     *
     * @Route("/area/formacao/{id}", name="curso_formacao_activate", options={"expose"=true})
     * @Method({"GET"})
     */
    public function activate(TipoAreaTematica $tpAreaTematica)
    {
        try {
            $command = new InativarTipoAreaTematicaCommand($tpAreaTematica);
            $this->getBus()->handle($command);

            $this->addFlash('success', 'Operação ativar/desativar realizada com sucesso!');
        } catch (\Exception $e) {
            $this->addFlash('warning', $e->getMessage());
            $this->addFlash('danger', 'Ocorreu um erro ao executar a operação.');
        }

        return $this->redirectToRoute('curso_formacao');
    }
}