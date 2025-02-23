<?php

namespace App\CommandBus;

use App\CommandBus\RecuperarSenhaCommand;
use App\Entity\Usuario;
use App\Repository\UsuarioRepository;
use Twig\Environment;
use Symfony\Component\Security\Core\Encoder\EncoderFactoryInterface;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;

class RecuperarSenhaHandler
{
    /**
     * @var MailerInterface
     */
    private $mailer;
    
    /**
     * @var Environment
     */
    private $templateEngine;
    
    /**
     *
     * @var App\Repository\UsuarioRepository
     */
    private $usuarioRepository;
    
    /**
     *
     * @var \Symfony\Component\Security\Core\Encoder\EncoderFactoryInterface;
     */
    private $encoderFactory;
    
    /**
     * 
     * @param MailerInterface $mailer
     * @param UsuarioRepository $usuarioRepository
     * @param EncoderFactoryInterface $encoderFactory
     */
    public function __construct(
        MailerInterface $mailer, 
        Environment $templateEngine,
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
        
        $encoder = $this->encoderFactory->getEncoder('App\Entity\Usuario');
        
        $usuario->setDsSenha($encoder->encodePassword($senha, null));
        
        $this->usuarioRepository->add($usuario);
        $this->sendEmail($usuario, $command->getEmail(), $senha);        
    }
    
    private function sendEmail(Usuario $usuario, string $email, string $plainPassword)
    {
        $htmlBody = $this->templateEngine->render('/default/email_recuperacao_senha.html.twig', [
            'dsLogin' => $usuario->getDsLogin(),
            'dsSenha' => $plainPassword,
        ]);

        $emailMessage = (new Email())
            ->from('info.sgtes@saude.gov.br')
            ->to($email)
            ->subject('SIGPET - Recuperação de Senha')
            ->html($htmlBody);

        $this->mailer->send($emailMessage);
    }
}
