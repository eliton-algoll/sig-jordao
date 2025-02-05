<?php

namespace AppBundle\Doctrine\Event\Listeners;

use Doctrine\ORM\Event\PreFlushEventArgs;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorage;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Symfony\Component\Security\Core\Role\SwitchUserRole;

class ImpersonateListener
{
    /**
     * @var TokenStorage
     */
    private $tokenStorage;    
    
    /**
     * @param TokenStorage $tokenStorage
     */
    public function __construct(TokenStorage $tokenStorage)
    {
        $this->tokenStorage = $tokenStorage;
    }
    
    /**
     * 
     * @param LifecycleEventArgs $args
     * @throws \Exception
     */
    public function preFlush(PreFlushEventArgs $args)
    {
        if ($this->isImpersonated()) {
            throw new AccessDeniedException('Você não tem permissão para executar essa ação.');
        }
    }

    /**
     * 
     * @return boolean
     */
    private function isImpersonated()
    {
        $token = $this->tokenStorage->getToken();
        
        if ($token && $token->getRoles()) {
            foreach ($token->getRoles() as $role) {
                if ($role instanceof SwitchUserRole) {
                    return true;
                }
            }
        }
        
        return false;
    }
}
