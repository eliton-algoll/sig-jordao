<?php

namespace App\CommandBus;

use App\Entity\EnderecoWeb;
use App\Repository\PerfilRepository;
use Symfony\Component\Security\Core\Encoder\EncoderFactoryInterface;
use Symfony\Bundle\TwigBundle\TwigEngine;
use App\Repository\UsuarioRepository;
use App\Repository\PessoaFisicaRepository;
use App\Entity\Usuario;

class CadastrarAdministradorHandler
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
     * @var PerfilRepository
     */
    private $perfilRepository;
    
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
        PessoaFisicaRepository $pessoaFisicaRepository,
        PerfilRepository $perfilRepository
    ) {
        $this->mailer = $mailer;
        $this->templateEngine = $templateEngine;
        $this->encoderFactory = $encoderFactory;
        $this->usuarioRepository = $usuarioRepository;
        $this->pessoaFisicaRepository = $pessoaFisicaRepository;
        $this->perfilRepository = $perfilRepository;
    }
    
    /**
     * @param CadastrarAdministradorCommand $command
     */
    public function handle(CadastrarAdministradorCommand $command)
    {

        $user = $this->usuarioRepository->findBy(['dsLogin' => $command->getDsLogin()]);
        if( $user ) {
            throw new \InvalidArgumentException('O Login já está em uso. Selecione outra opção.');
        }

        $senha = bin2hex(random_bytes(4));
        $cpf = preg_replace("/[^0-9]/", "", $command->getNuCpf());
        $encoder = $this->encoderFactory->getEncoder('App\Entity\Usuario');
        $perfil = $this->perfilRepository->find(1);
        $pessoaFisica = $this->pessoaFisicaRepository->find($cpf);


        if (!$pessoaFisica->getUsuario()) {
            $usuario = new Usuario($pessoaFisica, $command->getDsLogin(), $encoder->encodePassword($senha, null));
        } else {
            $usuario = $pessoaFisica->getUsuario()->ativar();
            $usuario->setDsLogin($command->getDsLogin());
        }
        $emails = $usuario->getPessoaFisica()->getPessoa()->getEnderecosWebAtivos();

        $saveEmail = true;
        foreach ($emails as $email) {
            if( $command->getEmail() == $email->getDsEnderecoWeb()) {
                $saveEmail = false;
            }
        }
        if( $saveEmail ) {
            $pessoa = $usuario->getPessoaFisica()->getPessoa();
            $enderecoWeb = new EnderecoWeb($pessoa, $command->getEmail());
            $usuario->getPessoaFisica()->getPessoa()->getEnderecosWeb()->add($enderecoWeb);
        }

        $pessoaFisica->addPerfil($perfil);
        $this->usuarioRepository->add($usuario);


        foreach ($pessoaFisica->getPessoasPerfisAtivos() as $pessoaPerfil) {
            if ($pessoaPerfil->getPerfil()->isAdministrador()) {
                $this->sendEmailConfirmacao($usuario, $senha);
                break;
            }
        }
    }

    /**
     * @param Usuario $usuario
     * @param $rawPassword
     * @param bool $emailUsuarioNovo
     * @throws \Twig_Error
     */
    public function sendEmailConfirmacao(Usuario $usuario, $rawPassword)
    {
        $message = new \Swift_Message();
        $message->setFrom('info.sgtes@saude.gov.br');
        $message->setSubject('SIGPET - Confirmação de cadastro');
        $message->setBody($this->templateEngine->render('/participante/email_confirmacao_cadastro_adm.html.twig', array(
            'dsLogin' => $usuario->getDsLogin(),
            'dsSenha' => $rawPassword,
        )), 'text/html');

        $emails = $usuario->getPessoaFisica()->getPessoa()->getEnderecosWebAtivos();

        foreach ($emails as $email) {
            $message->addTo($email->getDsEnderecoWeb());
        }

        $this->mailer->send($message);
    }
}
