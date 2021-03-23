<?php

namespace AppBundle\Menu;

use Knp\Menu\FactoryInterface;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerAwareTrait;
use AppBundle\Entity\Perfil;

class Builder implements ContainerAwareInterface
{
    use ContainerAwareTrait;

    /**
     * @param FactoryInterface $factory
     * @param array $options
     * @return \Knp\Menu\ItemInterface
     */
    public function mainMenu(FactoryInterface $factory, array $options)
    {
        $securityChecker = $this->container->get('security.authorization_checker');        
        
        $menu = $factory->createItem('root');
        $menu->setChildrenAttribute('class', 'nav navbar-nav');
        
        if ($securityChecker->isGranted(Perfil::ROLE_ADMINISTRADOR)) {
            $this->menuAdministrador($menu);            
        } elseif ($securityChecker->isGranted([Perfil::ROLE_COORDENADOR_PROJETO])) {
            $this->menuCoordenadorProjeto($menu);
        } elseif ($securityChecker->isGranted(Perfil::ROLE_COORDENADOR_GRUPO)) {
            $this->menuCoordenadorGrupo($menu);
        } elseif ($securityChecker->isGranted(Perfil::ROLE_PRECEPTOR)) {
            $this->menuPreceptor($menu);
        } elseif ($securityChecker->isGranted(Perfil::ROLE_TUTOR)) {
            $this->menuTutor($menu);
        } elseif ($securityChecker->isGranted(Perfil::ROLE_ESTUDANTE)) {
            $this->menuEstudante($menu);
        } elseif ($securityChecker->isGranted('ROLE_PREVIOUS_ADMIN')) {
            if (in_array(Perfil::ROLE_COORDENADOR_PROJETO, $this->getUser()->getRoles())) {
                $this->menuCoordenadorProjeto($menu);
            }
        }
        return $menu;
    }

    /**
     * @param FactoryInterface $factory
     * @param array $options
     * @return mixed
     */
    public function menuExterno(FactoryInterface $factory, array $options)
    {
        $menu = $factory->createItem('root');
        $menu->setChildrenAttribute('class', 'nav navbar-nav');

        $menu->addChild('Informe de Rendimento', array('route' => 'informe-rendimento'));
        $menu->addChild('Fale Conosco', array('route' => 'fale_conosco'));

        return $menu;
    }
    
    public function menu(FactoryInterface $factory, array $options)
    {
        $securityChecker = $this->container->get('security.authorization_checker');
        
        $menu = $factory->createItem('root');
        $menu->setChildrenAttribute('class', 'nav navbar-nav navbar-right');
        
        $menu->addChild('Alterar Perfil', array('route' => 'default_selecionar_perfil'));
        if ($securityChecker->isGranted('ROLE_PREVIOUS_ADMIN')) {
            $menu->addChild('Sair da Representação', array('uri' => '/?_switch_user=_exit'));
        } else {
            $menu->addChild('Sair', array('route' => 'logout'));
        }
        
        return $menu;
    }

    /**
     * @param \Knp\Menu\ItemInterface $menu
     */
    private function menuAdministrador(\Knp\Menu\ItemInterface $menu)
    {
        $menu->addChild('Programas', array('route' => 'programa'));
        $menu->addChild('Projetos', array('route' => 'projeto'));
        $menu->addChild('Participantes', array('route' => 'participante'));
        $menu->addChild('Folha de pagamento', array('route' => 'folha_pagamento'));        
        
        $menu->addChild('Relatorios')->setAttribute('dropdown', true);
        $menu['Relatorios']->addChild('Bolsas', array('route' => 'relatorio_pagamento'));
        $menu['Relatorios']->addChild('Bolsas não autorizadas', array('route' => 'relatorio_pagamento_nao_autorizado'));
        $menu['Relatorios']->addChild('Folha de Pagamento', array('route' => 'relatorio_folha_pagamento'));
        $menu['Relatorios']->addChild('Gerencial de Pagamento', array('route' => 'relatorio_gerencial_pagamento'));
        $menu['Relatorios']->addChild('Gerencial de Participantes', array('route' => 'relatorio_gerencial_participante'));
        $menu['Relatorios']->addChild('Participantes', array('route' => 'relatorio_participante'));
        $menu['Relatorios']->addChild('Projetos', array('route' => 'relatorio_projeto'));
        
        $menu->addChild('Avaliação de Atividades')->setAttribute('dropdown', true);
        $menu['Avaliação de Atividades']->addChild('Gerenciar Formulário de Atividades', array('route' => 'gerenciar_formulario_atividade'));
        $menu['Avaliação de Atividades']->addChild('Tramitar Formulário de Atividades', array('route' => 'tramita_formulario_atividade'));
        
        $menu->addChild('Gerenciar Sistema')->setAttribute('dropdown', true);
        $menu['Gerenciar Sistema']->addChild('Abrir Cadastro Participante', array('route' => 'abertura_cadastro_participante'));
        $menu['Gerenciar Sistema']->addChild('Manter Valor de Bolsa', array('route' => 'valor_bolsa_index'));
        $menu['Gerenciar Sistema']->addChild('Planejar Abertura de Folha', array('route' => 'planejamento_abertura_folha'));
        $menu['Gerenciar Sistema']->addChild('Manter Modelo de Certificado', array('route' => 'modelo_certificado', 'routeParameters' => ['filter' => 'clear']));
        
        $menu->addChild('Folha de Pagamento/Arquivos Bancários')->setAttribute('dropdown', true);
        $menu['Folha de Pagamento/Arquivos Bancários']->addChild('Retorno de Cadastro', array('route' => 'arquivo_retorno_cadastro'));
        $menu['Folha de Pagamento/Arquivos Bancários']->addChild('Retorno de Pagamento', array('route' => 'arquivo_retorno_pagamento'));        
    }
    
