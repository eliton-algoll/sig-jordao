<?php

namespace App\Event;

use App\Entity\EnvioFormularioAvaliacaoAtividade;
use App\Entity\TramitacaoFormulario;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\EventDispatcher\Event;

final class SendMailNotificacaoExcusaoEnvioFormularioAvaliacaoAtividadeEvent extends Event
{
    const NAME = 'send_notificacao.exclusao_tramitacoes_formulario';    
    
    /**
     *
     * @var ArrayCollection<\App\Entity\TramitacaoFormulario> 
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
     * @return ArrayCollection<\App\Entity\TramitacaoFormulario> 
     */
    public function getTramitacoesFormulario()
    {
        return $this->tramitacoesFormulario;
    }
}
