<?php

namespace AppBundle\Event;

use AppBundle\Entity\EnvioFormularioAvaliacaoAtividade;
use AppBundle\Entity\TramitacaoFormulario;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\EventDispatcher\Event;

final class SendMailNotificacaoExcusaoEnvioFormularioAvaliacaoAtividadeEvent extends Event
{
    const NAME = 'send_notificacao.exclusao_tramitacoes_formulario';    
    
    /**
     *
     * @var ArrayCollection<\AppBundle\Entity\TramitacaoFormulario> 
     */
    private $tramitacoesFormulario;
    
    /**
     * 
     * @param TramitacaoFormulario $tramitacoesFormulario
     */
    public function __construct($tramitacoesFormulario)
    {
        if ($tramitacoesFormulario instanceof TramitacaoFormulario) {
            $this->tramitacoesFormulario = new ArrayCollection();        
            $this->tramitacoesFormulario->add($tramitacoesFormulario);
        } elseif ($tramitacoesFormulario instanceof ArrayCollection) {
            $this->tramitacoesFormulario = $tramitacoesFormulario;
        }
    }

    /**
     * 
     * @return ArrayCollection<\AppBundle\Entity\TramitacaoFormulario> 
     */
    public function getTramitacoesFormulario()
    {
        return $this->tramitacoesFormulario;
    }
}
