<?php

namespace AppBundle\Security;

use AppBundle\Repository\AutorizaCadastroParticipanteRepository;
use AppBundle\Repository\ProjetoRepository;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;

final class AutorizacaoCadastroParticipanteVoter extends Voter
{
    /**
     * @var AutorizaCadastroParticipanteRepository
     */
    private $autorizaCadastroParticipanteRepository;
    
    /**
     *
     * @var ProjetoRepository 
     */
    private $projetoRepository;
    
    /**
     * 
     * @param AutorizaCadastroParticipanteRepository $autorizaCadastroParticipanteRepository
     * @param ProjetoRepository $projetoRepository
     */
    public function __construct(
        AutorizaCadastroParticipanteRepository $autorizaCadastroParticipanteRepository,
        ProjetoRepository $projetoRepository
    ) {
        $this->autorizaCadastroParticipanteRepository = $autorizaCadastroParticipanteRepository;
        $this->projetoRepository = $projetoRepository;
    }
    
    /**
     * 
     * @param string $attribute
     * @param mixed $subject
     * @return boolean
     */
    protected function supports($attribute, $subject)
    {
        return $attribute == 'HAS_AUTORIZACAO_CADASTRO_PARTICIPANTE';
    }
    
    /**
     * 
     * @param string $attribute
     * @param mixed $subject
     * @param TokenInterface $token
     * @return boolean
     */
    protected function voteOnAttribute($attribute, $subject, TokenInterface $token)
    {
        if (!$token->hasAttribute('coProjeto') || $token->getAttribute('coProjeto') == null) {
            return false;
        }
        
        try {
            $projeto = $this->projetoRepository->find($token->getAttribute('coProjeto'));            
            $this->autorizaCadastroParticipanteRepository->checkExistisAutorizacaoVigente($projeto);
        } catch (\Exception $ex) {
            return true;
        }
        
        return false;
    }
}
