<?php

namespace App\Security;

use Symfony\Component\Security\Core\Authorization\Voter\Voter;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use App\Repository\ProjetoRepository;
use App\Query\FolhaPagamentoQuery;

/**
 * Vota se a folha de pagamento do projeto está aberta
 */
class FolhaPagamentoAbertaVoter extends Voter
{
    /**
     * @var FolhaPagamentoQuery
     */
    private $folhaPagamentoQuery;
    
    /**
     * @var ProjetoRepository
     */
    private $projetoRepository;
    
    /**
     * @param FolhaPagamentoQuery $folhaPagamentoQuery
     */
    public function __construct(ProjetoRepository $projetoRepository, FolhaPagamentoQuery $folhaPagamentoQuery)
    {
        $this->projetoRepository = $projetoRepository;
        $this->folhaPagamentoQuery = $folhaPagamentoQuery;
    }
    
    /**
     * @param string $attribute
     * @param mixed $subject
     * @return bool
     */
    protected function supports($attribute, $subject)
    {
        return $attribute == 'HAS_FOLHA_PAGAMENTO_ABERTA';
    }

    /**
     * @param string $attribute
     * @param mixed $subject
     * @param TokenInterface $token
     * @return bool
     */    
    protected function voteOnAttribute($attribute, $subject, TokenInterface $token)
    {
        if (!$token->hasAttribute('coProjeto') || $token->getAttribute('coProjeto') == null) {
            return false;
        }
        
        $projeto = $this->projetoRepository->find($token->getAttribute('coProjeto'));

        $folha = $this->folhaPagamentoQuery->findFolhaAbertaByPublicacao($projeto->getPublicacao());
        
        return (bool) $folha;
    }
}