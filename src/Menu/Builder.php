<?php

namespace App\Menu;

use Knp\Menu\FactoryInterface;
use Knp\Menu\ItemInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Doctrine\ORM\EntityManagerInterface;
use App\Entity\Perfil;
use App\Entity\Projeto;

class Builder
{
    private $factory;
    private $tokenStorage;
    private $authorizationChecker;
    private $entityManager;

    public function __construct(
        FactoryInterface $factory,
        TokenStorageInterface $tokenStorage,
        AuthorizationCheckerInterface $authorizationChecker,
        EntityManagerInterface $entityManager
    ) {
        $this->factory = $factory;
        $this->tokenStorage = $tokenStorage;
        $this->authorizationChecker = $authorizationChecker;
        $this->entityManager = $entityManager;
    }

    public function mainMenu(array $options): ItemInterface
    {
        $menu = $this->factory->createItem('root');
        $menu->setChildrenAttribute('class', 'nav navbar-nav');

        if ($this->authorizationChecker->isGranted(Perfil::ROLE_ADMINISTRADOR)) {
            $this->menuAdministrador($menu);
        } elseif ($this->authorizationChecker->isGranted(Perfil::ROLE_COORDENADOR_PROJETO)) {
            $this->menuCoordenadorProjeto($menu);
        } elseif ($this->authorizationChecker->isGranted(Perfil::ROLE_COORDENADOR_GRUPO)) {
            $this->menuCoordenadorGrupo($menu);
        } elseif ($this->authorizationChecker->isGranted(Perfil::ROLE_PRECEPTOR)) {
            $this->menuPreceptor($menu);
        } elseif ($this->authorizationChecker->isGranted(Perfil::ROLE_TUTOR)) {
            $this->menuTutor($menu);
        } elseif ($this->authorizationChecker->isGranted(Perfil::ROLE_ESTUDANTE)) {
            $this->menuEstudante($menu);
        } elseif ($this->authorizationChecker->isGranted('ROLE_PREVIOUS_ADMIN')) {
            if (in_array(Perfil::ROLE_COORDENADOR_PROJETO, $this->getUser()->getRoles())) {
                $this->menuCoordenadorProjeto($menu);
            }
        }
        return $menu;
    }

    public function menuExterno(array $options): ItemInterface
    {
        $menu = $this->factory->createItem('root');
        $menu->setChildrenAttribute('class', 'nav navbar-nav');

        $menu->addChild('Informe de Rendimento', ['route' => 'informe-rendimento']);
        $menu->addChild('Fale Conosco', ['route' => 'fale_conosco']);

        return $menu;
    }

    public function menu(array $options): ItemInterface
    {
        $menu = $this->factory->createItem('root');
        $menu->setChildrenAttribute('class', 'nav navbar-nav navbar-right');

        $menu->addChild('Alterar Perfil', ['route' => 'default_selecionar_perfil']);

        if ($this->authorizationChecker->isGranted('ROLE_PREVIOUS_ADMIN')) {
            $menu->addChild('Sair da Representação', ['uri' => '/?_switch_user=_exit']);
        } else {
            $menu->addChild('Sair', ['uri' => '/logout']);
        }

        return $menu;
    }

    private function menuAdministrador(ItemInterface $menu)
    {
        $menu->addChild('Programas', array('route' => 'programa'));
        $menu->addChild('Projetos', array('route' => 'projeto'));
        $menu->addChild('Participantes', array('route' => 'participante'));
        $menu->addChild('Folha de pagamento', array('route' => 'folha_pagamento'));   
        
        $relatorios = $menu->addChild('Relatórios')
        ->setAttribute('dropdown', true)
        ->setAttribute('class', 'dropdown')
        ->setChildrenAttribute('class', 'dropdown-menu');
        
        // $menu->addChild('Relatorios')->setAttribute('dropdown', true);
        $relatorios->addChild('Bolsas', ['route' => 'relatorio_pagamento']);
        $relatorios->addChild('Bolsas não autorizadas', ['route' => 'relatorio_pagamento_nao_autorizado']);
        $relatorios->addChild('Folha de Pagamento', ['route' => 'relatorio_folha_pagamento']);
        $relatorios->addChild('Gerencial de Participantes', array('route' => 'relatorio_gerencial_participante'));
        $relatorios->addChild('Participantes', ['route' => 'relatorio_participante']);
        $relatorios->addChild('Projetos', ['route' => 'relatorio_projeto']);
        
        $avaliacaoAtividade = $menu->addChild('Avaliação de Atividades')
            ->setAttribute('dropdown', true)
            ->setAttribute('class', 'nav-item dropdown') 
            ->setLinkAttribute('class', 'nav-link dropdown-toggle') 
            ->setLinkAttribute('data-toggle', 'dropdown') 
            ->setLinkAttribute('aria-haspopup', 'true')
            ->setLinkAttribute('aria-expanded', 'false')
            ->setChildrenAttribute('class', 'dropdown-menu');
            
        $avaliacaoAtividade->addChild('Gerenciar Formulário de Atividades', array('route' => 'gerenciar_formulario_atividade'));
        $avaliacaoAtividade->addChild('Tramitar Formulário de Atividades', array('route' => 'tramita_formulario_atividade'));
        
        $gerenciarSistema = $menu->addChild('Gerenciar Sistema')
        ->setAttribute('dropdown', true)
        ->setAttribute('class', 'dropdown')
        ->setChildrenAttribute('class', 'dropdown-menu');

        $gerenciarSistema->addChild('Abrir Cadastro Participante', array('route' => 'abertura_cadastro_participante'));
        $gerenciarSistema->addChild('Manter Valor de Bolsa', array('route' => 'valor_bolsa_index'));
        $gerenciarSistema->addChild('Planejar Abertura de Folha', array('route' => 'planejamento_abertura_folha'));
        $gerenciarSistema->addChild('Manter Modelo de Certificado', array('route' => 'modelo_certificado', 'routeParameters' => ['filter' => 'clear']));
        $gerenciarSistema->addChild('Manter Banco', ['route' => 'banco_index']);
        $gerenciarSistema->addChild('Manter Administrador', ['route' => 'administrador']);
        $gerenciarSistema->addChild('Manter Instituição', ['route' => 'instituicao']);
        $gerenciarSistema->addChild('Manter Campus', ['route' => 'campus']);
        $gerenciarSistema->addChild('Manter Cursos de Formação', ['route' => 'curso_formacao']);
        $gerenciarSistema->addChild('Manter Texto Saudação', ['route' => 'saudacao']);

        $folhaPagamento = $menu->addChild('Folha de Pagamento/Arquivos Bancários')
        ->setAttribute('dropdown', true)
        ->setAttribute('class', 'dropdown')
        ->setChildrenAttribute('class', 'dropdown-menu');

        $folhaPagamento->addChild('Retorno de Cadastro', array('route' => 'arquivo_retorno_cadastro'));
        $folhaPagamento->addChild('Retorno de Pagamento', array('route' => 'arquivo_retorno_pagamento'));      
    }

