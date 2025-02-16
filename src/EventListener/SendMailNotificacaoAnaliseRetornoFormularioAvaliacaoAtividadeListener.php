<?php

namespace App\EventListener;

use App\Event\SendMailNotificacaoAnaliseRetornoFormularioAvaliacaoAtividadeEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use Twig\Environment;

class SendMailNotificacaoAnaliseRetornoFormularioAvaliacaoAtividadeListener implements EventSubscriberInterface
{
    private $subject = [
        'satisfatorio' => 'SIGPET-INFOSD – Análise de retorno do Formulário de Avaliação de Atividade – Formulário SATISFATÓRIO e FINALIZADO',
        'nao_satisfatorio' => 'SIGPET-INFOSD – Análise de retorno do Formulário de Avaliação de Atividade – Formulário NÃO SATISFATÓRIO',
    ];
    
    private $bodyView = 'tramita_formulario_atividade/email-notificacao-analise.html.twig';
    
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
            SendMailNotificacaoAnaliseRetornoFormularioAvaliacaoAtividadeEvent::NAME => 'onAnalisarFormulario'
        ];
    }
    
    /**
     * 
     * @param SendMailNotificacaoAnaliseRetornoFormularioAvaliacaoAtividadeEvent $event
     */
    public function onAnalisarFormulario(SendMailNotificacaoAnaliseRetornoFormularioAvaliacaoAtividadeEvent $event): void
    {
        $tramitacaoFormulario = $event->getTramitacaoFormulario();
        $email = $tramitacaoFormulario
            ->getProjetoPessoa()
            ->getPessoaPerfil()
            ->getPessoaFisica()
            ->getPessoa()
            ->getEnderecoWebAtivo();

        // Renderiza o corpo do e-mail com Twig
        $htmlContent = $this->twigEngine->render($this->bodyView, [
            'tramitacaoFormulario' => $tramitacaoFormulario,
        ]);

        // Determina o assunto com base na situação do formulário
        $subject = $tramitacaoFormulario->getSituacaoTramiteFormulario()->isDevolvido()
            ? $this->subject['nao_satisfatorio']
            : $this->subject['satisfatorio'];

        // Cria o e-mail usando Symfony Mailer
        $emailMessage = (new Email())
            ->from('no-reply@saude.gov.br')
            ->to($email->getDsEnderecoWeb())
            ->subject($subject)
            ->html($htmlContent);

        // Envia o e-mail
        $this->mailer->send($emailMessage);
    }
}
