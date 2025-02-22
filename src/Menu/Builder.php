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
        $menu->addChild('Programas', ['route' => 'programa']);
        $menu->addChild('Projetos', ['route' => 'projeto']);
        $menu->addChild('Participantes', ['route' => 'participante']);
        $menu->addChild('Folha de pagamento', ['route' => 'folha_pagamento']);

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
        $menu['Gerenciar Sistema']->addChild('Manter Banco', ['route' => 'banco_index']);
        $menu['Gerenciar Sistema']->addChild('Manter Administrador', ['route' => 'administrador']);
        $menu['Gerenciar Sistema']->addChild('Manter Instituição', ['route' => 'instituicao']);
        $menu['Gerenciar Sistema']->addChild('Manter Campus', ['route' => 'campus']);
        $menu['Gerenciar Sistema']->addChild('Manter Cursos de Formação', ['route' => 'curso_formacao']);
        $menu['Gerenciar Sistema']->addChild('Manter Texto Saudação', ['route' => 'saudacao']);

        $menu->addChild('Folha de Pagamento/Arquivos Bancários')->setAttribute('dropdown', true);
        $menu['Folha de Pagamento/Arquivos Bancários']->addChild('Retorno de Cadastro', array('route' => 'arquivo_retorno_cadastro'));
        $menu['Folha de Pagamento/Arquivos Bancários']->addChild('Retorno de Pagamento', array('route' => 'arquivo_retorno_pagamento'));
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

    private function menuCoordenadorGrupo(ItemInterface $menu)
    {
        $menu->addChild('Avaliação de Atividades')
            ->setAttribute('dropdown', true);
        $menu['Avaliação de Atividades']->addChild('Tramitar Formulário de Atividades', array('route' => 'tramita_formulario_atividade_index_retorno'));
    }

    private function menuPreceptor(ItemInterface $menu)
    {
        $menu->addChild('Avaliação de Atividades')->setAttribute('dropdown', true);
        $menu['Avaliação de Atividades']->addChild('Tramitar Formulário de Atividades', ['route' => 'tramita_formulario_atividade_index_retorno']);
    }

    private function menuTutor(ItemInterface $menu)
    {
        $menu->addChild('Avaliação de Atividades')
            ->setAttribute('dropdown', true);
        $menu['Avaliação de Atividades']->addChild('Tramitar Formulário de Atividades', array('route' => 'tramita_formulario_atividade_index_retorno'));
    }

    private function menuEstudante(ItemInterface $menu)
    {
        $menu->addChild('Avaliação de Atividades')
            ->setAttribute('dropdown', true);
        $menu['Avaliação de Atividades']->addChild('Tramitar Formulário de Atividades', array('route' => 'tramita_formulario_atividade_index_retorno'));
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
