<?php

namespace App\Event;

use App\Entity\FaleConosco;
use Symfony\Component\EventDispatcher\Event;

final class SendMailFaleConoscoEvent extends Event
{
    const NAME = 'send_mail.fale_conosco';
    
    /**
     *
     * @var FaleConosco
     */
    protected $faleConosco;
    
    public function __construct(FaleConosco $faleConosco)
    {
        $this->faleConosco = $faleConosco;
    }
    
    /**
     *
     * @return FaleConosco
     */
    public function getFaleConosco()
    {
        return $this->faleConosco;
    }
}
