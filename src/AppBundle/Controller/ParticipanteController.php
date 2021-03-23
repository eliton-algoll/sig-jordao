<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\ParameterBag;
use League\Tactician\Bundle\Middleware\InvalidCommandException;
use AppBundle\Form\PesquisarParticipanteType;
use AppBundle\Form\CadastrarParticipanteType;
use AppBundle\Form\AtualizarParticipanteType;
use AppBundle\Form\TelefoneType;
use AppBundle\CommandBus\CadastrarUsuarioCommand;
use AppBundle\CommandBus\CadastrarParticipanteCommand;
use AppBundle\CommandBus\AtualizarParticipanteCommand;
use AppBundle\Entity\ProjetoPessoa;
use AppBundle\CommandBus\InativarParticipanteCommand;
use Symfony\Component\HttpFoundation\JsonResponse;

/**
 * @Security("is_granted(['ADMINISTRADOR', 'COORDENADOR_PROJETO', 'ROLE_PREVIOUS_ADMIN'])")
 */
class ParticipanteController extends ControllerAbstract
{
    /**
     * @Route("/participante", name="participante")
     * @param Request $request
     */
    public function allAction(Request $request)
    {
        $projeto = $this->getProjetoAutenticado();;

        $queryParams = $request->query->get('pesquisar_participante', array());
        $queryParams['page'] = $request->query->get('page', 1);
        $queryParams['projeto'] = $this->getProjetoAutenticado();
        //$queryParams['pessoaPerfil'] = $this->getPessoaPerfilAutenticado();
        
        $pagination = $this
            ->get('app.participante_query')
            ->search(
                new ParameterBag($queryParams)
            );

        $form = $this->createForm(
            PesquisarParticipanteType::class,
            null,
            [
                'perfil' => $this->getPessoaPerfilAutenticado()->getPerfil(),
                'projeto' => $this->getProjetoAutenticado(),
            ]
        );
        
        return $this->render(
            'participante/all.html.twig', 
            array(
                'pagination' => $pagination,
                'queryParams' => $queryParams,
                'projeto' => $projeto,
                'form' => $form->createView()
            )
        );
    }
    
