<?php

namespace AppBundle\Command;

use AppBundle\Entity\FolhaPagamento;
use AppBundle\EventListener\HandleVigenciaBolsaProgramaListener;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

final class RealizarAberturaFolhaCommand extends ContainerAwareCommand
{
    
    protected function configure()
    {
        $this->setName('sigpet:abrir-folha')
            ->setDescription('Abre as folhas planejadas de pagamento.');
    }
    
    /**
     * 
     * @param InputInterface $input
     * @param OutputInterface $output
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $output->writeln('Realizando abertura das folhas de pagamento planejadas...');
        
        $container = $this->getContainer();        
        $em = $container->get('doctrine.orm.default_entity_manager');
        $logger = $container->get('logger');
        $dispatcher = $container->get('event_dispatcher');
        
        $planejamentoMesesFolha = $container
            ->get('app.planejamento_mes_folha_repository')
            ->findNaoExecutadosByDate(new \DateTime());
        
        $situacaoFolha = $container
            ->get('app.situacao_folha_repository')
            ->find(\AppBundle\Entity\SituacaoFolha::ABERTA);
        
        foreach ($planejamentoMesesFolha as $planejamentoMesFolha) {
            try {
                $folhaPagamento = new FolhaPagamento(
                    $planejamentoMesFolha->getPlanejamentoAnoFolha()->getPublicacao(),
                    $situacaoFolha,
                    $planejamentoMesFolha->getNuMes(),
                    $planejamentoMesFolha->getPlanejamentoAnoFolha()->getNuAno()
                );                
                $folhaPagamento->setPlanejamentoMesFolha($planejamentoMesFolha);
                
                $em->persist($folhaPagamento);
                $em->flush();
                
                $this->sendMail();
            } catch (\Exception $ex) {
                $em->rollback();
                $this->sendMail(false, [
                    'error' => $ex->getMessage(),
                ]);
                $logger->error('[ REALIZAR ABERTURA FOLHA ] - ' . $ex->getMessage());                
            }
        }
        
        $subscriber = new HandleVigenciaBolsaProgramaListener($em);
        $dispatcher->addSubscriber($subscriber);
        $dispatcher->dispatch(HandleVigenciaBolsaProgramaListener::COMMAND_DISPATCH);
        
        $output->writeln('Concluído.');
    }
    
    /**
     * 
     * @param boolean $success
     * @param array $parameters
     */
    private function sendMail($success = true, array $parameters = [])
    {
        $container = $this->getContainer();
        $templateEngine = $container->get('templating');
        $options = [];
        $now = new \DateTime();
        
        if (true === $success) {
            $options['subject'] = 'Abertura Automática da Folha de Pagamento para o Mês/Ano Corrente: '. $now->format('m/Y') .' - Realizada com Sucesso';
            $options['template'] = 'planejamento_abertura_folha_pagamento/email_abertura_realizada.html.twig';            
        } else {
            $options['subject'] = 'Abertura Automática da Folha de Pagamento para o Mês/Ano Corrente: '. $now->format('m/Y') .' - NÃO REALIZADA';
            $options['template'] = 'planejamento_abertura_folha_pagamento/email_abertura_nao_realizada.html.twig';            
        }
        
        $message = \Swift_Message::newInstance()
            ->setSubject($options['subject'])
            ->setFrom('no-reply@saude.gov.br')
            ->setTo('petsaude@saude.gov.br')
            ->setBody($templateEngine->render($options['template'], $parameters), 'text/html');
        
        $mailer = $container->get('mailer');        
        $mailer->send($message);
    }
}
