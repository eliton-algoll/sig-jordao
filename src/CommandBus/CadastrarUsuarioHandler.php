<?php

namespace App\CommandBus;

use App\Entity\ProjetoPessoa;
use Symfony\Component\Security\Core\Encoder\EncoderFactoryInterface;
use Symfony\Bundle\TwigBundle\TwigEngine;
use App\Repository\UsuarioRepository;
use App\Repository\PessoaFisicaRepository;
use App\Entity\Usuario;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;

class CadastrarUsuarioHandler
{
    /**
     * @var MailerInterface
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
     * @param MailerInterface $mailer
     * @param EncoderFactoryInterface $encoderFactory
     * @param UsuarioRepository $usuarioRepository
     * @param PessoaFisicaRepository $pessoaFisicaRepository
     */
    public function __construct(
        MailerInterface $mailer,
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
        $encoder = $this->encoderFactory->getEncoder('App\Entity\Usuario');
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
        $email = (new Email())
            ->from('info.sgtes@saude.gov.br')
            ->to($usuario->getPessoaFisica()->getPessoa()->getEnderecosWebAtivos()[0]->getDsEnderecoWeb()) // Ajuste conforme necessário
            ->subject('SIGPET - Confirmação de cadastro')
            ->html($this->templateEngine->render('/participante/email_confirmacao_cadastro_usuario.html.twig', [
                'dsLogin' => $usuario->getDsLogin(),
                'dsSenha' => $rawPassword,
                'emailUsuarioNovo' => $emailUsuarioNovo,
                'programa' => ($projetoPessoa) ? $projetoPessoa->getProjeto()->getPublicacao()->getPrograma() : null,
            ]));

        $this->mailer->send($email);
    }
}
