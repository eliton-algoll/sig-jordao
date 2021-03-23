<?php

namespace AppBundle\CommandBus;

use AppBundle\CommandBus\RecuperarSenhaCommand;
use AppBundle\Entity\Usuario;
use AppBundle\Repository\UsuarioRepository;
use Symfony\Bundle\TwigBundle\TwigEngine;
use Symfony\Component\Security\Core\Encoder\EncoderFactoryInterface;

class RecuperarSenhaHandler
{
    /**
     * @var \Swift_Mailer
     */
    private $mailer;
    
    /**
     * @var TwigEngine
     */
    private $templateEngine;
    
    /**
     *
     * @var AppBundle\Repository\UsuarioRepository
     */
    private $usuarioRepository;
    
    /**
     *
     * @var \Symfony\Component\Security\Core\Encoder\EncoderFactoryInterface;
     */
    private $encoderFactory;
    
    /**
     * 
     * @param \Swift_Mailer $mailer
     * @param UsuarioRepository $usuarioRepository
     * @param EncoderFactoryInterface $encoderFactory
     */
    public function __construct(
        \Swift_Mailer $mailer, 
        TwigEngine $templateEngine,
        EncoderFactoryInterface $encoderFactory,
        UsuarioRepository $usuarioRepository         
    ) {
        $this->mailer               = $mailer;
        $this->templateEngine       = $templateEngine;
        $this->encoderFactory       = $encoderFactory;
        $this->usuarioRepository    = $usuarioRepository;        
    }
    
    /**
     * 
     * @param RecuperarSenhaCommand $command
     * @throws \Exception
     */
    public function handle(RecuperarSenhaCommand $command)
    {
        $usuario = $this->usuarioRepository->findOneBy(array('pessoaFisica' => $command->getCpf()));
        
        if (!$usuario) {
            throw new \Exception(sprintf('O CPF %s não possui cadastro.', $command->getCpf()));
        }  
        
        if (!$usuario->getPessoaFisica()->getPessoa()->hasEnderecoWeb($command->getEmail())) {
            throw new \Exception(sprintf('O e-mail %s não pertence ao usuário.', $command->getEmail()));
        }
                
        $senha = bin2hex(random_bytes(4));
        
        $encoder = $this->encoderFactory->getEncoder('AppBundle\Entity\Usuario');
        
        $usuario->setDsSenha($encoder->encodePassword($senha, null));
        
        $this->usuarioRepository->add($usuario);
        $this->sendEmail($usuario, $command->getEmail(), $senha);        
    }
    
    /**
     * 
     * @param Usuario $usuario
     * @param string $email
     * @param string $plainPassword
     */
    public function sendEmail(Usuario $usuario, $email, $plainPassword)
    {
        $message = new \Swift_Message();
        $message->setFrom('info.sgtes@saude.gov.br');
        $message->setSubject('SIGPET - Recuperação de Senha');
        $message->addTo($email);
        $message->setBody(
            $this->templateEngine->render(
                '/default/email_recuperacao_senha.html.twig',
                array('dsLogin' => $usuario->getDsLogin(), 'dsSenha' => $plainPassword)
            ), 
            'text/html'
        );        
        
        $this->mailer->send($message);
    }
}