    private function menuCoordenadorProjeto(ItemInterface $menu)
    {
        $projeto = $this->getProjetoAutenticado();

        if ($projeto && $projeto->getPublicacao()->getPrograma()->isAreaAtuacao()) {
            $menu->addChild('Grupos de atuação', array('route' => 'grupo_atuacao_index'));
        }

        $menu->addChild('Participantes', array('route' => 'participante'));
        $menu->addChild('Estabelecimentos', array('route' => 'estabelecimento_index'));
        $menu->addChild('Autorização de pagamento', array('route' => 'autorizacao_pagamento'));
        $menu->addChild('Certificado/Declaração', array('route' => 'certificado'));
        
        $relatorios = $menu->addChild('Relatorios')
            ->setAttribute('dropdown', true)
            ->setAttribute('class', 'dropdown')
            ->setChildrenAttribute('class', 'dropdown-menu');
            
        $relatorios->addChild('Gerencial de Participantes', array('route' => 'relatorio_gerencial_participante'));
        
        $gerenciarSistema = $menu->addChild('Gerenciar Sistema')
            ->setAttribute('dropdown', true)
            ->setAttribute('class', 'dropdown')
            ->setChildrenAttribute('class', 'dropdown-menu');

        $gerenciarSistema->addChild('Planejar Abertura de Folha', array('route' => 'planejamento_abertura_folha'));
        
        $avaliacao = $menu->addChild('Avaliação de Atividades')
            ->setAttribute('dropdown', true)
            ->setAttribute('class', 'dropdown')
            ->setChildrenAttribute('class', 'dropdown-menu');

        $avaliacao->addChild('Tramitar Formulário de Atividades', array('route' => 'tramita_formulario_atividade_index_retorno'));
    }

    private function menuCoordenadorGrupo(ItemInterface $menu)
    {
        $avaliacao = $menu->addChild('Avaliação de Atividades')
            ->setAttribute('dropdown', true)
            ->setAttribute('class', 'dropdown')
            ->setChildrenAttribute('class', 'dropdown-menu');
        $avaliacao->addChild('Tramitar Formulário de Atividades', ['route' => 'tramita_formulario_atividade_index_retorno']);
    }

    private function menuPreceptor(ItemInterface $menu)
    {
        $avaliacao = $menu->addChild('Avaliação de Atividades')
            ->setAttribute('dropdown', true)
            ->setAttribute('class', 'dropdown')
            ->setChildrenAttribute('class', 'dropdown-menu');
            $avaliacao->addChild('Tramitar Formulário de Atividades', ['route' => 'tramita_formulario_atividade_index_retorno']);
    }

    private function menuTutor(ItemInterface $menu)
    {
        $avaliacao = $menu->addChild('Avaliação de Atividades')
            ->setAttribute('dropdown', true)
            ->setAttribute('class', 'dropdown')
            ->setChildrenAttribute('class', 'dropdown-menu');
        $avaliacao->addChild('Tramitar Formulário de Atividades', ['route' => 'tramita_formulario_atividade_index_retorno']);
    }

    private function menuEstudante(ItemInterface $menu)
    {
        $avaliacao = $menu->addChild('Avaliação de Atividades')
            ->setAttribute('dropdown', true)
            ->setAttribute('class', 'dropdown')
            ->setChildrenAttribute('class', 'dropdown-menu');
        $avaliacao->addChild('Tramitar Formulário de Atividades', ['route' => 'tramita_formulario_atividade_index_retorno']);
    }

    protected function getUser(): ?UserInterface
    {
        $token = $this->tokenStorage->getToken();

        if (!$token || !is_object($user = $token->getUser())) {
            return null;
        }

        return $user;
    }

    protected function getProjetoAutenticado(): ?Projeto
    {
        $token = $this->tokenStorage->getToken();

        if (!$token) {
            return null;
        }

        $attributes = $token->getAttributes();

        if (!isset($attributes['coProjeto']) || !$attributes['coProjeto']) {
            return null;
        }

        return $this->entityManager->find(Projeto::class, $attributes['coProjeto']);
    }
}
