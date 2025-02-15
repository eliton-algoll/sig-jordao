<?php

namespace App\Doctrine\Event\Listeners;

use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorage;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Session\Session;
use Doctrine\DBAL\Event\ConnectionEventArgs;
use App\Entity\Usuario;

class AuditoriaListener
{
    /**
     * @var RequestStack
     */
    private $requestStack;
    
    /**
     *
     * @var Session
     */
    private $session;
    
    /**
     * 
     * @param RequestStack $requestStack
     * @param Session $session
     */
    public function __construct(RequestStack $requestStack, Session $session)
    {
        $this->requestStack = $requestStack;
        $this->session = $session;
    }
    
    /**
     * @param ConnectionEventArgs $args
     */
    public function postConnect(ConnectionEventArgs $args)
    {
        $this->executeAuditoria($args);
    }
    
    /**
     * @param ConnectionEventArgs $args
     */
    private function executeAuditoria(ConnectionEventArgs $args)   
    {
        $conn = $args->getConnection();
        
        $token = unserialize($this->session->getBag('attributes')->get('_security_main'));
        
        if ($token && $token->getUser() instanceof Usuario) {
            $dsLogin = $token->getUser()->getDsLogin();
            $auditoriaUsuario = $conn->prepare('BEGIN DBGERAL.PKG_INFO_LOG.PC_INFORMA_USUARIO(:DS_USUARIO); END;');
            $auditoriaUsuario->bindParam(':DS_USUARIO', $dsLogin);
            $auditoriaUsuario->execute();
        }
        
        $request = $this->requestStack->getCurrentRequest();
        
        if ($request && $request->getClientIp()) {
            $ip = $request->getClientIp();
            $auditoriaIp = $conn->prepare('BEGIN DBGERAL.PKG_INFO_LOG.PC_INFORMA_IP(:NU_IP); END;');
            $auditoriaIp->bindParam(':NU_IP', $ip);
            $auditoriaIp->execute();
        }
    }
}