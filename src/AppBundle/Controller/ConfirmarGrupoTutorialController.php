<?php

namespace AppBundle\Controller;

use AppBundle\CommandBus\ConfirmarGrupoTutorialCommand;
use AppBundle\Entity\GrupoAtuacao;
use League\Tactician\Bundle\Middleware\InvalidCommandException;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RedirectResponse;

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

        return $this->render('confirmar_grupo_atuacao/grid-grupo-tutorial.html.twig',
            compact('projetosPessaGrupoAtuacao', 'grupoAtuacao')
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

    /**
     * @Route(
     *     "confirmar-grupo-tutorial/confirmar/{grupoAtuacao}",
     *     name="confirmar_grupo_tutorial_confirmar",
     *     options={"expose"=true}
     * )
     *
     * @param GrupoAtuacao $grupoAtuacao
     * @return RedirectResponse
     */
    public function confirmarGrupoAction(GrupoAtuacao $grupoAtuacao)
    {
        $command = ConfirmarGrupoTutorialCommand::create($grupoAtuacao);

        try {
            $continuar = true;

            // TODO: Precisa receber 1 ou mais grupos

            // TODO: Validar o RGN169

            // TODO: Salvar

            $this->getBus()->handle($command);
            $this->addFlash('success',
                sprintf('O grupo %s foi confirmado com sucesso.', $grupoAtuacao->getNoGrupoAtuacao()));
        } catch (InvalidCommandException $e) {
            $erros = [];
            if (method_exists($e, 'getViolations')) {
                foreach ($e->getViolations() as $violation) {
                    $erros[] = $violation->getMessage();
                }
            }

            $this->addFlash('danger', implode('\\n', $erros));
        }

        return $this->redirectToRoute('confirmar_grupo_tutorial');
    }

}