<?php

namespace AppBundle\Controller;

use AppBundle\CommandBus\CadastrarAdministradorCommand;
use AppBundle\CommandBus\CadastrarInstituicaoCommand;
use AppBundle\CommandBus\CadastrarProjetoCommand;
use AppBundle\Form\CadastrarAdministradorType;
use AppBundle\Form\CadastrarInstituicaoType;
use AppBundle\Form\CadastrarProjetoType;
use AppBundle\Form\ConsultarAdministradorType;
use AppBundle\Form\ConsultarInstituicaoType;
use AppBundle\Form\PesquisarCampusType;
use AppBundle\Form\PesquisarProjetoType;
use AppBundle\Form\PesquisarSecretariaType;
use Exception;
use League\Tactician\Bundle\Middleware\InvalidCommandException;
use Symfony\Component\HttpFoundation\ParameterBag;
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

        $queryParams = $request->query->get('consultar_instituicao', array());
        $queryParams['page'] = $request->query->get('page', 1);

        $pagination = $this
            ->get('app.instituicao_query')
            ->searchInst(
                new ParameterBag($queryParams)
            );

        $form = $this->createForm(ConsultarInstituicaoType::class);

        return $this->render('instituicao/index.html.twig', [
            'form' => $form->createView(),
            'queryParams' => $queryParams,
            'pagination' => $pagination,
        ]);
    }

    /**
     * @Route("/instituicao/cadastrar", name="instituicao_create")
     */
    public function cadastrarInstAction(Request $request)
    {
        $command = new CadastrarInstituicaoCommand();

        $form = $this->get('form.factory')->createNamed('cadastrar_instituicao', CadastrarInstituicaoType::class, $command);
        $form->handleRequest($request);

        if ($form->isSubmitted()) {

            $data = new ParameterBag($request->request->get('cadastrar_instituicao'));

            $areasTematicasSaude = [];
            if ( !is_null($data->get('areasTematicasSaude')) ) {
                $areasTematicasSaude = $data->get('areasTematicasSaude');
            }

            $areasTematicasCienciasHumanas = [];
            if ( !is_null($data->get('areasTematicasCienciasHumanas')) ) {
                $areasTematicasCienciasHumanas = $data->get('areasTematicasCienciasHumanas');
            }

            $areasTematicasCienciasSociais = [];
            if ( !is_null($data->get('areasTematicasCienciasSociais')) ) {
                $areasTematicasCienciasSociais = $data->get('areasTematicasCienciasSociais');
            }


            $areasTematicas = array_merge($areasTematicasSaude, $areasTematicasCienciasHumanas);
            $areasTematicas = array_merge($areasTematicas, $areasTematicasCienciasSociais);

            # bind manual devido a complexidade do formulário
            $command
                ->setNuSipar($data->get('nuSipar'))
                ->setDsObservacao($data->get('dsObservacao'))
                ->setStOrientadorServico($data->get('stOrientadorServico'))
                ->setPublicacao($data->get('publicacao'))
                ->setAreasTematicas($areasTematicas)
                ->setCampus($data->get('campus'))
                ->setSecretarias($data->get('secretarias'));
            try {
                $this->getBus()->handle($command);
                $this->addFlash('success', 'Projeto cadastrado com sucesso');
                return $this->redirectToRoute('projeto');

            } catch (InvalidCommandException $e) {
                $this->addFlashValidationError();
            }
        }

        return $this->render(
            'instituicao/create.html.twig',
            array(
                'form' => $form->createView(),
            )
        );
    }


    /**
     *
     * @param Request $request
     * @return Response
     *
     * @Route("instituicao/atualizar/{instituicao}", name="instituicao_atualizar")
     * @Method({"GET", "POST"})
     */
    public function atualizarInstAction(Request $request)
    {

        $form = $this->createForm(ConsultarInstituicaoType::class);

        return $this->render('instituicao/create.html.twig', [
            'form' => $form->createView(),
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


}
