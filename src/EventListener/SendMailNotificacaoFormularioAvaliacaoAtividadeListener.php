<?php

namespace App\EventListener;

use App\Entity\EnderecoWeb;
use App\Event\SendMailNotificacaoFormularioAvaliacaoAtividadeEvent;
use App\Repository\TramitacaoFormularioRepository;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use Twig\Environment;

final class SendMailNotificacaoFormularioAvaliacaoAtividadeListener implements EventSubscriberInterface
{
    private $subject = [
        'notificacao' => 'SIGPET-Saúde – Notificação de Formulário de Avaliação de Atividade',
        'alteracao' => 'SIGPET-Saúde – Formulário de Avaliação de Atividade – Alteração do período de retorno',
    ];
    
    private $bodyView = 'tramita_formulario_atividade/email_notificacao.html.twig';
    
    /**
     *
     * @var MailerInterface
     */
    private $mailer;
    
    /**
     *
     * @var Environment
     */
    private $twigEngine;
    
    /**
     *
     * @var TramitacaoFormularioRepository 
     */
    private $tramitacaoFormularioRepository;
    

    public function __construct(
        MailerInterface $mailer,
        Environment $twigEngine,
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
    private function sendMail(EnderecoWeb $email, SendMailNotificacaoFormularioAvaliacaoAtividadeEvent $event): void
    {
        $htmlContent = $this->twig->render($this->bodyView, [
            'envioFormularioAvaliacaoAtividade' => $event->getEnvioFormularioAvaliacaoAtividade(),
            'isAtualizacao' => $event->isAtualizacao(),
        ]);

        $emailMessage = (new Email())
            ->from('no-reply@saude.gov.br')
            ->to($email->getDsEnderecoWeb())
            ->subject($event->isAtualizacao() ? $this->subject['alteracao'] : $this->subject['notificacao'])
            ->html($htmlContent);

        $this->mailer->send($emailMessage);
    }
}
