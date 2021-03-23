<?php

namespace AppBundle\EventListener;

use AppBundle\Event\SendMailNotificacaoAnaliseRetornoFormularioAvaliacaoAtividadeEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Bundle\TwigBundle\TwigEngine;

class SendMailNotificacaoAnaliseRetornoFormularioAvaliacaoAtividadeListener implements EventSubscriberInterface
{
    private $subject = [
        'satisfatorio' => 'SIGPET-Saúde – Análise de retorno do Formulário de Avaliação de Atividade – Formulário SATISFATÓRIO e FINALIZADO',
        'nao_satisfatorio' => 'SIGPET-Saúde – Análise de retorno do Formulário de Avaliação de Atividade – Formulário NÃO SATISFATÓRIO',
    ];
    
    private $bodyView = 'tramita_formulario_atividade/email-notificacao-analise.html.twig';
    
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
            SendMailNotificacaoAnaliseRetornoFormularioAvaliacaoAtividadeEvent::NAME => 'onAnalisarFormulario'
        ];
    }
    
    /**
     * 
     * @param SendMailNotificacaoAnaliseRetornoFormularioAvaliacaoAtividadeEvent $event
     */
    public function onAnalisarFormulario(SendMailNotificacaoAnaliseRetornoFormularioAvaliacaoAtividadeEvent $event)
    {
        $tramitacaoFormulario = $event->getTramitacaoFormulario();
        $email = $tramitacaoFormulario
            ->getProjetoPessoa()
            ->getPessoaPerfil()
            ->getPessoaFisica()
            ->getPessoa()
            ->getEnderecoWebAtivo();
        
        $message = \Swift_Message::newInstance()
            ->setFrom('no-reply@saude.gov.br')
            ->setTo($email->getDsEnderecoWeb())
            ->setSubject(
                $tramitacaoFormulario
                    ->getSituacaoTramiteFormulario()
                    ->isDevolvido() ? $this->subject['nao_satisfatorio'] : $this->subject['satisfatorio']
            )->setBody($this->twigEngine->render($this->bodyView, [
                'tramitacaoFormulario' => $tramitacaoFormulario,
            ]), 'text/html');

        $this->mailer->send($message);
    }
}
