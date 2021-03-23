<?php

namespace AppBundle\EventListener;

use AppBundle\Entity\EnderecoWeb;
use AppBundle\Event\SendMailNotificacaoFormularioAvaliacaoAtividadeEvent;
use AppBundle\Repository\TramitacaoFormularioRepository;
use Symfony\Bundle\TwigBundle\TwigEngine;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

final class SendMailNotificacaoFormularioAvaliacaoAtividadeListener implements EventSubscriberInterface
{
    private $subject = [
        'notificacao' => 'SIGPET-Saúde – Notificação de Formulário de Avaliação de Atividade',
        'alteracao' => 'SIGPET-Saúde – Formulário de Avaliação de Atividade – Alteração do período de retorno',
    ];
    
    private $bodyView = 'tramita_formulario_atividade/email_notificacao.html.twig';
    
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
     * @var TramitacaoFormularioRepository 
     */
    private $tramitacaoFormularioRepository;
    
    /**
     * 
     * @param \Swift_Mailer $mailer
     * @param TwigEngine $twigEngine
     * @param TramitacaoFormularioRepository $tramitacaoFormularioRepository
     */
    public function __construct(
        \Swift_Mailer $mailer,
        TwigEngine $twigEngine,
        TramitacaoFormularioRepository $tramitacaoFormularioRepository
    ) {
        $this->mailer = $mailer;
        $this->twigEngine = $twigEngine;
        $this->tramitacaoFormularioRepository = $tramitacaoFormularioRepository;
    }
    
    public static function getSubscribedEvents()
    {
        return [
            SendMailNotificacaoFormularioAvaliacaoAtividadeEvent::NAME => 'onSaveEnvioFormularioAvaliacaoAtividade'
        ];
    }
    
    /**
     * 
     * @param SendMailNotificacaoFormularioAvaliacaoAtividadeEvent $event
     */
    public function onSaveEnvioFormularioAvaliacaoAtividade(SendMailNotificacaoFormularioAvaliacaoAtividadeEvent $event)
    {
        $tramitacoesFormulario = $event
            ->getEnvioFormularioAvaliacaoAtividade()
            ->getTramitacaoFormulario();
        
        foreach ($tramitacoesFormulario as $tramitacaoFormulario) {
            
            $email = $tramitacaoFormulario
                ->getProjetoPessoa()
                ->getPessoaPerfil()
                ->getPessoaFisica()
                ->getPessoa()
                ->getEnderecoWebAtivo();
                
            if (!$email) continue;
            
            $this->sendMail($email, $event);
        }
    }
    
    /**
     * 
     * @param EnderecoWeb $email
     * @param SendMailNotificacaoFormularioAvaliacaoAtividadeEvent $event
     */
    private function sendMail(
        EnderecoWeb $email,
        SendMailNotificacaoFormularioAvaliacaoAtividadeEvent $event
    ) {
        $message = \Swift_Message::newInstance()
            ->setFrom('no-reply@saude.gov.br')
            ->setTo($email->getDsEnderecoWeb())
            ->setSubject(($event->isAtualizacao()) ? $this->subject['alteracao'] : $this->subject['notificacao'])
            ->setBody($this->twigEngine->render($this->bodyView, [
                'envioFormularioAvaliacaoAtividade' => $event->getEnvioFormularioAvaliacaoAtividade(),
                'isAtualizacao' => $event->isAtualizacao(),
            ]), 'text/html');

        $this->mailer->send($message);
    }
}
