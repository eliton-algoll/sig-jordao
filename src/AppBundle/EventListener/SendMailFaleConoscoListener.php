<?php

namespace AppBundle\EventListener;

use AppBundle\Event\SendMailFaleConoscoEvent;
use Symfony\Bundle\TwigBundle\TwigEngine;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

final class SendMailFaleConoscoListener implements EventSubscriberInterface
{
    private $supportMails = [
        'petsaude@saude.gov.br'
    ];
    
    private $subject = 'Fale Conosco SIGPET - ';
    
    private $bodyView = 'fale_conosco/email.html.twig';
    
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

    public static function getSubscribedEvents()
    {
        return [
            SendMailFaleConoscoEvent::NAME => 'onSaveFaleConosco'
        ];
    }
    
    /**
     *
     * @param SendMailFaleConoscoEvent $event
     */
    public function onSaveFaleConosco(SendMailFaleConoscoEvent $event)
    {
        $faleConosco = $event->getFaleConosco();
        
        $message = \Swift_Message::newInstance()
            ->setFrom($faleConosco->getDsEmail())
            ->setTo($this->supportMails)
            ->setSubject($this->subject . $faleConosco->getAssuntoCompleto())
            ->setBody($this->twigEngine->render($this->bodyView, [
                'faleConosco' => $faleConosco
            ]), 'text/html');
        
        if ($this->mailer->send($message) === 0) {
            throw new \RuntimeException();
        }
    }
}
