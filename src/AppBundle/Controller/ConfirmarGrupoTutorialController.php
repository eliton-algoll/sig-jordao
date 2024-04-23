<?php

namespace AppBundle\Controller;

use AppBundle\CommandBus\ConfirmarGrupoTutorialCommand;
use AppBundle\Entity\CategoriaProfissional;
use AppBundle\Entity\GrupoAtuacao;
use AppBundle\Entity\Perfil;
use AppBundle\Entity\Projeto;
use AppBundle\Entity\ProjetoPessoa;
use AppBundle\Entity\ProjetoPessoaCursoGraduacao;
use League\Tactician\Bundle\Middleware\InvalidCommandException;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RedirectResponse;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\ParameterBag;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;

/**
 * @Security("is_granted(['COORDENADOR_PROJETO', 'ROLE_PREVIOUS_ADMIN'])")
 */
final class ConfirmarGrupoTutorialController extends ControllerAbstract
{

    /**
     * @Route("confirmar-grupo-tutorial", name="confirmar_grupo_tutorial")
     */
    public function indexAction()
    {
        $projeto = $this->getProjetoAutenticado();

        return $this->render('confirmar_grupo_atuacao/index.html.twig', compact('projeto'));
    }

    /**
     * @Route(
     *     "confirmar-grupo-tutorial/grid/{grupoAtuacao}",
     *     name="confirmar_grupo_tutorial_grid",
     *     options={"expose"=true}
     * )
     * @Security("is_granted('IS_GRUPO_BELONGS_PROJETO', grupoAtuacao)")
     *
     * @param GrupoAtuacao $grupoAtuacao
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function gridGrupoAction(GrupoAtuacao $grupoAtuacao)
    {

        $projetosPessaGrupoAtuacao = $this->get('app.projeto_pessoa_grupo_atuacao_repository')
            ->findByGrupoAtuacao($grupoAtuacao);

        $em = $this->getDoctrine()->getManager();
        $categoriasProfissionais_ = $em->getRepository(CategoriaProfissional::class)
            ->findBy(['stRegistroAtivo' => 'S']);

        $error = [];
        $command = ConfirmarGrupoTutorialCommand::create($grupoAtuacao);
        $error   = $command->validateComposicaoGrupoTutorial($projetosPessaGrupoAtuacao, $categoriasProfissionais_);


        return $this->render('confirmar_grupo_atuacao/grid-grupo-tutorial.html.twig',
            compact('projetosPessaGrupoAtuacao', 'grupoAtuacao', 'error')
        );
    }

    /**
     * @Route(
     *     "confirmar-grupo-tutorial/validate/{grupoAtuacao}",
     *     name="confirmar_grupo_tutorial_validade",
     *     options={"expose"=true}
     * )
     * @Security("is_granted('IS_GRUPO_BELONGS_PROJETO', grupoAtuacao)")
     *
     * @param GrupoAtuacao $grupoAtuacao
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     */
    public function validateGrupoAction(GrupoAtuacao $grupoAtuacao)
    {
        $command = ConfirmarGrupoTutorialCommand::create($grupoAtuacao);

        $validator = $this->get('validator');
        $errors = $validator->validate($command);

        $response = new \stdClass();
        $response->status = ($errors->count() === 0) ? true : false;
        $response->errors = [];

        if (0 !== $errors->count()) {
            foreach ($errors as $error) {
                $response->errors[] = $error->getMessage();
            }
        }

        return new JsonResponse($response);
    }

//    /**
//     * @Route(
//     *     "confirmar-grupo-tutorial/confirmar/{grupoAtuacao}",
//     *     name="confirmar_grupo_tutorial_confirmar",
//     *     options={"expose"=true}
//     * )
//     *
//     * @param GrupoAtuacao $grupoAtuacao
//     * @return RedirectResponse
//     */
//    public function confirmarGrupoAction(GrupoAtuacao $grupoAtuacao)
//    {
//        $command = ConfirmarGrupoTutorialCommand::create($grupoAtuacao);
//
//        try {
//            $this->getBus()->handle($command);
//            $this->addFlash('success',
//                sprintf('O grupo %s foi confirmado com sucesso.', $grupoAtuacao->getNoGrupoAtuacao()));
//        } catch (InvalidCommandException $e) {
//            $erros = [];
//            if (method_exists($e, 'getViolations')) {
//                foreach ($e->getViolations() as $violation) {
//                    $erros[] = $violation->getMessage();
//                }
//            }
//
//            $this->addFlash('danger', implode('\\n', $erros));
//        }
//
//        return $this->redirectToRoute('confirmar_grupo_tutorial');
//    }

//    /**
//     * @Route(
//     *     "confirmar-grupo-tutorial/confirmar",
//     *     name="confirmar_grupo_tutorial_confirmar",
//     *     options={"expose"=true}
//     * )
//     *
//     * @param GrupoAtuacao $grupoAtuacao
//     * @return RedirectResponse
//     */
// RedirectResponse
    /**
     * @param Request $request
     * @return JsonResponse
     *
     * @Security("is_granted('COORDENADOR_PROJETO')")
     * @Route("confirmar-grupo-tutorial/confirmar", name="confirmar_grupo_tutorial_confirmar", options={"expose"=true})
     */
    public function confirmarGruposAction(Request $request)
    {
        // Cria o objeto
        $response = new \stdClass();
        $response->status = true;

        $em = $this->getDoctrine()->getManager();
        $conn = $em->getConnection();

        try {
            // Precisa receber o projeto
            $projeto = $this->getProjetoAutenticado();
            $gruposAtuacao = [];
            $nrGruposEixoA = [];
            $nrGruposEixoB = [];
            $nrGruposEixoC = [];

            if( $projeto ) {
                $gruposAtuacao = $projeto->getGruposAtuacaoAtivosComEixoAtuacao();
                $categoriasProfissionais = $em->getRepository(CategoriaProfissional::class)
                                               ->findBy(['stRegistroAtivo' => 'S']);
            }

            // Validação das regras de negócio
            $errors = array();


            $nrGrupos = count($gruposAtuacao);
            $gruposEixoA = $projeto->getGruposAtuacaoAtivosPorEixoAtuacao('A');
            $nrGruposEixoA = ($gruposEixoA) ? count($gruposEixoA) : 0;
            $gruposEixoB = $projeto->getGruposAtuacaoAtivosPorEixoAtuacao('B');
            $nrGruposEixoB = ($gruposEixoB) ? count($gruposEixoB) : 0;
            $gruposEixoC = $projeto->getGruposAtuacaoAtivosPorEixoAtuacao('C');
            $nrGruposEixoC = ($gruposEixoC) ? count($gruposEixoC) : 0;

            if( $nrGrupos > 2 ) {
                if( $nrGrupos == 3 ) {
                    if( $nrGruposEixoA != 1 || $nrGruposEixoB != 1 || $nrGruposEixoC != 1 ) {
                        $errors[] = [['msg' => 'Projetos com 3 (três) grupos devem ser distribuídos entre os três eixos.']];
                    }
                }

                if( $nrGrupos > 3 ) {
                    if( $nrGruposEixoA >= 1 || $nrGruposEixoB >= 1 || $nrGruposEixoC >= 1 ) {
                        $errors[] = [['msg' => 'Projetos com 3 (três) ou mais grupos devem utilizar os eixos de forma proporcional']];
                    }
                }
            }


            // Realiza as validações
            foreach ($gruposAtuacao as $grupoAtuacao) {
                $projetosPessaGrupoAtuacao = $this->get('app.projeto_pessoa_grupo_atuacao_repository')->findByGrupoAtuacao($grupoAtuacao);
                $command = ConfirmarGrupoTutorialCommand::create($grupoAtuacao);
                $errorsGroup  = $command->validateComposicaoGrupoTutorial($projetosPessaGrupoAtuacao, $categoriasProfissionais);
                if( count($errorsGroup) ) {
                    $errors[] = $errorsGroup;
                }
            }

            if (count($errors) > 0) {
                $response->status = false;
                $response->errors = $errors;
            } else {
                foreach ($gruposAtuacao as $grupoAtuacao) { // aka Grupo Tutorial
                    $grupoAtuacao->confirmar();

                    // Persiste na base de dados
                    $em->persist($grupoAtuacao);
                    $em->flush();
                }
                $msg = (count($gruposAtuacao) == 1) ? 'O grupo foi confirmado com sucesso.' : 'Os grupos foram confirmados com sucesso.';
                $response->message = $msg;
            }
        } catch (\Exception $e) {
            echo $e->getMessage() . "\n";

            $erros = [];

            if (method_exists($e, 'getViolations')) {
                foreach ($e->getViolations() as $violation) {
                    $erros[] = $violation->getMessage();
                }
            }

            $response->status = false;
            $response->error = implode('\\n', $erros);
        }

        return new JsonResponse($response);
    }

}
