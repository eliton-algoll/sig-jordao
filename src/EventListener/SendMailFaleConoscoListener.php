<?php

namespace App\EventListener;

use App\Event\SendMailFaleConoscoEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use Twig\Environment;

final class SendMailFaleConoscoListener implements EventSubscriberInterface
{
    private $supportMails = [
        'petsaude@saude.gov.br'
    ];
    
    private $subject = 'Fale Conosco SIGPET - ';
    
    private $bodyView = 'fale_conosco/email.html.twig';
    
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

    public static function getSubscribedEvents()
    {
        return [
            SendMailFaleConoscoEvent::NAME => 'onSaveFaleConosco'
        ];
    }
    
    public function onSaveFaleConoscox(SendMailFaleConoscoEvent $event): void
    {
        $faleConosco = $event->getFaleConosco();

        // Renderiza o corpo do e-mail com Twig
        $htmlContent = $this->twigEngine->render($this->bodyView, [
            'faleConosco' => $faleConosco
        ]);

        // Cria o e-mail usando Symfony Mailer
        $email = (new Email())
            ->from($faleConosco->getDsEmail())
            ->to(...$this->supportMails)
            ->subject($this->subject . $faleConosco->getAssuntoCompleto())
            ->html($htmlContent);

        // Envia o e-mail
        $this->mailer->send($email);
    }
}
