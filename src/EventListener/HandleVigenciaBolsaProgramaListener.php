<?php

namespace App\EventListener;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\EventDispatcher\Event;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Event\FilterControllerEvent;
use Symfony\Component\HttpKernel\KernelEvents;

final class HandleVigenciaBolsaProgramaListener implements EventSubscriberInterface
{
    const COMMAND_DISPATCH = 'handle_vigencia.bolsa_programa.command_dispatch';
    
    /**
     *
     * @var EntityManagerInterface 
     */
    private $entityManager;
    
    /**
     * 
     * @param EntityManagerInterface $entityManager
     */
    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }
    
    public static function getSubscribedEvents()
    {
        return [
           KernelEvents::CONTROLLER => 'onKernelController',
           self::COMMAND_DISPATCH => 'onCommandDispatch',
        ];
    }
    
    /**
     * 
     * @param FilterControllerEvent $event
     */
    public function onKernelController(FilterControllerEvent $event)
    {
        $controller = $event->getController();
        
        if (isset($controller[0]) && 
            ($controller[0] instanceof \App\Controller\ValorBolsaController ||
            $controller[0] instanceof \App\Controller\FolhaPagamentoController)
        ) {
            $this->handleVigenciaBolsaPrograma();
        }
    }
    
    /**
     * 
     * @param Event $event
     */
    public function onCommandDispatch(Event $event)
    {
        $this->handleVigenciaBolsaPrograma();        
    }
    
    private function handleVigenciaBolsaPrograma()
    {
        $data = $this->entityManager
            ->getRepository('App:ValorBolsaPrograma')
            ->findPendentesToBeVigentes();
        
        foreach ($data as $valorBolsaPrograma) {
            try {
                $valorBolsaProgramaVigente = $this->entityManager
                    ->getRepository('App:ValorBolsaPrograma')
                    ->getVigenteByPublicacaoAndPerfil(
                        $valorBolsaPrograma->getPublicacao(),
                        $valorBolsaPrograma->getPerfil()
                    );                
                $valorBolsaProgramaVigente->descontinuar();
                $valorBolsaPrograma->entrarEmVigencia();
                $this->entityManager->persist($valorBolsaProgramaVigente);
                $this->entityManager->persist($valorBolsaPrograma);
                $this->entityManager->flush();
            } catch (\Exception $ex) {                
                continue;
            }
        }
    }
}
