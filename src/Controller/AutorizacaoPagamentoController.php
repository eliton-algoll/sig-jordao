<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use League\Tactician\Bundle\Middleware\InvalidCommandException;
use App\CommandBus\AutorizarPagamentoCommand;
use App\Entity\ProjetoFolhaPagamento;
use Doctrine\DBAL\Exception\NotNullConstraintViolationException;

class AutorizacaoPagamentoController extends ControllerAbstract
{
    /**
     * @Route("autorizacao-pagamento", name="autorizacao_pagamento")
     * @return Response
     */
    public function indexAction()
    {
        $folhas = $this->get('app.folha_pagamento_query')->listProjetoFolhaPagamento($this->getProjetoAutenticado());
        
        return $this->render('autorizacao_pagamento/index.html.twig', array(
            'folhas' => $folhas
        ));
    }
    
    /**
     * @Route("autorizacao-pagamento/autoriza", name="autorizacao_pagamento_autoriza")
     * @return Response
     */
    public function autorizaAction()
    {
        if (!$this->isGranted(array('HAS_FOLHA_PAGAMENTO_ABERTA'))) {
            $this->addFlash('danger', 'Não há folha aberta no momento.');
            return $this->redirectToRoute('autorizacao_pagamento');
        }

        $projeto      = $this->getProjetoAutenticado();
        $folha        = $this->get('app.folha_pagamento_query')->findFolhaAbertaByPublicacao($projeto->getPublicacao());
        $projetoFolha = $this->get('app.folha_pagamento_query')->findProjetoFolhaPagamento($projeto, $folha);
        
        if ($projetoFolha) {            
            $this->addFlash('danger','A autorização da folha de pagamento do projeto desse mês já foi efetuada');
            return $this->redirectToRoute('autorizacao_pagamento');
        }

        if ($projeto->getPublicacao()->getPrograma()->isGrupoTutorial()) {
            $grupos = $this->get('app.grupo_atuacao_repository')->findGruposTutorialConfirmadoByProjeto($projeto);
        } else {
            $grupos = $this->get('app.grupo_atuacao_query')->findByProjeto($projeto);
        }
        
        return $this->render('autorizacao_pagamento/autoriza.html.twig', array(
            'folha'     => $folha,
            'projeto'   => $projeto,
            'grupos'    => $grupos
        ));
    }

    /**
     * @Security("is_granted('HAS_FOLHA_PAGAMENTO_ABERTA')")
     * @Route("autorizacao-pagamento/autorizar", name="autorizacao_pagamento_autoriza_pagamento")
     * @param Request $request
     * @return Response
     */
    public function autorizaPagamentoAction(Request $request)
    {
        $folha = $this->get('app.folha_pagamento_query')->findFolhaAbertaByPublicacao($this->getProjetoAutenticado()->getPublicacao());
        
        $command = new AutorizarPagamentoCommand();
        $command->setParticipantes((array) $request->request->get('autorizacao_pagamento_projeto_pessoa')); 
        $command->setCoordenadorDeProjetoNaoVoluntario($request->request->get('autorizacao_pagamento_coordenador'));
        if( $request->request->get('autorizacao_pagamento_orientador') ) {
            $command->setOrientadorDeProjetoNaoVoluntario($request->request->get('autorizacao_pagamento_orientador'));
        }
        $command->setJustificativa($request->request->get('autorizacao_pagamento_justificativa'));
        $command->setProjeto($this->getProjetoAutenticado());
        $command->setFolhaPagamento($folha);
        $command->setStDeclaracao($request->request->get('stDeclaracao'));
        $command->setPessoaPerfil($this->getPessoaPerfilAutenticado());

        try {
            $this->getBus()->handle($command);
            $this->addFlash('success', 'Folha de pagamento autorizada com sucesso.');
            return $this->redirectToRoute('autorizacao_pagamento');
            
        } catch (InvalidCommandException $e) {
            $erros = array();

            if (method_exists($e, 'getViolations')) {
                foreach ($e->getViolations() as $violation) {
                    $erros[] = $violation->getMessage();
                }
            }
            $this->addFlash('danger', implode('</br>', $erros));
        } catch (\UnexpectedValueException $ex) {
            $this->addFlash('danger', $ex->getMessage());            
        } catch (NotNullConstraintViolationException $ex) {
            $this->addFlash('danger', 'Não é possível autorizar pagamento para um participante com bolsa de valor inválido. Verificar coluna Valor da Bolsa.');
        } catch (\Exception $ex) {
            $this->addFlashValidationError();
        }
        return $this->redirectToRoute('autorizacao_pagamento_autoriza');
    }
    
    /**
     * @param ProjetoFolhaPagamento $projetoFolha
     * @Route("autorizacao-pagamento/{projetoFolha}", name="autorizacao_pagamento_get")
     * @return Response
     */
    public function visualizarAction(ProjetoFolhaPagamento $projetoFolha)
    {
        return $this->render('autorizacao_pagamento/visualizar.html.twig', array(
            'projetoFolha' => $projetoFolha
        ));
    }

    /**
     * @Route("autorizacao-pagamento/print/{projetoFolhaPagamento}", name="autorizacao_pagamento_print")
     * @param ProjetoFolhaPagamento $projetoFolhaPagamento
     * @return Response
     */
    public function printAutorizacaoAction(ProjetoFolhaPagamento $projetoFolhaPagamento)
    {
        return $this->render(
            'autorizacao_pagamento/print.html.twig', [
                'projetoFolhaPagamento' => $projetoFolhaPagamento,
            ]
        );
    }
}
