<?php

namespace App\EventListener;

use App\Event\SendMailNotificacaoExcusaoEnvioFormularioAvaliacaoAtividadeEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use Twig\Environment;

final class SendMailNotificacaoExcusaoEnvioFormularioAvaliacaoAtividadeListener implements EventSubscriberInterface
{
    private $subject = 'SIGPET-Saúde –Formulário de Avaliação de Atividade – Formulário EXCLUÍDO';
    
    private $bodyView = 'tramita_formulario_atividade/email_notificacao_exclusao.html.twig';
    
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
    
    public function __construct(MailerInterface $mailer, Environment $twigEngine)
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

    private function sendMail(string $email, $tramitacaoFormulario): void
    {
        $htmlContent = $this->twigEngine->render($this->bodyView, [
            'tramitacaoFormulario' => $tramitacaoFormulario,
        ]);

        $emailMessage = (new Email())
            ->subject($this->subject)
            ->to($email)
            ->from('no-reply@saude.gov.br')
            ->html($htmlContent);

        $this->mailer->send($emailMessage);
    }
}
