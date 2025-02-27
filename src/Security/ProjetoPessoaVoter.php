<?php

namespace App\Security;

use Symfony\Component\Security\Core\Authorization\Voter\Voter;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Doctrine\ORM\EntityManagerInterface;

class ProjetoPessoaVoter extends Voter
{
    /**
     * @var EntityManagerInterface
     */
    private $em;
    
    /**
     * @param EntityManagerInterface
     */
    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->em = $entityManager;
    }
    
    /**
     * @inheritdoc
     */
    protected function supports($attribute, $subject)
    {
        $perfis = $this->em->getRepository('App:Perfil')->findAll();
        
        $roles = array();
        
        foreach ($perfis as $perfil) {
            $roles[] = $perfil->getNoRole();
        }
        
        return in_array($attribute, $roles);
    }

    /**
     * @inheritdoc
     */    
    protected function voteOnAttribute($attribute, $subject, TokenInterface $token)
    {
        if (!$token->hasAttribute('coPessoaPerfil')) {
            return false;
        }
        
        $id = $token->getAttribute('coPessoaPerfil');
        
        $pessoaPerfil = $this->em->find('App:PessoaPerfil', $id);
        
        return $pessoaPerfil->getPerfil()->getNoRole() === $attribute;
    }
}