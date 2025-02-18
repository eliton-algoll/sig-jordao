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
            $menu->addChild('Sair', ['route' => 'logout']);
        }

        return $menu;
    }

    private function menuAdministrador(ItemInterface $menu)
    {
        $menu->addChild('Programas', ['route' => 'programa']);
        $menu->addChild('Projetos', ['route' => 'projeto']);
        $menu->addChild('Participantes', ['route' => 'participante']);
        $menu->addChild('Folha de pagamento', ['route' => 'folha_pagamento']);

        $menu->addChild('Relatórios')->setAttribute('dropdown', true);
        $menu['Relatórios']->addChild('Bolsas', ['route' => 'relatorio_pagamento']);
        $menu['Relatórios']->addChild('Bolsas não autorizadas', ['route' => 'relatorio_pagamento_nao_autorizado']);

        $menu->addChild('Gerenciar Sistema')->setAttribute('dropdown', true);
        $menu['Gerenciar Sistema']->addChild('Abrir Cadastro Participante', ['route' => 'abertura_cadastro_participante']);
    }

    private function menuCoordenadorProjeto(ItemInterface $menu)
    {
        if ($projeto = $this->getProjetoAutenticado()) {
            if ($projeto->getPublicacao()->getPrograma()->isAreaAtuacao()) {
                $menu->addChild('Grupos de atuação', ['route' => 'grupo_atuacao_index']);
            }
        }

        $menu->addChild('Participantes', ['route' => 'participante']);
        $menu->addChild('Autorização de pagamento', ['route' => 'autorizacao_pagamento']);
    }

    private function menuCoordenadorGrupo(ItemInterface $menu)
    {
        $menu->addChild('Avaliação de Atividades')->setAttribute('dropdown', true);
        $menu['Avaliação de Atividades']->addChild('Tramitar Formulário de Atividades', ['route' => 'tramita_formulario_atividade_index_retorno']);
    }

    private function menuPreceptor(ItemInterface $menu)
    {
        $menu->addChild('Avaliação de Atividades')->setAttribute('dropdown', true);
        $menu['Avaliação de Atividades']->addChild('Tramitar Formulário de Atividades', ['route' => 'tramita_formulario_atividade_index_retorno']);
    }

    private function menuTutor(ItemInterface $menu)
    {
        $menu->addChild('Avaliação de Atividades')->setAttribute('dropdown', true);
        $menu['Avaliação de Atividades']->addChild('Tramitar Formulário de Atividades', ['route' => 'tramita_formulario_atividade_index_retorno']);
    }

    private function menuEstudante(ItemInterface $menu)
    {
        $menu->addChild('Avaliação de Atividades')->setAttribute('dropdown', true);
        $menu['Avaliação de Atividades']->addChild('Tramitar Formulário de Atividades', ['route' => 'tramita_formulario_atividade_index_retorno']);
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
