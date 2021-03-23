<?php

namespace AppBundle\Controller;

use League\Tactician\CommandBus;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

abstract class ControllerAbstract extends Controller
{
    /**
     * @return CommandBus
     */
    public function getBus()
    {
        return $this->get('tactician.commandbus');
    }

    /**
     * Mensagem padrão para erros de preenchimento de formulário
     */
    public function addFlashValidationError()
    {
        $this->addFlash('danger', 'Ocorreram erros no preenchimento do formulário.');
    }
    
    /*
     * Mensagem padrão para errors atípico que possam ocorrer na execução da ação
     */
    public function addFlashExecutionError()
    {
        $this->addFlash('danger', 'Ocorreu um erro na execução.');
    }

    /**
     * @return \AppBundle\Entity\Projeto | null
     */
    public function getProjetoAutenticado()
    {
        if (null === $token = $this->container->get('security.token_storage')->getToken()) {
            return;
        }

        $projeto = null;
        
        if ($token->hasAttribute('coProjeto')) {
            $id = $token->getAttribute('coProjeto');
            if ($id) {
                $projeto = $this->getDoctrine()->getManager()->find('AppBundle:Projeto', $id);
            }
        }
        
        return $projeto;
    }
    
    /**
     * @return \AppBundle\Entity\PessoaPerfil | null
     */
    public function getPessoaPerfilAutenticado()
    {
        if (null === $token = $this->container->get('security.token_storage')->getToken()) {
            return;
        }
        
        $pessoaPerfil = null;
        
        if ($token->hasAttribute('coPessoaPerfil')) {
            $id = $token->getAttribute('coPessoaPerfil');
            if ($id) {
                $pessoaPerfil = $this->getDoctrine()->getManager()->find('AppBundle:PessoaPerfil', $id);
            }
        }
        
        return $pessoaPerfil;
    }
}