<?php

namespace App\Controller;

use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use App\CommandBus\RecuperarSenhaCommand;
use App\Form\EsqueciMinhaSenhaType;
use App\Form\LogarType;
use App\Form\SelecionarPerfilType;
use App\Controller\ControllerAbstract;
use App\Entity\PessoaPerfil;
use App\Entity\Projeto;
use Symfony\Component\Security\Core\Exception\BadCredentialsException;

class DefaultController extends ControllerAbstract
{
    /**
     * @Route("/login", name="login")
     */
    public function loginAction(Request $request)
    {
        $authenticationUtils = $this->get('security.authentication_utils');
        $error = $authenticationUtils->getLastAuthenticationError();
        $lastUsername = $authenticationUtils->getLastUsername();

        if ($error instanceof BadCredentialsException) {
            $token = $error->getToken();
            $fieldErrors = [];
            if (!$token->getUser()) {
                $fieldErrors[] = 'Login';
            }
            if (!$token->getCredentials()) {
                $fieldErrors[] = 'Senha';
            }

            if (0 === count($fieldErrors)) {
                $this->addFlash('danger', 'Login ou Senha inválida!');
            } else {
                $this->addFlash(
                    'danger',
                    sprintf('É obrigatório o preenchimento do(s) campo(s): %s', implode(' e ', $fieldErrors))
                );
            }
        }

        return $this->render(
            'default/login.html.twig',
            array(
                'form' => $this->createForm(LogarType::class)->createView(),
                'lastUsername' => $lastUsername
            )
        );
    }

    /**
     * @Route("/", name="default_index", options={"expose"=true})
     */
    public function indexAction(Request $request)
    {
        $dsPrograma = null;
        $coPrograma = null;

        $programa = null;
        if( ($this->getPessoaPerfilAutenticado()) &&
            !is_null($this->getPessoaPerfilAutenticado()->getPerfil()) &&
            !$this->getPessoaPerfilAutenticado()->getPerfil()->isAdministrador()) {
            $programa = $this->getProjetoAutenticado()->getPublicacao()->getPrograma();
        }

       // $saudacao = $this->get('app.texto_saudacao_query')->find();
        $saudacao = ' a ';
        return $this->render(
            'default/index.html.twig',
            array(
                'pessoaPerfil' => $this->getPessoaPerfilAutenticado(),
                'programa' => $programa,
                'saudacao' => $saudacao //$saudacao->getDsTextoSaudacao()
            )
        );
    }    
    
    /**
     * @Route("/direcionar-login", name="default_direcionar_login")
     */
    public function direcionarLoginAction(Request $request)
    {
        $params = $request->request->get('selecionar_perfil');
        
        if ($params) {
            $this->registrarTokenAcesso(
                $params['coPessoaPerfil'], 
                isset($params['coProjeto']) ? $params['coProjeto'] : null
            );
            return $this->redirectToRoute('default_index');
        }
        
        $pessoasPerfis = $this->getUser()->getPessoaFisica()->getPessoasPerfisAtivos();
        
        # selciona perfil se tiver mais de um perfil ou mais de um projeto
        if ($pessoasPerfis->count() > 1 || $pessoasPerfis->first()->getProjetosPessoasAtivos()->count() > 1) {
            return $this->redirectToRoute('default_selecionar_perfil');
        }
        
        $projeto = null;
        
        if (!$pessoasPerfis->first()->getPerfil()->isAdministrador()) {
            $projeto = $pessoasPerfis->first()->getProjetosPessoasAtivos()->first()->getProjeto();
        }
        
        $this->registrarTokenAcesso($pessoasPerfis->first(), $projeto);
        
        return $this->redirectToRoute('default_index');
    }
    
    /**
     * Registrar no token da sessão as informações do projeto e perfil selecionados
     * @param PessoaPerfil | integer $pessoaPerfil
     * @param Projeto | integer | null $projeto
     * @throws \DomainException
     */
    protected function registrarTokenAcesso($pessoaPerfil, $projeto = null)
    {
        if (!$pessoaPerfil instanceof PessoaPerfil) {
            $pessoaPerfil = $this->get('app.perfil_query')->findPessoaPerfilById($pessoaPerfil);
        }
        
        if ($pessoaPerfil->getPerfil()->isAdministrador()) {
            $projeto = null;
        }
        
        if (null !== $projeto && $projeto instanceof Projeto) {
            $projeto = $projeto->getCoSeqProjeto();
        }
        
//        $this->getUser()->checkIdentidade($pessoaPerfil, $projeto);
        
        $tokenStorage = $this->container->get('security.token_storage');
        $tokenStorage->getToken()->setAttribute('coPessoaPerfil', $pessoaPerfil->getCoSeqPessoaPerfil());
        $tokenStorage->getToken()->setAttribute('coProjeto', $projeto);
    }

    /**
     * @Route("/selecionar-perfil", name="default_selecionar_perfil")
     */
    public function selecionarPerfilAction(Request $request)
    {
        $form = $this->createForm(SelecionarPerfilType::class, null, array('usuario' => $this->getUser()));
        
        return $this->render('default/selecionar_perfil.html.twig', array(
            'form' => $form->createView()
        ));
    }
    
    /**
     * @Route("/projetos-autorizados/{pessoaPerfil}", name="default_get_projetos_autorizados", options={"expose"=true})
     * @return JsonResponse
     */
    public function getProjetosAutorizadosAction(Request $request, PessoaPerfil $pessoaPerfil)
    {
        $projetosAutorizados = $this->get('app.projeto_query')->listProjetosAutorizados($pessoaPerfil);
        
        return new JsonResponse(array(
            'status' => true, 
            'result' => $projetosAutorizados
        ));
    }
    
    /**
     * @return Response
     */
    public function cabecalhoAction()
    {
        $projeto      = $this->getProjetoAutenticado();
        $pessoaPerfil = $this->getPessoaPerfilAutenticado();

        $params = [];
 
        if(!is_null($pessoaPerfil)) {
            $params = [
                'dsPerfil'   => $pessoaPerfil->getPerfil()->getDsPerfil(),
                'noPrograma' => $projeto ? $projeto->getPublicacao()->getPrograma()->getDsPrograma() : null,
                'nuSipar'    => $projeto ? $projeto->getNuSipar() : null,
            ];
        }
        
        return $this->render('default/cabecalho.html.twig', $params);
    }
    
    /**
     * 
     * @Route("/esqueci-minha-senha", name="esqueci_minha_senha")
     * @param $request Request
     * @return Response
     */
    public function esqueciMinhaSenhaAction(Request $request)
    {
        $command = new RecuperarSenhaCommand();
        
        $form = $this->createForm(EsqueciMinhaSenhaType::class, $command);
        $form->handleRequest($request);
        
        if ($form->isSubmitted()) {
            try {
                $this->getBus()->handle($command);                
                $this->addFlash('success', sprintf('Um e-mail foi enviado para %s', $command->getEmail()));
                return $this->redirectToRoute('login');
            } catch(\Exception $e) {
                $this->addFlash('danger', $e->getMessage());
            }
        }
        
        return $this->render('default/esqueci_minha_senha.html.twig', array(
            'form' => $form->createView()
        ));
    }
}