    /**
     * @param \Knp\Menu\ItemInterface $menu
     */
    private function menuCoordenadorProjeto(\Knp\Menu\ItemInterface $menu)
    {
        $projeto = $this->getProjetoAutenticado();

        if ($projeto && $projeto->getPublicacao()->getPrograma()->isAreaAtuacao()) {
            $menu->addChild('Grupos de atuação', array('route' => 'grupo_atuacao_index'));
        }

        $menu->addChild('Participantes', array('route' => 'participante'));
        $menu->addChild('Estabelecimentos', array('route' => 'estabelecimento_index'));
        $menu->addChild('Autorização de pagamento', array('route' => 'autorizacao_pagamento'));
        $menu->addChild('Certificado/Declaração', array('route' => 'certificado'));
        
        $menu->addChild('Relatorios')
             ->setAttribute('dropdown', true);
        $menu['Relatorios']->addChild('Gerencial de Participantes', array('route' => 'relatorio_gerencial_participante'));
        
        $menu->addChild('Gerenciar Sistema')
             ->setAttribute('dropdown', true);
        $menu['Gerenciar Sistema']->addChild('Planejar Abertura de Folha', array('route' => 'planejamento_abertura_folha'));
        
        $menu->addChild('Avaliação de Atividades')
             ->setAttribute('dropdown', true);
        $menu['Avaliação de Atividades']->addChild('Tramitar Formulário de Atividades', array('route' => 'tramita_formulario_atividade_index_retorno'));
    }
    
    /**
     * 
     * @param \Knp\Menu\ItemInterface $menu
     */
    private function menuCoordenadorGrupo(\Knp\Menu\ItemInterface $menu)
    {
        $menu->addChild('Avaliação de Atividades')
             ->setAttribute('dropdown', true);
        $menu['Avaliação de Atividades']->addChild('Tramitar Formulário de Atividades', array('route' => 'tramita_formulario_atividade_index_retorno'));
    }
    
    /**
     * 
     * @param \Knp\Menu\ItemInterface $menu
     */
    private function menuPreceptor(\Knp\Menu\ItemInterface $menu)
    {
        $menu->addChild('Avaliação de Atividades')
             ->setAttribute('dropdown', true);
        $menu['Avaliação de Atividades']->addChild('Tramitar Formulário de Atividades', array('route' => 'tramita_formulario_atividade_index_retorno'));
    }
    
    /**
     * 
     * @param \Knp\Menu\ItemInterface $menu
     */
    private function menuTutor(\Knp\Menu\ItemInterface $menu)
    {
        $menu->addChild('Avaliação de Atividades')
             ->setAttribute('dropdown', true);
        $menu['Avaliação de Atividades']->addChild('Tramitar Formulário de Atividades', array('route' => 'tramita_formulario_atividade_index_retorno'));
    }
    
    /**
     * 
     * @param \Knp\Menu\ItemInterface $menu
     */
    private function menuEstudante(\Knp\Menu\ItemInterface $menu)
    {
        $menu->addChild('Avaliação de Atividades')
             ->setAttribute('dropdown', true);
        $menu['Avaliação de Atividades']->addChild('Tramitar Formulário de Atividades', array('route' => 'tramita_formulario_atividade_index_retorno'));
    }
    
    /**
     * 
     * @return mixed
     * @throws \LogicException
     */
    protected function getUser()
    {
        if (!$this->container->has('security.token_storage')) {
            throw new \LogicException('The SecurityBundle is not registered in your application.');
        }

        if (null === $token = $this->container->get('security.token_storage')->getToken()) {
            return;
        }

        if (!is_object($user = $token->getUser())) {            
            return;
        }

        return $user;
    }

    public function getProjetoAutenticado()
    {
        if (null === $token = $this->container->get('security.token_storage')->getToken()) {
            return;
        }

        $projeto = null;

        if ($token->hasAttribute('coProjeto')) {
            $id = $token->getAttribute('coProjeto');
            if ($id) {
                $projeto = $this->container->get('doctrine.orm.default_entity_manager')->find('AppBundle:Projeto', $id);
            }
        }

        return $projeto;
    }
}