<?php

namespace App\Event;

use App\Entity\TramitacaoFormulario;
use Symfony\Component\EventDispatcher\Event;

final class SendMailNotificacaoAnaliseRetornoFormularioAvaliacaoAtividadeEvent extends Event
{
    const NAME = 'send_mail_notificacao.analise_retorno_formulario_avaliacao_atividade';
    
    /**
     *
     * @var TramitacaoFormulario
     */
    private $tramitacaoFormulario;
    
    /**
     * 
     * @param TramitacaoFormulario $tramitacaoFormulario
     */
    public function __construct(TramitacaoFormulario $tramitacaoFormulario)
    {
        $this->tramitacaoFormulario = $tramitacaoFormulario;
    }

    /**
     * 
     * @return TramitacaoFormulario
     */
    public function getTramitacaoFormulario()
    {
        return $this->tramitacaoFormulario;
    }
}
