<?php

namespace AppBundle\Security;

use Symfony\Component\Security\Core\Authorization\Voter\Voter;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use AppBundle\Repository\ProjetoRepository;
use AppBundle\Query\FolhaPagamentoQuery;

/**
 * Vota se a folha de pagamento do projeto está em qualquer outro STATUS diferente de Aberta
 */
class FolhaPagamentoDiferenteDeAberta
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
     */
    protected function supports($attribute, $subject)
    {
        return $attribute == 'HAS_FOLHA_PAGAMENTO_DIFERENTE_DE_ABERTA';
    }

    /**
     * @param string $attribute
     * @param mixed $subject
     * @param TokenInterface $token
     */    
    protected function voteOnAttribute($attribute, $subject, TokenInterface $token)
    {
        if (!$token->hasAttribute('coProjeto') || $token->getAttribute('coProjeto') == null) {
            return false;
        }
        
        $projeto = $this->projetoRepository->find($token->getAttribute('coProjeto'));

        $folha = $this->folhaPagamentoQuery->findFolhaAbertaByPublicacao($projeto->getPublicacao());
        
        return !(bool) $folha;
    }
}
