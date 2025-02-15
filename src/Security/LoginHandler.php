<?php

namespace App\Security;

use Symfony\Bundle\FrameworkBundle\Routing\Router;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Security\Core\Authorization\AuthorizationChecker;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Http\Authentication\AuthenticationSuccessHandlerInterface;

class LoginHandler implements AuthenticationSuccessHandlerInterface
{
    /**
     * @var AuthorizationChecker
     */
    protected $security;

    /**
     * @var Router
     */
    protected $router;

    /**
     * @param AuthorizationChecker $security
     * @param Router $router
     */
    public function __construct($security, $router)
    {
        $this->security = $security;
        $this->router = $router;
    }

    /**
     * @inheritdoc
     */
    public function onAuthenticationSuccess(Request $request, TokenInterface $token)
    {
        return new RedirectResponse($this->router->generate('default_direcionar_login'));
    }
}