<?php

namespace App\Security;

use App\Entity\GrupoAtuacao;
use App\Entity\Projeto;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;

class GrupoAtuacaoProjetoVoter extends Voter
{
    /**
     * @var EntityManagerInterface
     */
    private $em;

    /**
     * GrupoAtuacaoProjetoVoter constructor.
     * @param EntityManagerInterface $entityManager
     */
    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->em = $entityManager;
    }

    /**
     * @param string $attribute
     * @param mixed $subject
     * @return bool
     */
    protected function supports($attribute, $subject)
    {
        return 'IS_GRUPO_BELONGS_PROJETO' === $attribute && $subject instanceof GrupoAtuacao;
    }

    /**
     * @param string $attribute
     * @param mixed $subject
     * @param TokenInterface $token
     * @return bool
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     * @throws \Doctrine\ORM\TransactionRequiredException
     */
    protected function voteOnAttribute($attribute, $subject, TokenInterface $token)
    {
        $projeto = $this->em->find(Projeto::class, $token->getAttribute('coProjeto'));

        return $projeto && $projeto == $subject->getProjeto();
    }
}