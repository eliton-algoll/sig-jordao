<?php

namespace AppBundle\EventListener;

use AppBundle\Event\SendMailNotificacaoExcusaoEnvioFormularioAvaliacaoAtividadeEvent;
use Symfony\Bundle\TwigBundle\TwigEngine;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

final class SendMailNotificacaoExcusaoEnvioFormularioAvaliacaoAtividadeListener implements EventSubscriberInterface
{
    private $subject = 'SIGPET-Saúde –Formulário de Avaliação de Atividade – Formulário EXCLUÍDO';
    
    private $bodyView = 'tramita_formulario_atividade/email_notificacao_exclusao.html.twig';
    
    /**
     *
     * @var \Swift_Mailer
     */
    private $mailer;
    
    /**
     *
     * @var TwigEngine 
     */
    private $twigEngine;
    
    /**
     * 
     * @param \Swift_Mailer $mailer
     * @param TwigEngine $twigEngine
     */
    public function __construct(\Swift_Mailer $mailer, TwigEngine $twigEngine)
    {
        $this->mailer = $mailer;
        $this->twigEngine = $twigEngine;
    }
    
    /**
     * 
     * @return array
     */
    public static function getSubscribedEvents()
    {
        return [
            SendMailNotificacaoExcusaoEnvioFormularioAvaliacaoAtividadeEvent::NAME => 'onDeleteTramitacoesFormulario',            
        ];
    }
    
    /**
     * 
     * @param SendMailNotificacaoExcusaoEnvioFormularioAvaliacaoAtividadeEvent $event
     */
    public function onDeleteTramitacoesFormulario(SendMailNotificacaoExcusaoEnvioFormularioAvaliacaoAtividadeEvent $event)
    {
        foreach ($event->getTramitacoesFormulario() as $tramitacaoFormulario) {
            $email = $tramitacaoFormulario->getProjetoPessoa()
                ->getPessoaPerfil()
                ->getPessoaFisica()
                ->getPessoa()
                ->getEnderecoWebAtivo();
            
            if ($email) {
                $this->sendMail($email->getDsEnderecoWeb(), $tramitacaoFormulario);
            }
        }
    }
    
    /**
     * 
     * @param string $email
     */
    private function sendMail($email, $tramitacaoFormulario)
    {
        $message = \Swift_Message::newInstance()
            ->setSubject($this->subject)
            ->setTo($email)
            ->setFrom('no-reply@saude.gov.br')
            ->setBody($this->twigEngine->render($this->bodyView, [
                'tramitacaoFormulario' => $tramitacaoFormulario,
            ]), 'text/html');
        
        $this->mailer->send($message);
    }
}
