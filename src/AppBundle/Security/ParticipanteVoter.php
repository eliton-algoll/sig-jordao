<?php

namespace AppBundle\Security;

use Symfony\Component\Security\Core\Authorization\Voter\Voter;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use AppBundle\Entity\ProjetoPessoa;

/**
 * Vota se o usuario esta no mesmo projeto da pessoa que ele ta tentando acessar/alterar
 */
class ParticipanteVoter extends Voter
{
    /**
     * @var \Doctrine\ORM\EntityManager
     */
    private $em;
    
    /**
     * @param \Doctrine\ORM\EntityManager
     */
    public function __construct($entityManager)
    {
        $this->em = $entityManager;
    }
    
    /**
     * @param string $attribute
     * @param mixed $subject
     */
    protected function supports($attribute, $subject)
    {
        return $attribute == 'IS_PARTICIPANTE' && $subject instanceof ProjetoPessoa;
    }

    /**
     * @param string $attribute
     * @param ProjetoPessoa $subject
     * @param TokenInterface $token
     */    
    protected function voteOnAttribute($attribute, $subject, TokenInterface $token)
    {
        if (!$token->hasAttribute('coPessoaPerfil')) {
            return false;
        }
        
        $pessoaPerfil = $this->em->find('AppBundle:PessoaPerfil', $token->getAttribute('coPessoaPerfil'));
        
        if ($pessoaPerfil->getPerfil()->isAdministrador()) {
            return true;
        }
        
        $projeto = $this->em->find('AppBundle:Projeto', $token->getAttribute('coProjeto'));
        
        return $subject->getProjeto()->getCoSeqProjeto() == $projeto->getCoSeqProjeto();
    }
}