    /**
     * @Route("/participante/cadastrar", name="participante_cadastrar")
     * @param Request $request
     */
    public function cadastrarAction(Request $request)
    {
        if ($this->isGranted(array('HAS_FOLHA_PAGAMENTO_ABERTA', 'HAS_FOLHA_PAGAMENTO_FECHADA')) && 
            !$this->isGranted('HAS_AUTORIZACAO_CADASTRO_PARTICIPANTE')
        ) {
            $this->addFlash('danger', 'Não é possível cadastrar participantes enquanto a folha de pagamento do programa estiver aberta ou até que seja cadastrado um período excepcional de abertura de cadastro pelo administrador do sistema.');
            return $this->redirectToRoute('participante');
        }

        $projeto = $this->getProjetoAutenticado();
        $command = new CadastrarParticipanteCommand();
        $command->setProjeto($projeto);


        $form = $this->createForm(CadastrarParticipanteType::class, $command, array(
            'projeto' => $projeto,
            'pessoaPerfil' => $this->getPessoaPerfilAutenticado()
        ));

        $form->handleRequest($request);

        if ($form->isSubmitted()) {
            $data = new ParameterBag($request->request->get('cadastrar_participante'));
           
            # bind manual devido a complexidade do formulário
            $command
                ->setPerfil($data->get('perfil'))
                ->setNuCpf($data->getDigits('nuCpf'))
                ->setCoAgenciaBancaria($data->get('coAgenciaBancaria'))
                ->setNoLogradouro($data->get('noLogradouro'))
                ->setNuLogradouro($data->get('nuLogradouro'))
                ->setDsComplemento($data->get('dsComplemento'))
                ->setNoBairro($data->get('noBairro'))
                ->setCoMunicipioIbge($data->get('coMunicipioIbge'))
                ->setCategoriaProfissional($data->get('categoriaProfissional'))
                ->setTitulacao($data->get('titulacao'))
                ->setCursoGraduacao($data->get('cursoGraduacao'))
                ->setNuAnoIngresso($data->get('nuAnoIngresso'))
                ->setNuMatriculaIES($data->get('nuMatriculaIES'))
                ->setNuSemestreAtual($data->get('nuSemestreAtual'))
                ->setCoCep($data->get('coCep'))
                ->setTelefones($data->get('telefones'))
                ->setAreaTematica($data->get('areaTematica'));

            $cadastrarUsuarioCommand = new CadastrarUsuarioCommand();
            $cadastrarUsuarioCommand->setNuCpf($command->getNuCpf());

            try {
                
                $projetoPessoa = $this->getBus()->handle($command);

                $cadastrarUsuarioCommand->setProjetoPessoa($projetoPessoa);
                
                $this->getBus()->handle($cadastrarUsuarioCommand);
                
                $link = $this->generateUrl('participante_termo', array('projetoPessoa' => $projetoPessoa->getCoSeqProjetoPessoa()));
                
                $message  = 'Participante cadastrado com sucesso. ';
                $message .= 'Clique <a href="' . $link . '" target="_blank">aqui</a> para imprimir o <strong>Termo de compromisso.</strong>';
                
                $this->addFlash('success', $message);

                if ($projeto && $projeto->getPublicacao()->getPrograma()->isGrupoTutorial()) {
                    $this->addFlash('info', 'Após cadastrar todos os participantes do grupo, clicar no botão Confirmar Grupo Tutorial.');
                }
                
                return $this->redirectToRoute('participante');
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
                
                $this->addFlashValidationError();
                $this->addFlash('danger', implode('</br>', $erros));
            } catch (\UnexpectedValueException $e) {
                $this->addFlash('danger', $e->getMessage());                
            } catch (\InvalidArgumentException $e) {
                $this->addFlash('danger', $e->getMessage());                
            } catch (\DomainException $e) {
                $this->addFlash('danger', $e->getMessage());
            } catch (\Exception $e) {
                $this->addFlash('danger', $e->getMessage());
                $this->addFlashValidationError();
            }
        }

        return $this->render(
            'participante/cadastrar.html.twig', 
            array(
                'form' => $form->createView(),
                'formTelefone' => $this->createForm(TelefoneType::class)->createView(),
                'projeto' => $projeto,
            )
        );
    }
    
