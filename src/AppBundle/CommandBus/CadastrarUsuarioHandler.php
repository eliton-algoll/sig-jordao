<?php

namespace AppBundle\CommandBus;

use AppBundle\Entity\ProjetoPessoa;
use Symfony\Component\Security\Core\Encoder\EncoderFactoryInterface;
use Symfony\Bundle\TwigBundle\TwigEngine;
use AppBundle\Repository\UsuarioRepository;
use AppBundle\Repository\PessoaFisicaRepository;
use AppBundle\Entity\Usuario;

class CadastrarUsuarioHandler
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
     * @var \Symfony\Component\Security\Core\Encoder\EncoderFactory
     */
    private $encoderFactory;
    
    /**
     * @var UsuarioRepository
     */
    private $usuarioRepository;
    
    /**
     * @var PessoaFisicaRepository
     */
    private $pessoaFisicaRepository;
    
    /**
     * @param \Swift_Mailer $mailer
     * @param EncoderFactoryInterface $encoderFactory
     * @param UsuarioRepository $usuarioRepository
     * @param PessoaFisicaRepository $pessoaFisicaRepository
     */
    public function __construct(
        \Swift_Mailer $mailer,
        TwigEngine $templateEngine,
        EncoderFactoryInterface $encoderFactory, 
        UsuarioRepository $usuarioRepository,
        PessoaFisicaRepository $pessoaFisicaRepository
    ) {
        $this->mailer = $mailer;
        $this->templateEngine = $templateEngine;
        $this->encoderFactory = $encoderFactory;
        $this->usuarioRepository = $usuarioRepository;
        $this->pessoaFisicaRepository = $pessoaFisicaRepository;
    }
    
    /**
     * @param CadastrarUsuarioCommand $command
     */
    public function handle(CadastrarUsuarioCommand $command)
    {
        $senha = bin2hex(random_bytes(4));
        $encoder = $this->encoderFactory->getEncoder('AppBundle\Entity\Usuario');
        $pessoaFisica = $this->pessoaFisicaRepository->find($command->getNuCpf());
        $emailUsuarioNovo = false;

        if (!$pessoaFisica->getUsuario()) {
            $emailUsuarioNovo = true;
            $usuario = new Usuario($pessoaFisica, $command->getNuCpf(), $encoder->encodePassword($senha, null));
        } else {
            $usuario = $pessoaFisica->getUsuario()->ativar();
        }

        $this->usuarioRepository->add($usuario);

        foreach ($pessoaFisica->getPessoasPerfisAtivos() as $pessoaPerfil) {
            if ($pessoaPerfil->getPerfil()->isCoordenadorProjeto()) {
                $this->sendEmailConfirmacao($usuario, $senha, $emailUsuarioNovo, $command->getProjetoPessoa());
                break;
            }
        }
    }

    /**
     * @param Usuario $usuario
     * @param $rawPassword
     * @param bool $emailUsuarioNovo
     * @param ProjetoPessoa|null $projetoPessoa
     * @throws \Twig_Error
     */
    public function sendEmailConfirmacao(Usuario $usuario, $rawPassword, $emailUsuarioNovo = false, ProjetoPessoa $projetoPessoa = null)
    {
        $message = new \Swift_Message();
        $message->setFrom('info.sgtes@saude.gov.br');
        $message->setSubject('SIGPET - Confirmação de cadastro');
        $message->setBody($this->templateEngine->render('/participante/email_confirmacao_cadastro_usuario.html.twig', array(
            'dsLogin' => $usuario->getDsLogin(),
            'dsSenha' => $rawPassword,
            'emailUsuarioNovo' => $emailUsuarioNovo,
            'programa' => ($projetoPessoa) ? $projetoPessoa->getProjeto()->getPublicacao()->getPrograma() : null,
        )), 'text/html');

        $emails = $usuario->getPessoaFisica()->getPessoa()->getEnderecosWebAtivos();

        foreach ($emails as $email) {
            $message->addTo($email->getDsEnderecoWeb());
        }

        $this->mailer->send($message);
    }
}
