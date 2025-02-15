<?php

namespace App\Doctrine\Event\Listeners;

use Doctrine\DBAL\Event\ConnectionEventArgs;

class RoleListener
{
    /**
     * @var string
     */
    private $roleName;
    
    /**
     * @var string
     */
    private $rolePass;
    
    /**
     * @param string $roleName
     */
    function setRoleName($roleName)
    {
        $this->roleName = $roleName;
    }

    /**
     * @param string $rolePass
     */
    function setRolePass($rolePass)
    {
        $this->rolePass = $rolePass;
    }
    
    /**
     * @param ConnectionEventArgs $args
     */
    public function postConnect(ConnectionEventArgs $args)
    {
        $this->executeRole($args);
    }
    
    /**
     * @param ConnectionEventArgs $args
     */
    private function executeRole(ConnectionEventArgs $args)   
    {
        if ($this->roleName && $this->rolePass) {
            $con = $args->getConnection();
            $con->executeUpdate('SET ROLE ' . $this->roleName . ' IDENTIFIED BY ' . $this->rolePass);
        }
    }
}