    /**
     * @Security("is_granted('IS_PARTICIPANTE', projetoPessoa)")
     * @Route("/participante/atualizar/{projetoPessoa}", name="participante_atualizar")
     * @param Request $request
     */
    public function atualizarAction(Request $request, ProjetoPessoa $projetoPessoa)
    {
        if ($this->isGranted(array('HAS_FOLHA_PAGAMENTO_ABERTA', 'HAS_FOLHA_PAGAMENTO_FECHADA')) && 
            !$this->isGranted('HAS_AUTORIZACAO_CADASTRO_PARTICIPANTE')
        ) {
            $this->addFlash('danger', 'Não é possível alterar as informações dos participantes enquanto a folha de pagamento do programa estiver aberta ou até que seja cadastrado um período excepcional de abertura de cadastro pelo administrador do sistema.');
            return $this->redirectToRoute('participante');
        }
        
        $command = new AtualizarParticipanteCommand($projetoPessoa);
        
        $form = $this->get('form.factory')->createNamed(
            'atualizar_participante', 
            AtualizarParticipanteType::class, 
            $command, 
            array(
                'projeto' => $this->getProjetoAutenticado(),
                'pessoaPerfil' => $this->getPessoaPerfilAutenticado(),
                'projetoPessoaParticipante' => $projetoPessoa
            )
        );
        
        $form->handleRequest($request);
        
        if ($form->isSubmitted()) {
            
            $data = new ParameterBag($request->request->get('atualizar_participante'));
            
            # bind manual devido a complexidade do formulário
            $command
                ->setPerfil($data->get('perfil'))
                ->setNuCpf($data->getDigits('nuCpf'))
                ->setCoAgenciaBancaria($data->get('coAgenciaBancaria'))
                ->setNoLogradouro($data->get('noLogradouro'))
                ->setNuLogradouro($data->get('nuLogradouro'))
                ->setDsComplemento($data->get('dsComplemento'))
                ->setNoBairro($data->get('noBairro'))
                ->setCoUf($data->get('coUf'))
                ->setCoMunicipioIbge($data->get('coMunicipioIbge'))
                ->setCategoriaProfissional($data->get('categoriaProfissional'))
                ->setTitulacao($data->get('titulacao'))
                ->setCursoGraduacao($data->get('cursoGraduacao'))
                ->setNuAnoIngresso($data->get('nuAnoIngresso'))
                ->setNuMatriculaIES($data->get('nuMatriculaIES'))
                ->setNuSemestreAtual($data->get('nuSemestreAtual'))
                ->setCoCep($data->get('coCep'))
                ->setTelefones($data->get('telefones'))
                ->setAreaTematica($data->get('areaTematica'));
            
            try {
                $this->getBus()->handle($command);
                $this->addFlash('success', 'Participante atualizado com sucesso');

                if (
                    !$projetoPessoa->getPessoaPerfil()->getPerfil()->isCoordenadorProjeto() &&
                    $projetoPessoa->getProjeto()->getPublicacao()->getPrograma()->isGrupoTutorial()
                ) {
                    $this->addFlash('info', 'Após atualizar cadastro de participante, clicar no botão Confirmar Grupo Tutorial. Todos os grupos que tiveram alterações precisam ser confirmados.');
                }

                return $this->redirectToRoute('participante');
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
            } catch (\UnexpectedValueException $e) {
                $this->addFlash('danger', $e->getMessage());
            } catch (\InvalidArgumentException $e) {
                $this->addFlash('danger', $e->getMessage());
            } catch ( \Exception $e) {
                $this->addFlashValidationError();
                $this->addFlash('danger', $e->getMessage());
            }
        }
        
        return $this->render(
            'participante/atualizar.html.twig', 
            array(
                'form' => $form->createView(),
                'formTelefone' => $this->createForm(TelefoneType::class)->createView(),
                'projetoPessoa' => $projetoPessoa
            )
        );
    }
    
    /**
     * @Security("is_granted('IS_PARTICIPANTE', projetoPessoa)")
     * @Route("/participante/inativar/{projetoPessoa}", name="participante_inativar", options={"expose"=true})
     * @param Request $request
     * @param ProjetoPessoa $projetoPessoa
     */
    public function inativarAction(Request $request, ProjetoPessoa $projetoPessoa)
    {
        if ($this->isGranted(array('HAS_FOLHA_PAGAMENTO_ABERTA', 'HAS_FOLHA_PAGAMENTO_FECHADA'))) {
            $return = array(
                'status' => false,
                'message' => 'Não é possível alterar as informações dos participantes enquanto a folha de pagamento do programa estiver aberta.'
            );
        } else {
            $command = new InativarParticipanteCommand();
            $command->setProjetoPessoa($projetoPessoa);

            try {
                $this->getBus()->handle($command);

                $return = array(
                    'status' => true,
                    'message' => 'Cadastro de participante removido com sucesso'
                );

            } catch (\Exception $e) {

                $return = array(
                    'status' => false,
                    'message' => $e->getMessage()
                );
            }
        }
        
        return new JsonResponse($return);
    }

    /**
     * @Security("is_granted('IS_PARTICIPANTE', projetoPessoa)")
     * @Route("/participante/termo/{projetoPessoa}", name="participante_termo")
     * @param Request $request
     * @return type
     */
    public function termoAction(Request $request, ProjetoPessoa $projetoPessoa)
    {  
        $dsPrograma = $projetoPessoa->getProjeto()->getPublicacao()->getPrograma()->getDsPrograma();   
        $dadoPessoal = $projetoPessoa->getPessoaPerfil()->getPessoaFisica()->getDadoPessoal();       
        $agenciaBancaria = $this->get('app.participante_query')->getAgenciaBancariaByDadoPessoal($dadoPessoal);

        return $this->render(
            'participante/termo.html.twig', 
            array(
                'projetoPessoa' => $projetoPessoa,
                'agenciaBancaria' => $agenciaBancaria, 
                'dsPrograma' => $dsPrograma
            )
        );
    }
}
