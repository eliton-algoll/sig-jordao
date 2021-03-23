<?php

namespace AppBundle\Event;

use AppBundle\Entity\EnvioFormularioAvaliacaoAtividade;
use Symfony\Component\EventDispatcher\Event;

final class SendMailNotificacaoFormularioAvaliacaoAtividadeEvent extends Event
{
    const NAME = 'send_mail_notificacao.formulario_avaliacao_atividade';
    
    /**
     *
     * @var EnvioFormularioAvaliacaoAtividade
     */
    private $envioFormularioAvaliacaoAtividade;
    
    /**
     *
     * @var boolean
     */
    private $stAtualizacao;
    
    /**
     * 
     * @param EnvioFormularioAvaliacaoAtividade $envioFormularioAvaliacaoAtividade
     */
    public function __construct(EnvioFormularioAvaliacaoAtividade $envioFormularioAvaliacaoAtividade, $stAtualizacao = false)
    {
        $this->envioFormularioAvaliacaoAtividade = $envioFormularioAvaliacaoAtividade;
        $this->stAtualizacao = $stAtualizacao;
    }

    /**
     * 
     * @return EnvioFormularioAvaliacaoAtividade
     */
    public function getEnvioFormularioAvaliacaoAtividade()
    {
        return $this->envioFormularioAvaliacaoAtividade;
    }
    
    /**
     * 
     * @return boolean
     */
    public function isAtualizacao()
    {
        return $this->stAtualizacao;
    }
}
